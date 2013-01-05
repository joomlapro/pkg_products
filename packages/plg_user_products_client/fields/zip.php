<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('JPATH_BASE') or die;

/**
 * Zip Field class for the Products.
 *
 * @package     Products
 * @subpackage  com_products
 * @since       3.0
 */
class JFormFieldZip extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 *
	 * @since   3.0
	 */
	protected $type = 'Zip';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.0
	 */
	protected function getInput()
	{
		// Initialiase variables.
		$doc = JFactory::getDocument();

		$script = <<<ENDSCRIPT
		function getZipcode() {
			var onRequest = function () {
				document.id('loader').setStyle('display', 'inline-block');
			};
			var onComplete = function () {
				document.id('loader').setStyle('display', 'none');
			};
			var onSuccess = function (response) {
				var responseJSON = JSON.parse(response);
				document.id('jform_profile_address_street').value = responseJSON.tipo_logradouro + ', ' + responseJSON.logradouro;
				document.id('jform_profile_address_district').value = responseJSON.bairro;
				document.id('jform_profile_address_city').value = responseJSON.cidade;
				document.id('jform_profile_address_state').value = responseJSON.uf;
			};
			var queryString = {
				'option': 'com_products',
				'task': 'products.getzipcode',
				'zipcode': document.id('jform_profile_address_zipcode').value,
				'format': 'json'
			};
			var options = {
				url: 'index.php',
				method: 'get',
				update: null,
				data: queryString,
				onSuccess: onSuccess,
				onComplete: onComplete,
				onRequest: onRequest
			};
			var ajaxReq = new Request(options).send();
		};
ENDSCRIPT;

		$doc->addScriptDeclaration($script);

		// Initialize some field attributes.
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="input-small ' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		$html = array();

		$html[] = '<div class="input-append">';
		$html[] = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . '/>';
		$html[] = '<button class="btn btn-primary" onclick="getZipcode()" type="button">' . JText::_('JSEARCH_FILTER_SUBMIT') . '</button>';
		$html[] = '</div>';

		$html[] = '<div id="loader" style="display:none;margin-left:10px;"><img src="' . JUri::root() . 'media/system/images/mootree_loader.gif" alt="" /></div>';

		return implode($html);
	}
}
