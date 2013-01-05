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
 * Product model.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsModelProduct extends JModelAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var     string
	 * @since   3.0
	 */
	protected $text_prefix = 'COM_PRODUCTS_PRODUCT';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   3.0
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return;
			}

			// Get the current user object.
			$user = JFactory::getUser();

			if ($record->catid)
			{
				return $user->authorise('core.delete', 'com_products.category.' . (int) $record->catid);
			}
			else
			{
				return parent::canDelete($record);
			}
		}
	}

	/**
	 * Method to test whether a record can have its state changed.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   3.0
	 */
	protected function canEditState($record)
	{
		// Get the current user object.
		$user = JFactory::getUser();

		// Check for existing product.
		if (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_products.category.' . (int) $record->catid);
		}
		// Default to component settings if product not known.
		else
		{
			return parent::canEditState($record);
		}
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   3.0
	 */
	public function getTable($type = 'Product', $prefix = 'ProductsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   3.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_products.product', 'product', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('product.id'))
		{
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		$addForm = new SimpleXMLElement('<form />');
		$fields = $addForm->addChild('fields');
		$fields->addAttribute('name', 'fields');

		$fieldset = $fields->addChild('fieldset');
		$fieldset->addAttribute('name', 'jfields');
		$fieldset->addAttribute('label', 'COM_PRODUCTS_FIELDSET_FIELDS');

		// Get an instance of the generic fields model.
		$model = JModelLegacy::getInstance('Fields', 'ProductsModel', array('ignore_request' => true));
		$model->setState('list.ordering', 'a.ordering');
		$model->setState('list.direction', 'asc');

		foreach ($model->getItems() as $item)
		{
			$params = new JRegistry;
			$params->loadString($item->params);

			$field = $fieldset->addChild('field');
			$field->addAttribute('name',        $item->name);
			$field->addAttribute('type',        $item->type);
			$field->addAttribute('default',     $item->default);
			$field->addAttribute('class',       $params->get('class'));
			$field->addAttribute('required',    $item->required);
			$field->addAttribute('label',       $item->label);
			$field->addAttribute('description', $item->description);
		}

		$form->load($addForm, false);

		// Trigger the default form events.
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   3.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_products.edit.product.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('product.id') == 0)
			{
				$app = JFactory::getApplication();
				$data->set('catid', $app->input->get('catid', $app->getUserState('com_products.products.filter.category_id'), 'int'));
			}
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   3.0
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the metadata field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			// Convert value to money format.
			$item->price = number_format($item->price, 2, ',', '.');

			$item->fields = self::getFields($item->id);
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function prepareTable($table)
	{
		// Initialise variables.
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
		$table->alias = JApplication::stringURLSafe($table->alias);

		if (empty($table->alias))
		{
			$table->alias = JApplication::stringURLSafe($table->name);
		}

		if (empty($table->id))
		{
			// Set the values

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__products');
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
			else
			{
				// Set the values
				$table->modified    = $date->toSql();
				$table->modified_by = $user->get('id');
			}
		}

		// Convert value to db decimal format.
		$table->price = str_replace(array('.', ','), array('', '.'), $table->price);

		// Increment the content version number.
		$table->version++;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   3.0
	 */
	public function save($data)
	{
		// Initialiase variables.
		$dispatcher = JEventDispatcher::getInstance();
		$table = $this->getTable();
		$key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		if ($data['fields'])
		{
			$fields = (array) $data['fields'];
			unset($data['fields']);
		}

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Allow an exception to be thrown.
		try
		{
			// Load the row if saving an existing record.
			if ($pk > 0)
			{
				$table->load($pk);
				$isNew = false;
			}

			// Bind the data.
			if (!$table->bind($data))
			{
				$this->setError($table->getError());
				return false;
			}

			// Prepare the row for saving.
			$this->prepareTable($table);

			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			// Trigger the onContentBeforeSave event.
			$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, $table, $isNew));

			if (in_array(false, $result, true))
			{
				$this->setError($table->getError());
				return false;
			}

			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Clean the cache.
			$this->cleanCache();

			// Trigger the onContentAfterSave event.
			$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, $table, $isNew));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$pkName = $table->getKeyName();

		if (isset($table->$pkName))
		{
			$this->setState($this->getName() . '.id', $table->$pkName);
		}

		$this->setState($this->getName() . '.new', $isNew);

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');

		// Update the database.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Delete all fields.
		$query->delete();
		$query->from($db->quoteName('#__products_fields_values'));
		$query->where('product_id = ' . (int) $value->id);

		// Set the query and execute the delete.
		$db->setQuery($query);

		if ($db->execute())
		{
			foreach ($fields as $k => $v)
			{
				$query->clear();

				// Create the base insert statement.
				$query->insert($db->quoteName('#__products_fields_values'));
				$query->columns(array($db->quoteName('product_id'), $db->quoteName('field_name'), $db->quoteName('value')));
				$query->values($db->quote($value->id) . ', ' . $db->quote($k) . ', ' . $db->quote($v));

				// Set the query and execute the insert.
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table  A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 *
	 * @since   3.0
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = ' . (int) $table->catid;

		return $condition;
	}

	/**
	 * Method to get the fields values.
	 *
	 * @param   int  $id  The id of product.
	 *
	 * @return  array
	 *
	 * @since   3.0
	 */
	protected function getFields($id)
	{
		// Initialiase variables.
		$db     = $this->getDbo();
		$query  = $db->getQuery(true);
		$fields = array();

		// Create the base select statement.
		$query->select('a.field_name AS field, a.value');
		$query->from($db->quoteName('#__products_fields_values') . ' AS a');
		$query->where('a.product_id = ' . (int) $id);

		// Set the query and load the result.
		$db->setQuery($query);
		$items = $db->loadObjectList();

		foreach ($items as $item)
		{
			$fields[$item->field] = $item->value;
		}

		return $fields;
	}
}
