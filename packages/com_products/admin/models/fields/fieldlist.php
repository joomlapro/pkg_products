<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * FieldList Field class for the Products.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class JFormFieldFieldList extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $type = 'FieldList';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.0
	 */
	protected function getOptions()
	{
		// Initialiase variables.
		$options = array(
			'calendar' => JText::_('COM_PRODUCTS_OPTION_CALENDAR'),
			'checkbox' => JText::_('COM_PRODUCTS_OPTION_CHECKBOX'),
			'checkboxes' => JText::_('COM_PRODUCTS_OPTION_CHECKBOXES'),
			'color' => JText::_('COM_PRODUCTS_OPTION_COLOR'),
			'editor' => JText::_('COM_PRODUCTS_OPTION_EDITOR'),
			'email' => JText::_('JGLOBAL_EMAIL'),
			'language' => JText::_('COM_PRODUCTS_OPTION_LANGUAGE'),
			'list' => JText::_('JGLOBAL_LIST'),
			'media' => JText::_('COM_PRODUCTS_OPTION_MEDIA'),
			'radio' => JText::_('COM_PRODUCTS_OPTION_RADIO'),
			'spacer' => JText::_('COM_PRODUCTS_OPTION_SPACER'),
			'tel' => JText::_('COM_PRODUCTS_OPTION_TEL'),
			'text' => JText::_('COM_PRODUCTS_OPTION_TEXT'),
			'textarea' => JText::_('COM_PRODUCTS_OPTION_TEXTAREA'),
			'url' => JText::_('COM_PRODUCTS_OPTION_URL')
		);

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
