<ul class="menu2">
<?php if ($categories->num_rows() > 0) : ?>
    <?php foreach ($categories->result() as $category) : ?>
    <li class="mitem2"><a href="/shop/view_category/<?php echo $category->id; ?>" ><?php echo $category->name; ?></a></li>
   
    <?php endforeach; ?>
<?php endif; ?>
</ul>