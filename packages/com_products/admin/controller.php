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
 * Products Component Controller
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsController extends JControllerLegacy
{
	/**
	 * @var     string  The default view.
	 * @since   3.0
	 */
	protected $default_view = 'cpanel';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   3.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/products.php';

		$view   = $this->input->get('view', 'cpanel');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'product' && $layout == 'edit' && !$this->checkEditId('com_products.edit.product', $id)
			|| $view == 'order' && $layout == 'edit' && !$this->checkEditId('com_products.edit.order', $id)
			|| $view == 'client' && $layout == 'edit' && !$this->checkEditId('com_products.edit.client', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_products&view=cpanel', false));

			return false;
		}

		parent::display();

		return $this;
	}
}
