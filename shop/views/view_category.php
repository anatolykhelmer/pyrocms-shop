<h2 id="page_title">
    <?php echo $this->module_details['name']. ' - ' .$cat_name; ?>
</h2>
<table width="100%">
    <thead>
    <tr>
        <th width="120px"><?php echo lang('shop.item_image_label'); ?></th>
        <th><?php echo lang('shop.item_title_label'); ?></th>
        <th width="120px"><?php echo lang('shop.item_price_label'); ?></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($items->result() as $item) {
        echo '<tr><td><img src="' .$thumbs[$item->id]. '" >';
        echo '</td><td><a href="/shop/view_item/' .$item->id. '">' .$item->name. '</a></td><td>' .$item->price. ' &#8362;</td></tr>';
    }
    ?>
    </tbody>
</table>