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
 * Products Component Route Helper
 *
 * @static
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
abstract class ProductsHelperRoute
{
	protected static $lookup;

	/**
	 * Method to get a route configuration for the product view.
	 *
	 * @param   int  $id     The route of the product.
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function getProductRoute($id, $catid)
	{
		// Initialiase variables.
		$needles = array(
			'product' => array((int) $id)
		);

		// Create the link
		$link = 'index.php?option=com_products&view=product&id=' . $id;

		if ($catid > 1)
		{
			$categories = JCategories::getInstance('Products');
			$category = $categories->get($catid);

			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid=' . $catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem(array('category' => array(0))))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem())
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Method to get a route configuration for the form view.
	 *
	 * @param   int     $id      The id of the product.
	 * @param   string  $return  The return page variable.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function getFormRoute($id, $return = null)
	{
		// Create the link.
		if ($id)
		{
			$link = 'index.php?option=com_products&task=product.edit&p_id=' . $id;
		}
		else
		{
			$link = 'index.php?option=com_products&task=product.add&p_id=0';
		}

		if ($return)
		{
			$link .= '&return=' . $return;
		}

		return $link;
	}

	/**
	 * Method to get a route configuration for the category view.
	 *
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;
			$category = $catid;
		}
		else
		{
			$id = (int) $catid;
			$category = JCategories::getInstance('Products')->get($id);
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			$needles = array(
				'category' => array($id)
			);

			if ($item = self::_findItem($needles))
			{
				$link = 'index.php?Itemid=' . $item;
			}
			else
			{
				// Create the link
				$link = 'index.php?option=com_products&view=category&id=' . $id;

				if ($category)
				{
					$catids = array_reverse($category->getPath());
					$needles = array(
						'category' => $catids,
						'categories' => $catids
					);

					if ($item = self::_findItem($needles))
					{
						$link .= '&Itemid=' . $item;
					}
					elseif ($item = self::_findItem())
					{
						$link .= '&Itemid=' . $item;
					}
				}
			}
		}

		return $link;
	}

	/**
	 * Method to find the item.
	 *
	 * @param   array  $needles  The needles to find.
	 *
	 * @return  null
	 *
	 * @since   3.0
	 */
	protected static function _findItem($needles = null)
	{
		// Initialiase variables.
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component = JComponentHelper::getComponent('com_products');
			$items     = $menus->getItems('component_id', $component->id);

			if ($items)
			{
				foreach ($items as $item)
				{
					if (isset($item->query) && isset($item->query['view']))
					{
						$view = $item->query['view'];

						if (!isset(self::$lookup[$view]))
						{
							self::$lookup[$view] = array();
						}

						if (isset($item->query['id']))
						{
							self::$lookup[$view][$item->query['id']] = $item->id;
						}
						else
						{
							self::$lookup[$view][] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$view][(int) $id]))
						{
							return self::$lookup[$view][(int) $id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();

			if ($active && $active->component == 'com_products')
			{
				return $active->id;
			}
		}

		return null;
	}
}
