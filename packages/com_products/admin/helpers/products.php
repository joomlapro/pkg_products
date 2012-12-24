<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Products helper.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function addSubmenu($vName = 'products')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_PRODUCTS'),
			'index.php?option=com_products&view=products',
			$vName == 'products'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_ORDERS'),
			'index.php?option=com_products&view=orders',
			$vName == 'orders'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_CLIENTS'),
			'index.php?option=com_products&view=clients',
			$vName == 'clients'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_products',
			$vName == 'categories'
		);

		if ($vName == 'categories')
		{
			JToolbarHelper::title(
				JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('com_products')),
				'products-categories');
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   int  $categoryId  The category ID.
	 *
	 * @return  JObject  A JObject containing the allowed actions.
	 *
	 * @since   3.0
	 */
	public static function getActions($categoryId = 0)
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_products';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_products.category.' . (int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_products', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
