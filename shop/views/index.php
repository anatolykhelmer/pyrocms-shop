<h2><?php echo $this->module_details['name']; ?></h2>
<h4><?php echo lang('shop.item_list_recent'); ?></h4>
<ul class="shop_ul_item_list">
    <?php 
    if(count($shop_recent) > 0){
        foreach($shop_recent as $dt) {
            $img_url = FCPATH.UPLOAD_PATH."shop/thumb/".$dt->image_name;
            clearstatcache();
            if(file_exists($img_url) ==  true and strlen($dt->image_name) > 3){
                $img_html = site_url(UPLOAD_PATH."shop/thumb/".$dt->image_name);
            }else{
                $img_html = site_url(SHARED_ADDONPATH."modules/shop/img/no_image_small.jpg");
            }
            echo '<li><a href="'.site_url("/shop/view_item/".$dt->id).'" ><b>' .$dt->name. '</b></a><br/>
                <div class="shop_thumb_info"> Category : <a href="'.site_url("/shop/view_category/".$dt->category).'" >' .$dt->cat_name. '</a></div>
                <a href="'.site_url("/shop/view_item/".$dt->id).'" ><img src="'.$img_html.'"/></a></li>';
        }
    }
    ?>
</ul>
<br style="clear:both;"/>
<h4><?php echo lang('shop.cat_list_title'); ?></h4>
<ul class="shop_ul_cat_list">
    <?php foreach($shop_categories->result() as $category) {
        echo '<li><a href="'.site_url("/shop/view_category/".$category->id).'" >' .$category->name. '</a></li>';
    }
    ?>
</ul>
