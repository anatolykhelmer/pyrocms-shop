<h2><?php echo $this->module_details['name']; ?></h2>
<ul><?php echo lang('shop.cat_list_title'); ?>
    <?php foreach($shop_categories->result() as $category) {
        echo '<li><a href="'.site_url("/shop/view_category/".$category->id).'" >' .$category->name. '</a></li>';
    }
    ?>
</ul>