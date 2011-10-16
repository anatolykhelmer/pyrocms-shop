<h3><?php echo lang('shop.setting_title'); ?></h3>

<div class="tabs">

    <ul class="tab-menu">
        <li><a href="#item-payinfo" onclick="init_tabs(this);" title="Payinfo"><span><?php echo lang('shop.setting_payment_info'); ?></span></a></li>
    </ul>
	<input id="tab_active" type="hidden" value="<?php echo @$tab_active; ?>"/>
    <div id="item-payinfo">
		
		<div id="content-payinfo">
		   
		</div>
		<div class="buttons" id="ax_progress" style="display:none;">
		<img id="progress_long" src="<?php echo site_url(SHARED_ADDONPATH.'modules/shop/img/loader.gif'); ?>" />
		</div>
		<div class="buttons" id="ax_button">
			<input name="payinfopost" type="button" value=" Save "/>
			<br class="clear-both">
		</div>
    </div>

</div>
