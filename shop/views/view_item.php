<h2 id="page_title"><?php echo $this->module_details['name']; ?></h2>
  <?php //echo form_open('/shop/add_to_cart/' .$item->id); ?>

<h2 class="item_detail_name"><?php echo $item->name; ?></h2>

<!--
<div id="add_to_cart">
  
        <input type="submit" id="add_to_cart_button" value="<?php echo lang('shop.cart_add_to_cart'); ?>" >

</div>
-->
    <?php //echo form_close(); ?>
    
    
<div id="container_tab">  
        <ul class="tab_menu">  
            <li id="first" class="active">Product Description</li>  
            <li id="prod_images">Images</li>  
            <?php if(@$payinfo_live == 1){ ?>
            <li id="prod_pinfo">Payment Info</li>  
            <?php } ?>
        </ul>  
        <span class="clear"></span>  
        <div class="tab_content">  
            <div class="content first">  
                <h4>Product Decription</h4>
                <p class="tcleft"> - <?php echo lang('shop.item_price_label'); ?></p> 
                <p class="tcright">: <?php echo $item->price; ?></p> 
                <p class="tcleft"> - <?php echo lang('shop.item_manufacturer_label'); ?></p> 
                <p class="tcright">: <?php echo $item->manufacturer; ?></p> 
                <p class="tcleft"> - <?php echo lang('shop.item_category_label'); ?></p> 
                <p class="tcright">: <?php echo $item->cat_name; ?></p> 
                <p class="tcleft"> - <?php echo lang('shop.item_description_title'); ?></p> 
                <span class="clear"></span>  
                <p class="tcfull"><?php echo $item->description; ?></p> 
                
            </div>  
            <div class="content notfirst prod_images">  
                <h4>Images</h4>  
                <div class="imgthumb">
                    <?php foreach($img as $vi): ?>
                         <img src="<?php echo site_url(UPLOAD_PATH.'shop/thumb/' . $vi->image_name); ?>"/>
                    <?php endforeach; ?>
                </div>
                <div style="display:none;">
<?php foreach($img as $vi): ?>
         <a href="<?php echo site_url(UPLOAD_PATH.'shop/' . $vi->image_name); ?>" rel="group1"><?php echo $vi->image_name; ?></a>
<?php endforeach; ?>
                </div>
            </div>  
            <?php if(@$payinfo_live == 1){ ?>
            <div class="content notfirst prod_pinfo">  
                <h4>Payment Info</h4>  
                <p class="tcfull"><?php echo @$payinfo_content; ?></p>
                
            </div>  
            <?php } ?>
        </div>  
    </div>  
