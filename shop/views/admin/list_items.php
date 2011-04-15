<?php if ($all_items->num_rows() != 0) : ?>
    <h2><?php echo lang('shop.item_list_title'); ?></h2>
    <?php echo form_open('admin/shop/delete_item'); ?>

        <table border="0" class="table-list">
            <thead>
                <tr>
                    <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                    <th><?php echo lang('shop.item_title_label'); ?></th>
                    <th width="200" class="align-center"><?php echo lang('shop.cat_actions_label'); ?></th>
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
                <?php foreach($all_items->result() as $item) : ?>
                    <tr>
                        <td><?php echo form_checkbox('action_to_all[]', $item->id); ?></td>
                        <td><?php echo $item->name; ?></td>
                        <td class="aling-center buttons buttons-small">
                            <?php echo anchor('admin/shop/edit_item/' .$item->id, lang('shop.item_edit_label'), 'class="button edit"'); ?>
                            <?php echo anchor('admin/shop/delete_item/' .$item->id, lang('shop.item_delete_label'), 'class="confirm button delete"'); ?>
                        </td>
                <?php endforeach; ?>
            </tbody>
        </table>

    <div class="buttons align-right padding-top">
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
    </div>
    
    <?php echo form_close(); ?>

<?php else: ?>
    <div class="blank-slate">
        <h2><?php echo lang('shop.item_no_items'); ?></h2>
    </div>
<?php endif; ?>
