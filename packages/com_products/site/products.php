<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Include dependancies
require_once JPATH_COMPONENT . '/helpers/route.php';

// Execute the task.
$controller = JControllerLegacy::getInstance('Products');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
