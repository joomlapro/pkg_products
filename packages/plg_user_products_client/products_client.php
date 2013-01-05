<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  User.products_client
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

// Add JavaScript Frameworks.
JHtml::_('jquery.framework');

// Load JavaScript.
JHtml::script('com_products/jquery.meio.mask.min.js', false, true);
JHtml::script('com_products/jquery.custom.js', false, true);

/**
 * Joomla Client plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  User.products_client
 * @since       3.0
 */
class PlgUserProducts_Client extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An array that holds the plugin configuration.
	 *
	 * @access  protected
	 * @since   3.0
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		JFormHelper::addFieldPath(__DIR__ . '/fields');
	}

	/**
	 * Called after the data for a JForm has been retrieved.
	 *
	 * It can be used to modify the data for a JForm object in memory before rendering.
	 *
	 * @param   string  $context  The context for the data.
	 * @param   int     $data     The user id.
	 *
	 * @return  boolean
	 *
	 * @since   3.0
	 */
	public function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile')))
		{
			return true;
		}

		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->profile) and $userId > 0)
			{
				// Load the profile data from the database.
				$db = JFactory::getDbo();
				$db->setQuery(
					'SELECT * FROM #__products_clients WHERE user_id = ' . (int) $userId
				);

				try
				{
					$data->profile = $db->loadAssoc();
				}
				catch (RuntimeException $e)
				{
					$this->_subject->setError($e->getMessage());
					return false;
				}

				// Format birthday.
				$data->profile['birthday'] = preg_replace('/(\d{4})-(\d{2})-(\d{2})/', "\\3-\\2-\\1", $data->profile['birthday']);
			}

			if (!JHtml::isRegistered('users.url'))
			{
				JHtml::register('users.url', array(__CLASS__, 'url'));
			}

			if (!JHtml::isRegistered('users.birthday'))
			{
				JHtml::register('users.birthday', array(__CLASS__, 'birthday'));
			}

			if (!JHtml::isRegistered('users.type'))
			{
				JHtml::register('users.type', array(__CLASS__, 'type'));
			}

			if (!JHtml::isRegistered('users.sex'))
			{
				JHtml::register('users.sex', array(__CLASS__, 'sex'));
			}

			if (!JHtml::isRegistered('users.zip'))
			{
				JHtml::register('users.zip', array(__CLASS__, 'zip'));
			}

			if (!JHtml::isRegistered('users.state'))
			{
				JHtml::register('users.state', array(__CLASS__, 'state'));
			}

			if (!JHtml::isRegistered('users.activity'))
			{
				JHtml::register('users.activity', array(__CLASS__, 'activity'));
			}
		}

		return true;
	}

	/**
	 * URL Field.
	 *
	 * @param   int  $value  The value.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function url($value)
	{
		if (empty($value))
		{
			return JHtml::_('users.value', $value);
		}
		else
		{
			$value = htmlspecialchars($value);
			if (substr($value, 0, 4) == "http")
			{
				return '<a href="' . $value . '" target="_blank" rel="nofollow">' . $value . '</a>';
			}
			else
			{
				return '<a href="http://' . $value . '" target="_blank" rel="nofollow">' . $value . '</a>';
			}
		}
	}

	/**
	 * Birthday Field.
	 *
	 * @param   int  $value  The value.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function birthday($value)
	{
		// Initialiase variables.
		$db = JFactory::getDbo();

		if (!$value)
		{
			$value = $db->getNullDate();
		}

		return date('d/m/Y', strtotime($value));
	}

	/**
	 * Type field.
	 *
	 * @param   int  $value  The value.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function type($value)
	{
		switch ($value)
		{
			case '1':
				return JText::_('PLG_USER_PRODUCTS_CLIENT_OPTION_INDIVIDUAL');
				break;

			case '2':
				return JText::_('PLG_USER_PRODUCTS_CLIENT_OPTION_CORPORATE');
				break;

			default:
				return '';
				break;
		}
	}

	/**
	 * Sex field.
	 *
	 * @param   int  $value  The value.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function sex($value)
	{
		switch ($value)
		{
			case 'F':
				return JText::_('PLG_USER_PRODUCTS_CLIENT_OPTION_FEMALE');
				break;

			case 'M':
				return JText::_('PLG_USER_PRODUCTS_CLIENT_OPTION_MALE');
				break;

			default:
				return '';
				break;
		}
	}

	/**
	 * State field.
	 *
	 * @param   string  $value  The value.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function state($value)
	{
		// Initialiase variables.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Prepare query.
		$query->select('a.name');
		$query->from('#__products_address_states AS a');
		$query->where('a.prefix = "' . (string) $value . '"');

		// Inject the query and load the result.
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Activity field.
	 *
	 * @param   int  $value  The value.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public static function activity($value)
	{
		// Initialiase variables.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Prepare query.
		$query->select('a.name');
		$query->from('#__products_activities AS a');
		$query->where('a.id = ' . (int) $value);

		// Inject the query and load the result.
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Called before a JForm is rendered.
	 *
	 * It can be used to modify the JForm object in memory before rendering.
	 *
	 * @param   JForm  $form  The form to be altered.
	 * @param   array  $data  The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since   3.0
	 */
	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form->getName();

		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration')))
		{
			return true;
		}

		// Add the registration fields to the form.
		JForm::addFormPath(__DIR__ . '/profiles');
		$form->loadFile('profile', false);

		$fields = array(
			'type',
			'cpf',
			'sex',
			'birthday',
			'phone',
			'mobile',
			'address_zipcode',
			'address_street',
			'address_district',
			'address_city',
			'address_state',
			'company_name',
			'trade_name',
			'cnpj',
			'ie',
			'activity_id',
			'website',
		);

		foreach ($fields as $field)
		{
			// Case using the users manager in admin
			if ($name == 'com_users.user')
			{
				// Remove the field if it is disabled in registration and profile
				if ($this->params->get('register-require_' . $field, 1) == 0
					&& $this->params->get('profile-require_' . $field, 1) == 0)
				{
					$form->removeField($field, 'profile');
				}
			}
			// Case registration
			elseif ($name == 'com_users.registration')
			{
				// Toggle whether the field is required.
				if ($this->params->get('register-require_' . $field, 1) > 0)
				{
					$form->setFieldAttribute($field, 'required', ($this->params->get('register-require_' . $field) == 2) ? 'required' : '', 'profile');
				}
				else
				{
					$form->removeField($field, 'profile');
				}
			}
			// Case profile in site or admin
			elseif ($name == 'com_users.profile' || $name == 'com_admin.profile')
			{
				// Toggle whether the field is required.
				if ($this->params->get('profile-require_' . $field, 1) > 0)
				{
					$form->setFieldAttribute($field, 'required', ($this->params->get('profile-require_' . $field) == 2) ? 'required' : '', 'profile');
				}
				else
				{
					$form->removeField($field, 'profile');
				}
			}
		}

		return true;
	}

	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 * This method sends a registration email to new users created in the backend.
	 *
	 * @param   array    $data    Holds the new user data.
	 * @param   boolean  $isNew   True if a new user is stored.
	 * @param   boolean  $result  True if user was succesfully stored in the database.
	 * @param   string   $error   Message.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		// Initialiase variables.
		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['profile']) && (count($data['profile'])))
		{
			try
			{
				// Update the database.
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);

				// Delete all array.
				$query->delete();
				$query->from($db->quoteName('#__products_clients'));
				$query->where('user_id = ' . (int) $userId);

				// Set the query and execute the delete.
				$db->setQuery($query);

				if ($db->execute())
				{
					$fields = array();
					$values = array();

					// Format birthday.
					$data['profile']['birthday'] = preg_replace('/(\d{2})\/(\d{2})\/(\d{4})/', "\\3-\\2-\\1", $data['profile']['birthday']);

					// Iterate over the object variables to build the query fields and values.
					foreach ($data['profile'] as $k => $v)
					{
						// Only process non-null scalars.
						if (is_array($v) or is_object($v) or $v === null)
						{
							continue;
						}

						// Ignore any internal fields.
						if ($k[0] == '_')
						{
							continue;
						}

						// Prepare and sanitize the fields and values for the database query.
						$fields[] = $db->quoteName($k);
						$values[] = $db->quote($v);
					}

					$query->clear();

					// Create the base insert statement.
					$query->insert($db->quoteName('#__products_clients'));
					$query->columns(array($db->quoteName('user_id'), implode(', ', $fields)));
					$query->values($db->quote($userId) . ', ' . implode(', ', $values));

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
			catch (RuntimeException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

	/**
	 * Remove all user profile information for the given user ID
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param   array    $user     Holds the user data.
	 * @param   boolean  $success  True if user was succesfully stored in the database.
	 * @param   string   $msg      Message.
	 *
	 * @return  boolean
	 *
	 * @since   3.0
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$userId = JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__products_clients WHERE user_id = ' . (int) $userId
				);

				$db->execute();
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}
}
