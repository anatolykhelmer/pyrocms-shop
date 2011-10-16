<nav id="shortcuts">
	<h6><?php echo lang('cp_shortcuts_title'); ?></h6>
	<ul>
        <li><?php echo anchor('/admin/shop/list_orders', lang('shop.list_orders_title')); ?> </li>
		<li><?php echo anchor('/admin/shop/create_category', lang('shop.new_category_label'), 'class="add"') ?></li>
		<li><?php echo anchor('/admin/shop', lang('shop.list_label')); ?></li>
        <li><?php echo anchor('/admin/shop/create_item', lang('shop.new_item_label'), 'class="add"') ?></li>
        <li><?php echo anchor('/admin/shop/list_items', lang('shop.list_items_label')); ?></li>
        <?php if($auth->setting_options == 1){ ?>
        <li><?php echo anchor('/admin/shop/setting', lang('shop.setting_title')); ?></li>
        <?php } ?>
	</ul>
	<br class="clear-both" />
</nav>