<h2 id="page_title">
    <?php echo $this->module_details['name']. ' - ' .$cat_name; ?>
</h2>

<div id="search_div">
    <?php echo form_open('/shop/view_category/'. $cat_id); ?>
        <?php   $data = array(
                  'name'        => 'search',
                  'id'          => 'search',
                  'value'       => '',
                  'maxlength'   => '100',
                  'size'        => '50',
                  'style'       => 'width:50%',
                );
            echo form_input($data); ?>
        <?php   $data = array(
                  'name'        => 'submit',
                  'id'          => 'submit',
                  'value'       => lang('shop.search_label')
                );
            echo form_submit($data); ?>
</div>

<?php if ($items->num_rows() != 0) : ?>

    <table width="100%">
        <thead>
        <tr>
            <th width="120px"><?php echo lang('shop.item_image_label'); ?></th>
            <th><?php echo lang('shop.item_title_label'); ?></th>
            <th><?php echo lang('shop.item_manufacturer_label'); ?></th>
            <th width="120px"><?php echo lang('shop.item_price_label'); ?></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($items->result() as $item) {
                $thumb_basepath = FCPATH.UPLOAD_PATH."shop/thumb/".$item->image_name;
                if(is_file($thumb_basepath)){
                    $thumb_showpath = site_url(UPLOAD_PATH."shop/thumb/".$item->image_name);
                    $height = '65%';
                }else{
                    $thumb_showpath = site_url(SHARED_ADDONPATH."modules/shop/img/no_image_small.jpg");
                    $height = '40%';
                }
            echo '<tr class="tbl_rowcenter"><td><img src="' .$thumb_showpath. '" width="70%" height="'.$height.'" >';
            echo '</td><td><a href="'.site_url("/shop/view_item/" .$item->id).'">' .$item->name. '</a></td><td>' .$item->manufacturer. '</td><td>' .$item->price. ' </td></tr>';
        }
        ?>
        </tbody>
    </table>

<?php else : ?>

    <h3>
        <?php echo lang('shop.item_no_items'); ?>
    </h3>

<?php endif; ?>
