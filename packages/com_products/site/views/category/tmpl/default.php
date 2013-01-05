<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
?>
<div class="products category<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?php if($this->params->get('show_category_title', 1)): ?>
		<div class="page-header">
			<h2>
				<?php echo JHtml::_('content.prepare', $this->category->title, '', 'com_products.category'); ?>
			</h2>
		</div>
	<?php endif; ?>

	<?php if ($this->items): ?>
		<?php $groups = array_chunk($this->items, 3); ?>
		<?php foreach ($groups as $group): ?>
			<div class="row-fluid">
				<?php foreach ($group as $item): ?>
					<div class="span4">
						<div class="image">
							<a href="<?php echo JRoute::_(ProductsHelperRoute::getProductRoute($item->slug, $item->catslug)); ?>"><img src="<?php echo $item->image; ?>" alt="<?php echo $this->escape($item->name); ?>" title="<?php echo $this->escape($item->name); ?>" /></a>
						</div>
						<h3><?php echo $this->escape($item->name); ?></h3>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<p>
			<?php echo JText::_('COM_PRODUCTS_NO_PRODUCT'); ?>
		</p>
	<?php endif; ?>

	<?php if ($this->params->get('show_pagination', 1)): ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)): ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
</div>
