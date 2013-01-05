<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.products_client
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('radio');

/**
 * Provides input for Type.
 *
 * @package     Joomla.Plugin
 * @subpackage  User.products_client
 * @since       3.0
 */
class JFormFieldType extends JFormFieldRadio
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $type = 'Type';
}
