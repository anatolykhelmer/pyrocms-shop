<h3><?php echo lang('shop.item_create_title'); ?></h3>

<?php echo form_open(uri_string(), 'class="crud"'); ?>
<div class="tabs">

    <ul class="tab-menu">
        <li><a href="#item-content"><span><?php echo lang('shop.item_content_title'); ?></span></a></li>
        <li><a href="#item-options"><span><?php echo lang('shop.item_options_title'); ?></span></a></li>
    </ul>

    <div id="item-content">
    <ol>
            <li>
                <label for="title"><?php echo lang('shop.item_title_label'); ?></label>
                <?php echo form_input('title', htmlspecialchars_decode($post->title), 'maxlength="100"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>

            <li class="even">
                <label for="price"><? echo lang('shop.item_price_label'); ?></label>
                <?php echo form_input('price', $post->price, 'maxlength="10"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            
            <li>
                <label for="category"><?php echo lang('shop.item_category_label'); ?></label>
		<?php echo form_dropdown('category', $categories, @$post->category) ?>
		[ <?php echo anchor('admin/shop/create_category', lang('shop.new_category_label'), 'target="_blank"'); ?> ]
            </li>

            <li class="even">
                <label for="gallery"><?php echo lang('shop.item_gallery_label'); ?></label>
                <?php echo form_dropdown('gallery', $galleries, $post->gallery) ?>
                [ <?php echo anchor('admin/galleries/create', lang('shop.new_gallery_label'), 'target="_blank"'); ?> ]
            </li>

            <li>
                    <label for="status"><?php echo lang('shop.item_status_label'); ?></label>
                    <?php echo form_dropdown('status', array('draft' => lang('shop.item_draft_label'), 'live' => lang('shop.item_live_label')), $post->status) ?>
            </li>

            <li class="even">
                    <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => stripslashes($post->description), 'rows' => 50, 'class' => 'wysiwyg-advanced')); ?>
            </li>
    </ol>
    </div>

    <div id="item-options">
        
        <ol>
            <li>
                        <label for="option_name"><?php echo lang('shop.item_option_name_label'); ?></label>
                        <?php echo form_input('option_name'); ?>

                        <div class="option_values">
                            <ul>
                                <li>
                                    <label for="value[1]"><?php echo lang('shop.item_option_value_label'); ?> #1</label>
                                    <?php echo form_input('value[1]'); ?>
                                </li>
                                <li>
                                    <label for="value[2]"><?php echo lang('shop.item_option_value_label'); ?> #2</label>
                                    <?php echo form_input('value[2]'); ?>
                                </li>
                                <li class="add_value">
                                    [ <a href="#" id ="add_value"><?php echo lang('shop.item_option_value_add_label'); ?></a> ]
                                </li>
                            </ul>
                        </div>
            </li>
        </ol>

    </div>
</div>
<div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
</div>

<?php echo form_close(); ?>