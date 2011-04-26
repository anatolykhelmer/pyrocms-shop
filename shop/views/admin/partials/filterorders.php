<div class="filter">
<?php echo form_open(); ?>
        <?php echo form_hidden('f_module', 'shop/list_orders'); ?>
        <ul>
                <li>
                    <?php echo lang('shop.item_status_label', 'f_status'); ?>
                    <?php echo form_dropdown('f_status', array(0 => lang('select.all'), 'canceled'=>lang('shop.order_canceled_label'), 'live'=>lang('shop.order_live_label'))); ?>
                </li>
                
        </ul>
<?php echo form_close(); ?>
<br class="clear-both">
</div>