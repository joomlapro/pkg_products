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
 * View class for a list of clients.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsViewClients extends JViewLegacy
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
		ProductsHelper::addSubmenu('clients');

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
		require_once JPATH_COMPONENT . '/helpers/products.php';

		$state = $this->get('State');
		$canDo = ProductsHelper::getActions();
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_PRODUCTS_MANAGER_CLIENTS'), 'clients.png');

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_products');
		}

		JToolBarHelper::help('clients', $com = true);

		JHtmlSidebar::setAction('index.php?option=com_products&view=clients');
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
			'u.name' => JText::_('COM_PRODUCTS_HEADING_NAME'),
			'a.company_name' => JText::_('COM_PRODUCTS_HEADING_COMPANY_NAME'),
			'a.activity_id' => JText::_('COM_PRODUCTS_HEADING_ACTIVITY'),
			'a.address_city' => JText::_('COM_PRODUCTS_HEADING_ADDRESS_CITY'),
			'a.address_state' => JText::_('COM_PRODUCTS_HEADING_ADDRESS_STATE'),
			'a.user_id' => JText::_('JGRID_HEADING_ID'),
		);
	}
}
