<div class="simplecheckout-block" id="simplecheckout_cart" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $has_error ? 'data-error="true"' : '' ?>>

<?php if ($attention) { ?>
    <div class="alert alert-danger simplecheckout-warning-block"><?php echo $attention; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
    <div class="alert alert-danger simplecheckout-warning-block"><?php echo $error_warning; ?></div>
<?php } ?>


<div class="cart-body">
    <ul class="cart-view">
        <li id="cart_item_1" class="cart-view_item active">
        <h2 class="cart_name">Моя корзина</h2>
            <div class="custom_header">
                <h2 style="display: none" class="order_id">Ваш заказ № <?php echo $last_id ?></h2>
                <a href="<?php echo $change ?>" style="display: none" class="change">Изменить</a>
            </div>
            <div class=""  data-mcs-theme="dark">
        <table class="simplecheckout-cart" id="cart-list">
            <thead class="header">
                <tr>
                    <th colspan="2">Наименование</th>
                    <th>Ед. изм.</th>
                    <th>Цена ед.</th>
                    <th>Количество</th>
                    <th>Стоимость</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td>

                        <?php if ($product['thumb']) { ?>
                            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                        <?php } ?>

                    </td>
                    <td class="image_name">
                        <span>
                            <?php if (!$product['stock'] && ($config_stock_warning || !$config_stock_checkout)) { ?>
                                <span class="product-warning">***<?php echo $product['name']; ?></span>
                            <?php } else { ?>
                                 <?php echo $product['name']; ?>
                            <?php } ?>
                        </span>
                    </td>
                    <td class="model"><?php echo $product['attr']; ?>.</td>
                    <td class="js_price"><?php echo $product['price']; ?> </td>
                    <td class="quantity">
                        <div class="jq-number quantity">
                            <div class="jq-number__field">
                                <input class="form-control quantity_value" data-quantity="<?php echo $product['quantity']; ?>" data-unit="<?php echo $product['attr']; ?>." type="text" data-onchange="changeProductQuantity" <?php echo $quantity_step_as_minimum ? 'onfocus="$(this).blur()" data-minimum="' . $product['minimum'] . '"' : '' ?> name="quantity[<?php echo !empty($product['cart_id']) ? $product['cart_id'] : $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1">
                            </div>
                            <div class="jq-number__spin minus" data-onclick="decreaseProductQuantity" data-toggle="tooltip" type="submit">
                            </div>
                            <div class="jq-number__spin plus" data-onclick="increaseProductQuantity" data-toggle="tooltip" type="submit">
                            </div>
                        </div>
                        <span class="attr" style="display: none"><?php echo $product['quantity']; ?> <?php echo $product['attr']; ?>.</span>
                    </td>
                    <td class="js_total">
                        <em class="total result"><?php echo $product['total']; ?> </em>

                        <a class="cross">
                            <i class="icon icon-remove_prod remove" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach ($vouchers as $voucher_info) { ?>
                    <tr>
                        <td class="image"></td>
                        <td class="name"><?php echo $voucher_info['description']; ?></td>
                        <td class="model"></td>
                        <td class="quantity">
                            <div class="input-group btn-block" style="max-width: 200px;">
                                <input class="form-control" type="text" value="1" disabled size="1" />
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" data-onclick="removeGift" data-gift-key="<?php echo $voucher_info['key']; ?>" type="button">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td class="price"><?php echo $voucher_info['amount']; ?></td>
                        <td class="total"><?php echo $voucher_info['amount']; ?></td>
                        <td class="remove"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
            </div>
<div class="clearfix">
    <div class="cart-promo">
        <span>ПРОМОКОД</span>
        <label>
            <input class="form-control" type="text" data-onchange="reloadAll" name="coupon" value="<?php echo $coupon; ?>" /></span>
            <?php if($coupon) { ?>
            <i class="icon icon-done-2"></i>
            <?php } ?>
        </label>
    </div>
    <div class="cart-result clearfix">

        <?php if(isset($client_special) && $client_special != 0) { ?>
            <div class="client_special">
                <span>Клиентская скидка <?php echo $client_special ?>%</span>
                <strong>
                    <em id="clientSale"> - <?php echo $different ?> </em>

                </strong>
                <span class="old_price"><?php echo $origin_total ?> </span>
            </div>
        <?php } ?>

        <div class="result-wrap">
            <span id="totalSum"></span>
        </div>
        <div class="result-wrap">
            <?php foreach ($totals as $total) { ?>
            <div id="total_<?php echo $total['code']; ?>">
                <div class="result-item">
                    <span><b><?php echo $total['title']; ?>:</b></span>
                    <strong>
                        <em class="js_total_value"><?php echo $total['value']; ?> </em>
                       </strong>
                    <span class="simplecheckout-cart-total-remove">
                        <?php if ($total['code'] == 'coupon') { ?>
                        <i data-onclick="removeCoupon" title="<?php echo $button_remove; ?>" class="fa fa-times-circle"></i>
                        <?php } ?>
                        <?php if ($total['code'] == 'voucher') { ?>
                        <i data-onclick="removeVoucher" title="<?php echo $button_remove; ?>" class="fa fa-times-circle"></i>
                        <?php } ?>
                        <?php if ($total['code'] == 'reward') { ?>
                        <i data-onclick="removeReward" title="<?php echo $button_remove; ?>" class="fa fa-times-circle"></i>
                        <?php } ?>
                    </span>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>



<input type="hidden" name="remove" value="" id="simplecheckout_remove">
<div style="display:none;" id="simplecheckout_cart_total"><?php echo $cart_total ?></div>
<?php if ($display_weight) { ?>
    <div style="display:none;" id="simplecheckout_cart_weight"><?php echo $weight ?></div>
<?php } ?>
<?php if (!$display_model) { ?>
    <style>
    .simplecheckout-cart col.model,
    .simplecheckout-cart th.model,
    .simplecheckout-cart td.model {
        display: none;
    }
    </style>
<?php } ?>
</div>
