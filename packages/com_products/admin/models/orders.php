<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Methods supporting a list of order records.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsModelOrders extends JModelList
{
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
				'name', 'u.name',
				'COUNT(oi.order_id)',
				'status', 'a.status',
				'payment_id', 'a.payment_id',
				'created', 'a.created',
				'created_by', 'a.created_by',
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
		$app = JFactory::getApplication('administrator');
		$input = $app->input;

		// Adjust the context to support modal layouts.
		if ($layout = $input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_products');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.created', 'desc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   3.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   3.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$user  = JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.status, a.payment_id' .
				', a.created'
			)
		);
		$query->from($db->quoteName('#__products_orders') . ' AS a');

		// Join over the items.
		$query->select('SUM(oi.qty) AS nitems, COUNT(oi.product_id) AS nproducts');
		$query->join('LEFT', $db->quoteName('#__products_orders_items') . ' AS oi ON a.id = oi.order_id');

		// Join over the users.
		$query->select('u.name AS user_name');
		$query->join('LEFT', $db->quoteName('#__users') . ' AS u ON u.id = a.user_id');

		// Join over the payments.
		$query->select('p.name AS payment_name');
		$query->join('LEFT', $db->quoteName('#__products_payments') . ' AS p ON p.id = a.payment_id');

		// Filter by search in name.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.observations LIKE ' . $search . ')');
			}
		}

		$query->group('a.id, a.status, a.payment_id');

		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.created');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));

		// echo nl2br(str_replace('#__', 'jos_', $query));

		return $query;
	}
}
