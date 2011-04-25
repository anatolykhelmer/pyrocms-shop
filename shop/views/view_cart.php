<h2 id="page_title">
    <?php echo lang('shop.cart_your_cart_title'); ?>
</h2>
<?php if ($this->cart->total_items()) : ?>
    <?php echo form_open('shop/update_cart'); ?>

    <table cellpadding="6" cellspacing="0" style="width:100%" >
        <thead>
                <tr>
                   <th width="120px"><?php echo lang('shop.qty_label'); ?></th>
                  <th><?php echo lang('shop.item_title_label'); ?></th>
                  <th style="text-align:right" width="120px"><?php echo lang('shop.item_price_label'); ?></th>
                  <th style="text-align:right" width="120px"><?php echo lang('shop.sub_total_label'); ?></th>
                </tr>
        </thead>

    <?php $i = 1; ?>

    <?php foreach ($this->cart->contents() as $items): ?>

            <?php echo form_hidden('rowid[' .$i. ']', $items['rowid']); ?>

            <tr>
              <td><?php echo form_input(array('name' => 'qty['.$i.']', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); ?></td>
              <td>
                    <?php echo $items['name']; ?>

                            <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>

                                    <p>
                                            <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>

                                                    <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />

                                            <?php endforeach; ?>
                                    </p>

                            <?php endif; ?>

              </td>
              <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
              <td style="text-align:right"><?php echo $this->cart->format_number($items['subtotal']); ?></td>
            </tr>

    <?php $i++; ?>

    <?php endforeach; ?>

    <tr>
      <td colspan="2"> </td>
      <td class="right"><strong><?php echo lang('shop.total_label'); ?></strong></td>
      <td class="right"><?php echo $this->cart->format_number($this->cart->total()); ?></td>
    </tr>

    </table>
    <p><?php echo form_submit('', lang('shop.cart_update_label')); ?></p>
    <p>
        <a href="/shop/check_out"><?php echo lang('shop.checkout_label'); ?></a>
    </p>
<?php else : ?>
    <h3>
        <?php echo lang('shop.item_no_items'); ?>
    </h3>
<?php endif; ?>