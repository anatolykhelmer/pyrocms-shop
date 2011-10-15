<h3><?php echo sprintf(lang('shop.item_edit_title'), ''); ?></h3>

<?php echo form_open(uri_string(), 'class="crud"'); ?>
<div class="tabs">
    <ul class="tab-menu">
        <li><a href="#item-content" onclick="init_tabs(this);" title="Data"><span><?php echo lang('shop.item_content_title'); ?></span></a></li>
        <li><a href="#item-options" onclick="init_tabs(this);" title="Image"><span><?php echo lang('shop.item_image_label'); ?></span></a></li>
    </ul>
    <input id="page_edited" type="hidden" value="<?php echo @$page_edited; ?>"/>
    <input id="tab_active" type="hidden" value=""/>
    <div id="item-content">
		
		<div id="item-data1">
       
		</div>
		<div class="buttons" id="ax_progress" style="display:none;">
		<img id="progress_long" src="<?php echo site_url('uploads/progress/bar_wait.gif'); ?>" />
		</div>
		<div class="buttons" id="ax_button">
			<input name="data1post" type="button" value=" Save "/>
			<br class="clear-both">
		</div>
    </div>
    <div id="item-options">
		<div id="item-image">
		
        
		</div>
    </div>
</div>
<!--<div class="uttons float-right padding-top">
	<?php //$this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
</div>-->

<?php echo form_close(); ?>
