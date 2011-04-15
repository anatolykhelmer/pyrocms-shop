<?php if ($orders->num_rows() != 0) : ?>

<h3><?php echo lang('shop.orders_list_title'); ?></h3>

	<?php echo form_open('admin/shop/cancel_order'); ?>

	<table border="0" class="table-list">
		<thead>
		<tr>
			<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
			<th><?php echo lang('shop.invoice_num_label'); ?></th>
			<th width="200" class="align-center"><span><?php echo lang('shop.cat_actions_label'); ?></span></th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($orders->result() as $order): ?>
			<tr>
				<td><?php echo form_checkbox('action_to[]', $order->id); ?></td>
				<td><a href="/admin/shop/view_order/<?php echo $order->id; ?>"><?php echo $order->id; ?></a></td>
				<td class="align-center buttons buttons-small">
					<?php echo anchor('admin/shop/cancel_order/' . $order->id, lang('shop.cat_edit_label'), 'class="button edit"'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="buttons align-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
	</div>

	<?php echo form_close(); ?>

<?php else: ?>
	<div class="blank-slate">
		<h2><?php echo lang('shop.cat_no_categories'); ?></h2>
	</div>
<?php endif; ?>