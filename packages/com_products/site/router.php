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
 * Build the route for the com_products component
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @since   3.0
 */
function productsBuildRoute(& $query)
{
	$segments = array();

	// Get a menu item based on Itemid or currently active
	$app      = JFactory::getApplication();
	$menu     = $app->getMenu();
	$params   = JComponentHelper::getParams('com_products');
	$advanced = $params->get('sef_advanced_link', 0);

	// We need a menu item. Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
	}
	else
	{
		$menuItem = $menu->getItem($query['Itemid']);
	}

	$mView  = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mCatid = (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
	$mId    = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

	if (isset($query['view']))
	{
		$view = $query['view'];

		if (empty($query['Itemid']))
		{
			$segments[] = $query['view'];
		}

		// We need to keep the view for forms since they never have their own menu item
		if ($view != 'form')
		{
			unset($query['view']);
		}
	}

	// Are we dealing with an product that is attached to a menu item?
	if (isset($query['view']) && ($mView == $query['view']) and (isset($query['id'])) and ($mId == (int) $query['id']))
	{
		unset($query['view']);
		unset($query['catid']);
		unset($query['id']);

		return $segments;
	}

	if (isset($view) and ($view == 'category' or $view == 'product'))
	{
		if ($mId != (int) $query['id'] || $mView != $view)
		{
			if ($view == 'product' && isset($query['catid']))
			{
				$catid = $query['catid'];
			}
			elseif (isset($query['id']))
			{
				$catid = $query['id'];
			}

			$menuCatid = $mId;
			$categories = JCategories::getInstance('Products');
			$category = $categories->get($catid);

			if ($category)
			{
				// TODO Throw error that the category either not exists or is unpublished
				$path = $category->getPath();
				$path = array_reverse($path);

				$array = array();

				foreach ($path as $id)
				{
					if ((int) $id == (int) $menuCatid)
					{
						break;
					}

					if ($advanced)
					{
						list($tmp, $id) = explode(':', $id, 2);
					}

					$array[] = $id;
				}

				$segments = array_merge($segments, array_reverse($array));
			}

			if ($view == 'product')
			{
				if ($advanced)
				{
					list($tmp, $id) = explode(':', $query['id'], 2);
				}
				else
				{
					$id = $query['id'];
				}

				$segments[] = $id;
			}
		}

		unset($query['id']);
		unset($query['catid']);
	}

	if (isset($query['layout']))
	{
		if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
		{
			if ($query['layout'] == $menuItem->query['layout'])
			{
				unset($query['layout']);
			}
		}
		else
		{
			if ($query['layout'] == 'default')
			{
				unset($query['layout']);
			}
		}
	}

	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   3.0
 */
function productsParseRoute($segments)
{
	$vars = array();

	// Get the active menu item.
	$app      = JFactory::getApplication();
	$menu     = $app->getMenu();
	$item     = $menu->getActive();
	$params   = JComponentHelper::getParams('com_products');
	$advanced = $params->get('sef_advanced_link', 0);

	// Count route segments
	$count = count($segments);

	// Standard routing for products.
	if (!isset($item))
	{
		$vars['view'] = $segments[0];
		$vars['id']   = $segments[$count - 1];

		return $vars;
	}

	// From the categories view, we can only jump to a category.
	$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';
	$categories = JCategories::getInstance('Products')->get($id)->getChildren();
	$vars['catid'] = $id;
	$vars['id'] = $id;
	$found = 0;

	foreach ($segments as $segment)
	{
		$segment = $advanced ? str_replace(':', '-', $segment) : $segment;

		foreach ($categories as $category)
		{
			if ($category->slug == $segment || $category->alias == $segment)
			{
				$vars['id']    = $category->id;
				$vars['catid'] = $category->id;
				$vars['view']  = 'category';
				$categories    = $category->getChildren();
				$found         = 1;

				break;
			}
		}

		if ($found == 0)
		{
			if ($advanced)
			{
				$db = JFactory::getDBO();
				$query = 'SELECT id FROM #__products WHERE catid = ' . $vars['catid'] . ' AND alias = ' . $db->quote($segment);
				$db->setQuery($query);
				$nid = $db->loadResult();
			}
			else
			{
				$nid = $segment;
			}

			$vars['id']   = $nid;
			$vars['view'] = 'product';
		}

		$found = 0;
	}

	return $vars;
}
