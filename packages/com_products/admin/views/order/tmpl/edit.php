<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
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
		if (task == 'order.cancel' || document.formvalidator.isValid(document.id('order-form'))) {
			<?php echo $this->form->getField('observations')->save(); ?>
			Joomla.submitform(task, document.getElementById('order-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_products&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="order-form" class="form-validate">
	<div class="row-fluid">
		<!-- Begin Orders -->
		<div class="span10 form-horizontal">
			<fieldset>
				<ul class="nav nav-tabs">
					<li class="active1"><a href="#details" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_PRODUCTS_NEW_ORDER') : JText::sprintf('COM_PRODUCTS_EDIT_ORDER', $this->item->id); ?></a></li>
					<li class="active"><a href="#items" data-toggle="tab"><?php echo JText::_('COM_PRODUCTS_FIELDSET_ITEMS'); ?></a></li>
					<li><a href="#publishing" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active1" id="details">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('status'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('status'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('payment_id'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('payment_id'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('observations'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('observations'); ?></div>
						</div>
					</div>
					<?php echo $this->loadTemplate('items'); ?>
					<div class="tab-pane" id="publishing">
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
				</div>
			</fieldset>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
		<!-- End Orders -->
		<!-- Begin Sidebar -->
		<div class="span2">
			<h4><?php echo JText::_('JDETAILS'); ?></h4>
			<hr />
			<fieldset class="form-vertical">
				<div class="control-group">
					<div class="controls"><?php echo $this->form->getValue('name'); ?></div>
				</div>
			</fieldset>
		</div>
		<!-- End Sidebar -->
	</div>
</form>
