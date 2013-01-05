<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Products Component Category Tree
 *
 * @static
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsCategories extends JCategories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   3.0
	 */
	public function __construct($options = array())
	{
		$options['table'] = '#__products';
		$options['extension'] = 'com_products';
		$options['statefield'] = 'state';
		$options['countItems'] = 1;

		parent::__construct($options);
	}
}
