<?php echo @$process_msg; ?>
<?php echo form_open(uri_string(), 'class="crud f_pi"'); ?>
 <ol>
    <li>
            <label for="pistatus"><?php echo lang('shop.item_status_label'); ?></label>
            <?php echo form_dropdown('pistatus', array('1' => lang('shop.show_label'), '0' => lang('shop.hide_label')), @$payinfo_live) ?>
    </li>

    <li class="even">
            <label for="picontent"><?php echo lang('shop.setting_payment_info'); ?></label>
            <?php echo form_textarea(array('id' => 'picontent', 'name' => 'picontent', 'value' => stripslashes(@$payinfo_content), 'rows' => 7, 'class' => 'wysiwyg-advanced')); ?>
    </li>
    
</ol>
    	<input name="item_id" type="hidden" value="<?php echo @$item_id; ?>"/>
        <input name="tab" type="hidden" value="<?php echo @$tab; ?>"/>
        <input name="tab_act" type="hidden" value="<?php echo @$tab_act; ?>"/>
<?php echo form_close(); ?>
<br style="clear:both;"/>
