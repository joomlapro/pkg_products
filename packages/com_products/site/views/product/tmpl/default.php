<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();

// Load the tooltip behavior.
JHtml::_('behavior.caption');
?>
<div class="product-item<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if (!$this->print): ?>
		<?php if ($canEdit || $params->get('show_print_icon', 1) || $params->get('show_email_icon', 1)): ?>
			<div class="btn-group pull-right">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i class="icon-cog"></i> <span class="caret"></span> </a>
				<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
				<ul class="dropdown-menu actions">
					<?php if ($params->get('show_print_icon', 1)): ?>
						<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
					<?php endif; ?>
					<?php if ($params->get('show_email_icon', 1)): ?>
						<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
					<?php endif; ?>
					<?php if ($canEdit): ?>
						<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<div class="pull-right">
			<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
		</div>
	<?php endif; ?>

	<div class="page-header">
		<h2>
			<?php echo $this->escape($this->item->name); ?>
		</h2>
	</div>

	<div class="row-fluid">
		<div class="span4">
			<img src="<?php echo $this->item->image; ?>" alt="<?php echo $this->escape($this->item->name); ?>" title="<?php echo $this->escape($this->item->name); ?>" />
		</div>
		<div class="span8">
			<?php echo $this->item->description; ?>
			<p>
				<strong><?php echo JText::_('COM_PRODUCTS_SIZE'); ?>:</strong> <?php echo $this->escape($this->item->size); ?><br />
				<strong><?php echo JText::_('COM_PRODUCTS_UNIT'); ?>:</strong> <?php echo $this->escape($this->item->unit); ?>
			</p>
			<h3><?php echo JText::_('COM_PRODUCTS_INGREDIENTS'); ?></h3>
			<p>
				<?php echo $this->escape($this->item->ingredients); ?>
			</p>
		</div>
	</div>
</div>
