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
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<div class="products categories-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_base_description')): ?>
		<?php // If there is a description in the menu parameters use that. ?>
		<?php if ($this->params->get('categories_description')): ?>
			<div class="category-desc base-desc">
				<?php echo JHtml::_('content.prepare', $this->params->get('categories_description'), '', 'com_products.categories'); ?>
			</div>
		<?php else: ?>
			<?php // Otherwise get one from the database if it exists. ?>
			<?php if ($this->parent->description): ?>
				<div class="category-desc base-desc">
					<?php echo JHtml::_('content.prepare', $this->parent->description, '', 'com_products.categories'); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<?php echo $this->loadTemplate('items'); ?>
</div>
