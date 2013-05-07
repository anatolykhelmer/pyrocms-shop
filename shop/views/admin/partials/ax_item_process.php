 <?php echo @$process_msg; ?>
 <ol>
            <li>
                <label for="title"><?php echo lang('shop.item_title_label'); ?></label>
                <?php echo form_input('title', @$post->title, 'maxlength="100"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>

            <li class="even">
                <label for="price"><? echo lang('shop.item_price_label'); ?></label>
                <?php echo form_input('price', @$post->price, 'maxlength="10"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>

            <li>
                <label for="manufacturer"><? echo lang('shop.item_manufacturer_label'); ?></label>
                <?php echo form_input('manufacturer', @$post->manufacturer, 'maxlength="100"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            
            <li class="even">
                <label for="category"><?php echo lang('shop.item_category_label'); ?></label>
		<?php echo form_dropdown('category', $categories, @$post->category) ?>
		[ <?php echo anchor('admin/shop/create_category', lang('shop.new_category_label'), 'target="_blank"'); ?> ]
            </li>
            
            
            <!--<li>
                <label for="gallery"><?php //echo lang('shop.item_gallery_label'); ?></label>
                <?php //echo form_dropdown('gallery', $galleries, $post->gallery) ?>
                [ <?php //echo anchor('admin/galleries/create', lang('shop.new_gallery_label'), 'target="_blank"'); ?> ]
            </li>-->

            <li>
                    <label for="status"><?php echo lang('shop.item_status_label'); ?></label>
                    <?php echo form_dropdown('status', array('draft' => lang('shop.item_draft_label'), 'live' => lang('shop.item_live_label')), @$post->status) ?>
            </li>

            <li class="even">
                    <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => stripslashes(@$post->description), 'rows' => 10, 'class' => 'wysiwyg-unique')); ?>
            </li>
            
            <li>
                <label for="datepost"><? echo lang('shop.item_date_label'); ?></label>
                <?php 
                if(strlen(@trim(@$process_msg)) > 0){
                    $dtDate = @$post->datepost;
                    $dtHour = @$post->hourpost;
                    $dtMinute = @$post->minutepost;
                }else{
                    if(isset($post->postdate) and strlen($post->postdate) > 8){
                        $datesplit = @explode(' ', $post->postdate);
                        $timesplit = @explode(':', $datesplit[1]);
                        
                        $dtDate = @$datesplit[0];
                        $dtHour = @$timesplit[0];
                        $dtMinute = @$timesplit[1];
                    }else{
                        $dtDate = '';
                        $dtHour = '00';
                        $dtMinute = '00';
                    }
                }
                
                
                $dtpicker = array(
                      'name'        => 'datepost',
                      'id'          => 'datepost',
                      'value'       => $dtDate,
                      'maxlength'   => '18',
                      'size'   => '20',
                      'style'   => 'width:110px;'
                    );
                echo form_input($dtpicker); ?>
                &nbsp;&nbsp;&nbsp;Hour :
                <?php 
                $dthrpicker = array(
                      'name'        => 'hourpost',
                      'id'          => 'hourpost',
                      'value'       => $dtHour,
                      'maxlength'   => '2',
                      'size'   => '3',
                      'style'   => 'width:30px;'
                    );
                echo form_input($dthrpicker); ?>
                &nbsp;Minute :
                <?php 
                $dttmpicker = array(
                      'name'        => 'minutepost',
                      'id'          => 'minutepost',
                      'value'       => $dtMinute,
                      'maxlength'   => '3',
                      'size'   => '2',
                      'style'   => 'width:30px;'
                    );
                echo form_input($dttmpicker); ?>
                
            </li>
    </ol>
    	<input name="item_id" type="hidden" value="<?php echo @$item_id; ?>"/>
        <input name="tab" type="hidden" value="<?php echo @$tab; ?>"/>
        <input name="tab_act" type="hidden" value="<?php echo @$tab_act; ?>"/>
<br style="clear:both;"/>
