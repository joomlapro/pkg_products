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
 * HTML Cpanel View class for the Products component
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsViewCpanel extends JViewLegacy
{
	protected $modules = null;

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
		$input = JFactory::getApplication()->input;

		// Set toolbar items for the page.
		JToolbarHelper::title(JText::_('COM_PRODUCTS_MANAGER_CPANEL'), 'cpanel.png');
		JToolBarHelper::help('cpanel', $com = true);

		/*
		 * Set the template - this will display cpanel.php
		 * from the selected admin template.
		 */
		$input->set('tmpl', 'cpanel');

		// Display the cpanel modules.
		$this->modules = JModuleHelper::getModules('cpanel');

		// Display the submenu position modules.
		$this->iconmodules = JModuleHelper::getModules('icon');

		parent::display($tpl);
	}
}
