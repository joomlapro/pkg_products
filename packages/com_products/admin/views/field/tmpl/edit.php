<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'field.cancel' || document.formvalidator.isValid(document.id('field-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('field-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_products&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="field-form" class="form-validate">
	<div class="row-fluid">
		<!-- Begin Fields -->
		<div class="span10 form-horizontal">
			<fieldset>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#details" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_PRODUCTS_NEW_FIELD') : JText::sprintf('COM_PRODUCTS_EDIT_FIELD', $this->item->id); ?></a></li>
					<li><a href="#publishing" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></a></li>
					<?php $fieldSets = $this->form->getFieldsets('params');
					foreach ($fieldSets as $name => $fieldSet): ?>
						<li><a href="#params-<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_($fieldSet->label); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('label'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('label'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('options'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('options'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('default'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('default'); ?></div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('required'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('required'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('ordering'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
								</div>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
						</div>
					</div>
					<div class="tab-pane" id="publishing">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
						</div>
						<?php if ($this->item->id): ?>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
								<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
							</div>
						<?php endif; ?>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('created_by_alias'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('created_by_alias'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
						</div>
					</div>
					<?php echo $this->loadTemplate('params'); ?>
				</div>
			</fieldset>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
		<!-- End Fields -->
		<!-- Begin Sidebar -->
		<div class="span2">
			<h4><?php echo JText::_('JDETAILS'); ?></h4>
			<hr />
			<fieldset class="form-vertical">
				<div class="control-group">
					<div class="controls"><?php echo $this->form->getValue('label'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
				</div>
			</fieldset>
		</div>
		<!-- End Sidebar -->
	</div>
</form>
