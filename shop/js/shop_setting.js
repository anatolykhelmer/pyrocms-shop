function process_post(){
	
	jQuery('textarea.wysiwyg-advanced').ckeditor(function(){
	  this.destroy();
	});
				
    form = jQuery('form.f_pi');
    tab_active = jQuery('#tab_active').val();
    var id_out = '#content-'+tab_active;
    dataString = form.serialize();
    //alert(dataString);
    url_post = SITE_URL + 'admin/shop/ax_settingsave/'+tab_active;
    //alert(url_post);
    
    jQuery.ajax({
	url: url_post,
	type: "POST",
	data: dataString,
	success: function(dataResult){
		if(dataResult.length > 0) {
            //alert(dataResult);
			jQuery(id_out).html(dataResult);
			//gowysiwyg();
			pyro.init_ckeditor();
			jQuery('#ax_progress').hide(2);
			jQuery('#ax_button').show(4);
			
		} else {
			alert('Failed to open page.');
		}
	}
    
	});
}


function init_tabs(vthis)
{
	var modeform;
	var page_open = jQuery('#page_edited').val();
	page_open = parseInt(page_open);
	if(page_open > 0){
		modeform = 'edit';
	}else{
		modeform = 'add';
		page_open = 0;
	}
	
	var titleTabs = jQuery(vthis).attr('title');
	jQuery('#tab_active').val(titleTabs);
	if(titleTabs == 'Image'){
		init_picture();
	}
}



function init_setting()
{
	var modeform;
	var tab_open = jQuery('#tab_active').val();
	var id_out = '#content-'+tab_open;
	
    jQuery.ajax({
	url: SITE_URL + 'admin/shop/ax_settingread/' + tab_open + '/',
	type: "GET",
	success: function(data) {
		if(data.length > 0) {
			jQuery(id_out).html(data);
            pyro.init_ckeditor();
			
		} else {
			alert('Failed to open page.');
		}
	}
	
	
	});


	var postbtn = 'input[name="'+tab_open+'post"]';
	jQuery(postbtn).live('click', function(){
        jQuery('#ax_progress').show(4);
        jQuery('#ax_button').hide(2);
        process_post();
    }); 
    
    
}

jQuery(function(){
    init_setting();
    
    
    /* 
    jQuery('img.cb_shop').livequery(function() {
        jQuery(this).each(function(){
            var src_load = this.src.replace('thumb/', '');
            var anchor = jQuery('<a/>').attr({'href': src_load}).colorbox(); 
            jQuery(this).wrap(anchor);
        });
    });
    
    jQuery('div.shop_thumb_btn').livequery('click',function() {
        
        var r=confirm("Click OK to delete");
        if (r==true)
        {
            var id_del = jQuery(this).attr('title');
            del_img(id_del);
        }
    });
    
    jQuery('div.shop_defthumb_btn').livequery('click',function() {
        
        var id_default= jQuery(this).attr('title');
        if(id_default.length > 0){
            var r=confirm("Click OK to set this images as default");
            if (r==true)
            {
                    set_default(id_default);
            }
        }
    });
    
    jQuery("#datepost").livequery(function() {
		jQuery( "#datepost" ).datepicker({ dateFormat: 'yy-mm-dd'});
        //jQuery("a.ui-datepicker-prev").hide();
	}); */
});

