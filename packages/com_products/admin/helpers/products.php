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
	public static function addSubmenu($vName = 'cpanel')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_CPANEL'),
			'index.php?option=com_products&view=cpanel',
			$vName == 'cpanel'
		);

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
			JText::_('COM_PRODUCTS_SUBMENU_FIELDS'),
			'index.php?option=com_products&view=fields',
			$vName == 'fields'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_ACTIVITIES'),
			'index.php?option=com_products&view=activities',
			$vName == 'activities'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PRODUCTS_SUBMENU_PAYMENTS'),
			'index.php?option=com_products&view=payments',
			$vName == 'payments'
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

	/**
	 * Get a list of filter options for the activities.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 * @return  3.0
	 */
	public static function getActivityOptions()
	{
		// Initialize variables.
		$options = array();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.name AS text');
		$query->from($db->quoteName('#__products_activities') . ' AS a');
		$query->order('a.ordering');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	/**
	 * Get a list of filter options for the address_states.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 * @return  3.0
	 */
	public static function getStateOptions()
	{
		// Initialize variables.
		$options = array();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.prefix AS value, a.name AS text');
		$query->from($db->quoteName('#__products_address_states') . ' AS a');
		$query->order('a.id');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	/**
	 * Get a list of filter options for the payments.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 * @return  3.0
	 */
	public static function getPaymentOptions()
	{
		// Initialize variables.
		$options = array();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.name AS text');
		$query->from($db->quoteName('#__products_payments') . ' AS a');
		$query->order('a.ordering');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	/**
	 * Get a list of filter options for the status.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 * @since   3.0
	 */
	public static function getStatusOptions()
	{
		// Build the filter options.
		$options = array();

		$options[] = JHtml::_('select.option', '0', JText::_('COM_PRODUCTS_OPTION_PENDING'));
		$options[] = JHtml::_('select.option', '1', JText::_('COM_PRODUCTS_OPTION_APPROVED'));
		$options[] = JHtml::_('select.option', '2', JText::_('COM_PRODUCTS_OPTION_NOT_APPROVED'));
		$options[] = JHtml::_('select.option', '3', JText::_('COM_PRODUCTS_OPTION_CANCELED'));

		return $options;
	}
}
