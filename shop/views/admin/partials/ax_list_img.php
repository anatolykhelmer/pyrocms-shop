<?php 
if(@strlen(@$process_msg) > 0 ){
    echo $process_msg;
}else{
    //$loop = 0;
    foreach($post as $vx){
        //$class_show = (($loop % 2) == 0) ? '': 'even';
        $img_url = site_url('uploads/'.SITE_REF.'/shop/thumb/'.$vx->image_name);
        $img_url_base = UPLOAD_PATH.'shop/thumb/'.$vx->image_name;
        //echo $img_url_base;
        if(is_file($img_url_base) == true){
            list($width,$height)=getimagesize($img_url_base);
            $left_margin = 0; $top_margin = 0;
            if($height < 120){
                $heightsisa = 120 - $height;
                $top_margin = floor($heightsisa/2);
            }
            if($width < 150){
                $widthsisa = 150 - $width;
                $left_margin = floor($widthsisa/2);
            }
            
            $file_show = '<img class="cb_shop" style="position:absolute; margin: '.$top_margin.'px 0px 0px '.$left_margin.'px" src="'.$img_url.'" />';
        }else{
            $file_show = '';
        }
        //echo $img_url . ":images<br/>";
        
        if($vx->is_default == 1){
            $default_btn = '<div class="shop_defthumb_btn"><b>Default</b></div>';
        }else{
            $default_btn = '<div class="shop_defthumb_btn" title="'.$vx->id_shop_images.'">Set Default</div>';
        }
    ?>
    <div class="shop_thumb_img">
        <?php echo $file_show;?>
        <div class="shop_thumb_btn" title="<?php echo $vx->id_shop_images;?>">Delete</div>
        <?php echo $default_btn;?>
    </div>
    <?php
        //$loop++;
    }

}
?>
<div style="clear:both;"></div>
