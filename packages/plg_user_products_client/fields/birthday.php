<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.products_client
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('calendar');

/**
 * Provides input for Birthday.
 *
 * @package     Joomla.Plugin
 * @subpackage  User.products_client
 * @since       3.0
 */
class JFormFieldBirthday extends JFormFieldCalendar
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  3.0
	 */
	public $type = 'Birthday';
}
