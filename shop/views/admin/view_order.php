<h2 id="page_title">
    <?php echo lang('shop.invoice_num_label').$cart->id; ?>
</h2>

<div id="customer_info">
    <p><?php echo lang('shop.customer_username_label'). ': ' .$cart->username; ?></p>
</div>


<table class="table-list" border="0">
<thead>
        <tr>
           <th width="40"><?php echo lang('shop.qty_label'); ?></th>
          <th><?php echo lang('shop.item_title_label'); ?></th>
          <th><?php echo lang('shop.item_price_label'); ?></th>
          <th><?php echo lang('shop.sub_total_label'); ?></th>
        </tr>
<thead>
<tbody>
        <?php $sum = 0; ?>

        <?php foreach ($items->result() as $item): ?>

                <?php  $sum += $item->price * $item->qty; ?>

                <tr>
                  <td><?php echo $item->qty; ?></td>
                  <td>
                    <?php echo $item->name; ?>
                         <?php  if (count($item_options)) : ?>
                              <?php foreach ($item_options[$item->id]->result() as $option) : ?>
              
                                  <p>
                                      <b><?php echo $option->name; ?>: </b> <?php echo $option->value; ?>
                                  </p>
                              <?php endforeach; ?>
                          <?php endif; ?>
                  </td>
                  <td><?php echo $item->price; ?></td>
                  <td><?php echo $sum; ?></td>
                </tr>

        <?php endforeach; ?>
</tbody>
<tfoot>
        <tr>
          <td colspan="2"> </td>
          <td class="right"><strong><?php echo lang('shop.total_label'); ?></strong></td>
          <td class="right"><?php echo $sum; ?></td>
        </tr>
</tfoot>

</table>
