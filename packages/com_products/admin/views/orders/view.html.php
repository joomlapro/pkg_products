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
 * View class for a list of orders.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsViewOrders extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   3.0
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->state      = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->items      = $this->get('Items');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Load the submenu.
		ProductsHelper::addSubmenu('orders');

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function addToolbar()
	{
		// Include dependancies.
		require_once JPATH_COMPONENT . '/helpers/products.php';

		// Initialise variables.
		$state = $this->get('State');
		$canDo = ProductsHelper::getActions();
		$user  = JFactory::getUser();

		JToolbarHelper::title(JText::_('COM_PRODUCTS_MANAGER_ORDERS'), 'orders.png');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('order.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('order.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('orders.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('orders.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolbarHelper::archiveList('orders.archive');
			JToolbarHelper::checkin('orders.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'orders.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('orders.trash');
		}

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_products');
		}

		JToolBarHelper::help('orders', $com = true);

		JHtmlSidebar::setAction('index.php?option=com_products&view=orders');
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.created' => JText::_('JGLOBAL_CREATED'),
			'u.name' => JText::_('COM_PRODUCTS_HEADING_CLIENT'),
			'a.status' => JText::_('COM_PRODUCTS_HEADING_STATUS'),
			'COUNT(oi.order_id)' => JText::_('COM_PRODUCTS_HEADING_PRODUCTS'),
			'a.payment_id' => JText::_('COM_PRODUCTS_HEADING_PAYMENT'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
