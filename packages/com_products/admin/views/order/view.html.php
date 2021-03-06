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
 * View to edit a order.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsViewOrder extends JViewLegacy
{
	protected $form;

	protected $item;

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
		// Initialiase variables.
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

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
		JFactory::getApplication()->input->set('hidemainmenu', true);

		// Initialiase variables.
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);

		// Since we don't track these assets at the item level.
		$canDo      = ProductsHelper::getActions();

		JToolbarHelper::title($isNew ? JText::_('COM_PRODUCTS_MANAGER_ORDER_NEW') : JText::_('COM_PRODUCTS_MANAGER_ORDER_EDIT'), 'order.png');

		// If not checked out, can save the item.
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::apply('order.apply');
			JToolbarHelper::save('order.save');
		}

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::save2new('order.save2new');
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			JToolbarHelper::save2copy('order.save2copy');
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('order.cancel');
		}
		else
		{
			JToolbarHelper::cancel('order.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolBarHelper::help('order', $com = true);
	}
}
