<?php
/**
 * @package     Products
 * @subpackage  com_products
 * @copyright   Copyright (C) 2012 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Get the current user object.
$user = JFactory::getUser();
?>
<div class="row-fluid">
	<div class="span2">
		<div class="sidebar-nav">
			<ul class="nav nav-list">
				<li class="nav-header"><?php echo JText::_('COM_PRODUCTS_HEADER_SUBMENU'); ?></li>
				<li class="active"><a href="<?php echo $this->baseurl; ?>/index.php?option=com_products"><?php echo JText::_('COM_PRODUCTS_LINK_DASHBOARD'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_products&amp;view=products"><?php echo JText::_('COM_PRODUCTS_LINK_PRODUCTS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_products&amp;view=orders"><?php echo JText::_('COM_PRODUCTS_LINK_ORDERS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_products&amp;view=clients"><?php echo JText::_('COM_PRODUCTS_LINK_CLIENTS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_products&amp;view=fields"><?php echo JText::_('COM_PRODUCTS_LINK_FIELDS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_products&amp;view=activities"><?php echo JText::_('COM_PRODUCTS_LINK_ACTIVITIES'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_products&amp;view=payments"><?php echo JText::_('COM_PRODUCTS_LINK_PAYMENTS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_categories&amp;extension=com_products"><?php echo JText::_('COM_PRODUCTS_LINK_CATEGORIES'); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="span6">
		<?php
		foreach ($this->modules as $module)
		{
			$output = JModuleHelper::renderModule($module, array('style' => 'well'));
			$params = new JRegistry;
			$params->loadString($module->params);
			echo $output;
		}
		?>
	</div>
	<div class="span4">
		<?php
		foreach ($this->iconmodules as $iconmodule)
		{
			$output = JModuleHelper::renderModule($iconmodule, array('style' => 'well'));
			$params = new JRegistry;
			$params->loadString($iconmodule->params);
			echo $output;
		}
		?>
	</div>
</div>
