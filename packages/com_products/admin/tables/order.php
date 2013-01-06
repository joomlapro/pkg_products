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
 * Order Table class
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsTableOrder extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  Driver A database connector object.
	 *
	 * @since   3.0
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__products_orders', 'id', $db);
	}

	/**
	 * Overload the store method for the Orders table.
	 *
	 * @param   boolean  $updateNulls  Toggle whether null values should be updated.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   3.0
	 */
	public function store($updateNulls = false)
	{
		// Initialiase variables.
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->id)
		{
			// Existing item.
			$this->modified    = $date->toSql();
			$this->modified_by = $user->get('id');
		}
		else
		{
			// New order. A order created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}

			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}

		// Attempt to store the user data.
		return parent::store($updateNulls);
	}
}
