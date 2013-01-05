<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Products Component Featured Model
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsModelFeatured extends JModelList
{
	/**
	 * Category items data
	 *
	 * @var     array
	 */
	protected $_item = null;

	protected $_articles = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	/**
	 * The category that applies.
	 *
	 * @access  protected
	 * @var     object
	 */
	protected $_category = null;

	/**
	 * The list of other product categories.
	 *
	 * @access  protected
	 * @var     array
	 */
	protected $_categories = null;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   3.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'state', 'a.state',
				'ordering', 'a.ordering',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app    = JFactory::getApplication();

		// Load the parameters. Merge Component and Menu Item params into new object.
		$params = JComponentHelper::getParams('com_products');
		$menuParams = new JRegistry;

		if ($menu = $app->getMenu()->getActive())
		{
			$menuParams->loadString($menu->params);
		}

		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		$this->setState('list.limit', $limit);

		$limitstart = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		$orderCol = JRequest::getCmd('filter_order', 'ordering');

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'ordering';
		}

		$this->setState('list.ordering', $orderCol);

		$listOrder = JRequest::getCmd('filter_order_Dir', 'ASC');

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		$this->setState('list.direction', $listOrder);

		// Get the user object.
		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_products')) && (!$user->authorise('core.edit', 'com_products')))
		{
			// Limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);

			// Filter by start and end dates.
			$this->setState('filter.publish_date', true);
		}

		$this->setState('filter.language', $app->getLanguageFilter());

		//
		$this->setState('filter.type', $mergedParams->get('type'));

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get a list of items.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 * @since   3.0
	 */
	public function getItems()
	{
		// Invoke the parent getItems method to get the main list
		$items = parent::getItems();

		// Convert the params field into an object, saving original in _params
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = &$items[$i];

			if (!isset($this->_params))
			{
				$params = new JRegistry;
				$params->loadString($item->params);
				$item->params = $params;
			}
		}

		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   3.0
	 */
	protected function getListQuery()
	{
		// Initialiase variables.
		$user   = JFactory::getUser();
		$groups = implode(', ', $user->getAuthorisedViewLevels());

		// Create a new query object.
		$db     = $this->getDbo();
		$query  = $db->getQuery(true);

		// Select required fields from the categories.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from($db->quoteName('#__products') . ' AS a');

		// Filter by type.
		$type = $this->getState('filter.type');

		switch ($type)
		{
			case '1':
				$query->where('a.promotion != 0');
				break;

			case '2':
				$query->where('a.release = 1');
				break;

			default:
				$query->where('a.featured = 1');
				break;
		}

		// Join over the categories.
		$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access');
		$query->join('INNER', '#__categories AS c ON c.id = a.catid');

		// Filter by access level.
		$query->where('a.access IN (' . $groups . ')');
		$query->where('c.access IN (' . $groups . ')');

		// Filter by category.
		if ($categoryId = $this->getState('category.id'))
		{
			$query->where('a.catid = ' . (int) $categoryId);
		}

		// Sqlsrv change... aliased c.published to cat_published
		// Join to check for category published state in parent categories up the tree
		$query->select('c.published as cat_published, CASE WHEN badcats.id is null THEN c.published ELSE 0 END AS parents_published');
		$subquery = 'SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
		$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
		$subquery .= 'WHERE parent.extension = ' . $db->quote('com_products');

		// Find any up-path categories that are not published
		// If all categories are published, badcats.id will be null, and we just use the product state
		$subquery .= ' AND parent.published != 1 GROUP BY cat.id ';

		// Select state to unpublished if up-path category is unpublished
		$publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 0 END';
		$query->join('LEFT OUTER', '(' . $subquery . ') AS badcats ON badcats.id = c.id');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);

			// Filter by start and end dates.
			$nullDate = $db->quote($db->getNullDate());
			$date = JFactory::getDate();
			$nowDate = $db->quote($date->toSql());
			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
			$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
			$query->where($publishedWhere . ' = ' . (int) $published);
		}

		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ', ' . $db->quote('*') . ')');
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to get category data for the current featured
	 *
	 * @return  object
	 *
	 * @since   3.0
	 */
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			// Initialiase variables.
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry;

			if ($active)
			{
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_items', 1) || $params->get('show_empty_categories', 0);
			$categories = JCategories::getInstance('Products', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			if (is_object($this->_item))
			{
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;
				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}
				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else
			{
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent category.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 *
	 * @since   3.0
	 */
	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getFeatured();
		}

		return $this->_parent;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 *
	 * @since   3.0
	 */
	function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getFeatured();
		}

		return $this->_leftsibling;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 *
	 * @since   3.0
	 */
	function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getFeatured();
		}

		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 *
	 * @since   3.0
	 */
	function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getFeatured();
		}

		return $this->_children;
	}
}
