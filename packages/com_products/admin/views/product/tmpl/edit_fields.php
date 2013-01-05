<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$fieldSets = $this->form->getFieldsets('fields');

foreach ($fieldSets as $name => $fieldSet): ?>
	<div class="tab-pane" id="fields-<?php echo $name; ?>">
		<?php if (isset($fieldSet->description) && trim($fieldSet->description)):
			echo '<p class="alert alert-info">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
		endif; ?>
		<?php foreach ($this->form->getFieldset($name) as $field): ?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?></div>
				<div class="controls"><?php echo $field->input; ?></div>
			</div>
		<?php endforeach; ?>
	</div>
<?php endforeach;
