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
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'product.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task);
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
		}
	}
</script>
<div class="products edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_products&p_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('product.save')">
					<i class="icon-ok"></i> <?php echo JText::_('JSAVE'); ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('product.cancel')">
					<i class="icon-cancel"></i> <?php echo JText::_('JCANCEL'); ?>
				</button>
			</div>
		</div>
		<fieldset>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#editor" data-toggle="tab"><?php echo JText::_('JEDITOR'); ?></a></li>
				<li><a href="#publishing" data-toggle="tab"><?php echo JText::_('COM_PRODUCTS_FIELDSET_PUBLISHING'); ?></a></li>
				<li><a href="#language" data-toggle="tab"><?php echo JText::_('JFIELD_LANGUAGE_LABEL'); ?></a></li>
				<?php $fieldSets = $this->form->getFieldsets('metadata');
				foreach ($fieldSets as $name => $fieldSet): ?>
				<li><a href="#metadata-<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_('COM_PRODUCTS_FIELDSET_METADATA'); ?></a></li>
				<?php endforeach; ?>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="editor">
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
					</div>

					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
					</div>

					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('ordering'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
					</div>

					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('image'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('size'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('size'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('ingredients'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('ingredients'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('price'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('unit'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('unit'); ?></div>
					</div>

					<?php echo $this->form->getInput('description'); ?>
				</div>
				<div class="tab-pane" id="publishing">
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('created_by_alias'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('created_by_alias'); ?></div>
					</div>
					<?php if ($this->item->params->get('access-change')): ?>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('featured'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('publish_up'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('publish_down'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
						</div>
					<?php endif; ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
					</div>
					<?php if (is_null($this->item->id)): ?>
						<div class="control-group">
							<div class="control-label"></div>
							<div class="controls"><?php echo JText::_('COM_PRODUCTS_ORDERING'); ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="tab-pane" id="language">
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
					</div>
				</div>
				<?php $fieldSets = $this->form->getFieldsets('metadata');

				foreach ($fieldSets as $name => $fieldSet): ?>
					<div class="tab-pane" id="metadata-<?php echo $name; ?>">
					<?php if (isset($fieldSet->description) && trim($fieldSet->description)):
						echo '<p class="alert alert-info">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
					endif; ?>
						<?php if ($name == 'jmetadata'): // Include the real fields in this panel. ?>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('metadesc'); ?></div>
								<div class="controls"><?php echo $this->form->getInput('metadesc'); ?></div>
							</div>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('metakey'); ?></div>
								<div class="controls"><?php echo $this->form->getInput('metakey'); ?></div>
							</div>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('xreference'); ?></div>
								<div class="controls"><?php echo $this->form->getInput('xreference'); ?></div>
							</div>
						<?php endif; ?>
						<?php foreach ($this->form->getFieldset($name) as $field): ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
				<?php if ($this->params->get('enable_category', 0) == 1): ?>
				<input type="hidden" name="jform[catid]" value="<?php echo $this->params->get('catid', 1); ?>" />
				<?php endif; ?>
			</div>
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>
