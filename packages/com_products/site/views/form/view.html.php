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
 * HTML Product View class for the Products component.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsViewForm extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $return_page;

	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  The template file to include.
	 *
	 * @return  mixed  False on error, null otherwise.
	 *
	 * @since   3.0
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$app		 = JFactory::getApplication();
		$user        = JFactory::getUser();

		// Get model data.
		$state       = $this->get('State');
		$item        = $this->get('Item');
		$form        = $this->get('Form');
		$return_page = $this->get('ReturnPage');

		if (empty($item->id))
		{
			$authorised = $user->authorise('core.create', 'com_products') || (count($user->getAuthorisedCategories('com_products', 'core.create')));
		}
		else
		{
			$authorised = $item->params->get('access-edit');
		}

		if ($authorised !== true)
		{
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		if (!empty($item) && isset($item->id))
		{
			// Create shortcuts.
			$item->metadata = json_decode($item->metadata);
			$item->images   = json_decode($item->images);

			$tmp = new stdClass;
			$tmp->metadata = $item->metadata;
			$tmp->images   = $item->images;
			$form->bind($tmp);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// Create a shortcut to the parameters.
		$params	= &$state->params;

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->state       = &$state;
		$this->item        = &$item;
		$this->form        = &$form;
		$this->return_page = &$return_page;
		$this->params      = &$params;
		$this->user        = &$user;

		if ($this->params->get('enable_category') == 1)
		{
			$this->form->setFieldAttribute('catid', 'default', $params->get('catid', 1));
		}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function _prepareDocument()
	{
		// Initialiase variables.
		$app     = JFactory::getApplication();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();
		$title   = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if (empty($this->item->id))
		{
			$head = JText::_('COM_PRODUCTS_FORM_SUBMIT_PRODUCT');
		}
		else
		{
			$head = JText::_('COM_PRODUCTS_FORM_EDIT_PRODUCT');
		}

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', $head);
		}

		$title = $this->params->def('page_title', $head);

		if ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);

		$pathway = $app->getPathWay();
		$pathway->addItem($title, '');

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
