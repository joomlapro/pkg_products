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
 * Products JSON controller for Products Component.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class ProductsControllerProducts extends JControllerLegacy
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   3.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('getzipcode', 'getZipCode');
	}

	/**
	 * Method to get zipcode.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function getZipCode()
	{
		// Initialiase variables.
		$app   = JFactory::getApplication();
		$input = $app->input;

		$request = 'http://republicavirtual.com.br/web_cep.php?cep=' . urlencode($input->get('zipcode')) . '&formato=json';
		$return = file_get_contents($request);

		// Check the data.
		if (empty($return))
		{
			$return = '{"resultado":"0","resultado_txt":"sucesso - cep n\u00e3o encontrado","uf":"","cidade":"","bairro":"","tipo_logradouro":"","logradouro":""}';
		}

		// Use the correct json mime-type
		header('Content-Type: application/json');

		// Send the response.
		echo $return;

		$app->close();
	}
}
