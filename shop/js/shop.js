function add_value_textbox()
{
    jQuery(".add_value").live('click', function(){
        var options_counter = jQuery("ol#options").children("li").length;
        var counter = jQuery(this).parent().parent().children().length;
        var option_value = jQuery("ol.option_values li span").html();
        var html_to_paste = '<li><label for="value">' + option_value + ' #'
                            + counter + '</label><input type="text" name="option'
                            + options_counter + '_value[' + counter + ']"></li>';

        jQuery(this).parent().before(html_to_paste);
        return false;
    });
}

function add_option()
{
    jQuery("#add_option").click(function(){
        var counter = jQuery("ol#options").children("li").length + 1;
        var add_class = "";
        var option_val  = jQuery("ol.option_values li span").html();
        var option_name = jQuery("ol#options li span").html();
        var option_no = jQuery("ol#options h3 span").html();
        var add_value = jQuery(".add_value").html();
        var delete_value = jQuery(".delete_value").html();

        if (counter%2 == 0) add_class = "class=even";
        var option_to_paste = "<li "+ add_class +" ><h3>" + option_no +
            + counter + "</h3><label for=option_name["
            + counter + "]>" + option_name + "</label><input type=text name=option_name["
            + counter + "]><ol class=option_values><li><label for=option"
            + counter +"_value[1]>"+ option_val +" #1</label><input type=text name=option"
            + counter + "_value[1]></li><li>[ <a href=# class =add_value>"
            + add_value + "</a> ] | [ <a href=# class=delete_value>" + delete_value + "</a> ]</li></ol></li>";


        jQuery("ol#options").append(option_to_paste);
        return false;
    });
}



function delete_option()
{
    jQuery("#delete_option").click(function(){
        if (jQuery("ol#options").children("li").length > 1) {
            jQuery("ol#options li").has("ol").last().remove();
        }
        return false;
    });
}

function delete_value_textbox()
{
    jQuery(".delete_value").live('click', function(){
        var len = jQuery(this).parent().parent().children().length;
        jQuery(this).parent().prev().remove();
        return false;
    });
}

var editor1;

function process_post(){
	
	jQuery('textarea.wysiwyg-unique').ckeditor(function(){
	  this.destroy();
	});
				
    form = jQuery('form.crud');
    item_id = jQuery('input[name="item_id"]', form).val();
    tab_active = jQuery('#tab_active').val();
    tab_action = jQuery('input[name="tab_act"]').val();
    dataString = form.serialize();
    url_post = SITE_URL + 'admin/shop/ax_save/'+tab_active+'/'+tab_action;
    //alert(url_post);
    
    jQuery.ajax({
	url: url_post,
	type: "POST",
	data: dataString,
	success: function(dataResult){
		if(dataResult.length > 0) {
            //alert(dataResult);
			jQuery('#item-data1').html(dataResult);
			//gowysiwyg();
			setTimeout(function(){
				
				jQuery('textarea.wysiwyg-unique').ckeditor({
					toolbar: [
						 ['TextColor', 'FontSize', '-', 'Cut','Copy','Paste','PasteFromWord'],['-', 'Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
						 ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					  ],
					width: '99%',
					height: 140,
					dialog_backgroundCoverColor: '#000000'
				});
			}, 2000);
			jQuery('#ax_progress').hide(2);
			jQuery('#ax_button').show(4);
			
		} else {
			alert('Failed to open new item.');
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


function init_picture()
{
	var modeform;
	
	var item_id = jQuery('input[name="item_id"]').val();
	if(parseInt(item_id) > 0){

	jQuery.ajax({
	url: SITE_URL + 'admin/shop/ax_image/' + item_id,
	type: "GET",
	success: function(data) {
		if(data.length > 0) {
			jQuery('#item-image').html(data);
            setTimeout(function(){
                init_list_img();
            }, 2000);
		} else {
			jQuery('#item-image').html('Failed to open \'Item\' images.');
		}
	}
	});

	}else{
		jQuery('#item-image').html('You must save \'Item\' data before uploading images.');
	}
}

function init_list_img()
{
	var item_id = jQuery('input[name="item_id"]').val();
	if(parseInt(item_id) > 0){
    
	jQuery.ajax({
	url: SITE_URL + 'admin/shop/ax_listimage/' + item_id ,
	type: "GET",
	success: function(data) {
		if(data.length > 0) {
			jQuery('#list_item_img').html(data);
		} else {
			jQuery('#list_item_img').html('Failed to open \'Item\' images.');
		}
	}
	});

	}else{
		jQuery('#list_item_img').html('You must save \'Item\' data before uploading images.');
	}
}

function del_img(id_del)
{
	jQuery.ajax({
	url: SITE_URL + 'admin/shop/ax_delimage/' + id_del ,
	type: "GET",
	success: function(data) {
        //alert(data);
		init_list_img();
	}
	});

}

function set_default(id_default)
{
	jQuery.ajax({
	url: SITE_URL + 'admin/shop/ax_setdefault/' + id_default ,
	type: "GET",
	success: function() {
		init_list_img();
	}
	});

}

function init_form()
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
	
	jQuery('#tab_active').val('Data');
	
    jQuery.ajax({
	url: SITE_URL + 'admin/shop/ax_read/' + modeform + '/' + page_open,
	type: "GET",
	
	success: function(data) {
		if(data.length > 0) {
			jQuery('#item-data1').html(data);
			setTimeout(function(){
				jQuery('textarea.wysiwyg-unique').ckeditor({
					toolbar: [
						 ['TextColor', 'FontSize', '-', 'Cut','Copy','Paste','PasteFromWord'],['-', 'Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
						 ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
					  ],
					width: '99%',
					height: 140
				});
			}, 2000);
			
			
		} else {
			alert('Failed to open new item.');
		}
	}
	
	
	});


	
    form = jQuery('form.crud');
	jQuery('input[name="data1post"]', form).live('click', function(){
        
        jQuery('#ax_progress').show(4);
        jQuery('#ax_button').hide(2);
        process_post();
    });
    
    
}

jQuery(function(){
    add_value_textbox();
    add_option();
    delete_option();
    delete_value_textbox();
    init_form();
    
    
    
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
	});
});

function startUpload(){
      document.getElementById('f1_upload_process').style.display = 'block';
      document.getElementById('f1_upload_form').style.display = 'none';
      
      var iframe = document.getElementById('upload_target');
        if (navigator.userAgent.indexOf("MSIE") > -1 && !window.opera){
            iframe.onreadystatechange = function(){
                if (iframe.readyState == "complete"){
                    stopUpload();
                }
            };
        } else {
            iframe.onload = function(){
                stopUpload();
            };
        }
      return true;
}

function stopUpload(){

      var ret = jQuery("#upload_target").contents().find('body').html();
      //alert(ret);
      var data = JSON.parse(ret);
      document.getElementById('upload_status_msg').innerHTML = unescape(data.message);
      //document.getElementById('upload_status_msg').innerHTML = unescape(data.debug);

      document.getElementById('f1_upload_process').style.display = 'none';
      document.getElementById('f1_upload_form').style.display = 'block';
      
      init_list_img();
      return true;   
}


