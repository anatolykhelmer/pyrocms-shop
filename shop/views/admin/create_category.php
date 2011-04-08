
<h3><?php echo lang('shop.cat_create_title');?></h3>


<?php echo form_open($this->uri->uri_string(), 'class="crud" id="categories"'); ?>

<fieldset>
	<ol>
		<li class="even">
		<label for="title"><?php echo lang('shop.cat_title_label');?></label>
		<?php echo  form_input('title', $category->title); ?>
		<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
		</li>
	</ol>

	<div class="buttons float-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
</fieldset>

<?php echo form_close(); ?>