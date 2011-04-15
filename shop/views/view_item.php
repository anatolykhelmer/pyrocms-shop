<h2 id="page_title"><?php echo $this->module_details['name']; ?></h2>
<div id="item_photos">
    
    <?php foreach($item_images as $item_image): ?>
    <a href="<?php echo site_url() . 'uploads/files/' .$gallery->file_id; ?>" >
        <img src="<?php echo site_url() . 'files/thumb/' . $item_image->file_id; ?>" alt="<?php echo $item->name; ?>" >
    </a>
    <?php endforeach; ?>
    
    <br clear="all">
    <a href="/galleries/<?php echo $gallery->slug; ?>" >
        <?php echo lang('shop.item_view_gallery_title'); ?>
    </a>
</div>

<h2><?php echo $item->name; ?></h2>

<div id="item_price">
    <h3><?php echo lang('shop.item_price_label'); ?></h3>
    <?php echo $item->price; ?>
</div>

<div id="item_description">
    <h3><?php echo lang('shop.item_description_title'); ?></h3>
    <?php echo $item->description; ?>
</div>

<div id="add_to_cart">
    <?php echo form_open('/shop/add_to_cart/' .$item->id); ?>
        <input type="submit" id="add_to_cart_button" value="<?php echo lang('shop.cart_add_to_cart'); ?>" >
    <?php echo form_close(); ?>
</div>