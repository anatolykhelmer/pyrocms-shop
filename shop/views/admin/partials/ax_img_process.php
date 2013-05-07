<?php
if(@strlen(@trim(@$process_msg)) >0){
    echo @$process_msg;
}else{
?>
<div id="upload_status_msg"></div>
<div id="list_item_img"></div>

<form class="img_upload_form" action="<?php echo site_url('admin/shop/ax_upload/'.@$id_item); ?>" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
     <p id="f1_upload_process"><img src="<?php echo site_url(SHARED_ADDONPATH.'modules/shop/img/loader.gif'); ?>" /><br/></p>
     <p id="f1_upload_form">
         <br/><br/>
         Upload Images: <input name="myfile" type="file" size="50" />
			<input class="button" type="submit" name="submitBtn" value="Upload" />
         
        
     </p>
     
     <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
 </form>
 <br style="clear:both;"/>
<?php

}
