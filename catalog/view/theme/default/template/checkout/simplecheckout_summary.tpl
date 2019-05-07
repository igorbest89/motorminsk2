<div class="simplecheckout-block" id="simplecheckout_summary" <?php echo $hide ? 'data-hide="true"' : '' ?>>
    <?php if ($display_header) { ?>
        <div class="checkout-heading panel-heading"><?php echo $text_summary ?></div>
    <?php } ?>

    <?php if ($display_products) { ?>
      <div class="table-responsive">
          <table class="simplecheckout-cart">
              <colgroup>
                  <col class="image">
                  <col class="name">
                  <col class="model">
                  <col class="quantity">
                  <col class="price">
                  <col class="total">
              </colgroup>
              <thead>
                  <tr>
                      <th colspan="2" class="name"><?php echo $column_name; ?></th>
                      <th class="attr_th">Ед. изм.</th>
                      <th class="price"><?php echo $column_price; ?></th>
                      <th class="total"><?php echo $column_total; ?></th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($products as $product) { ?>
                      <tr>
                          <td class="image">
                              <?php if ($product['thumb']) { ?>
                                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                              <?php } ?>
                          </td>
                          <td class="name">
                              <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                              <div class="options">
                              <?php foreach ($product['option'] as $option) { ?>
                              &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                              <?php } ?>
                              </div>
                              <?php if ($product['reward']) { ?>
                              <small><?php echo $product['reward']; ?></small>
                              <?php } ?>
                          </td>
                          <td class="attr"><span><?php echo $product['attr']; ?>.</span></td>
                          <td class="price"><span><?php echo $product['price']; ?></span></td>
                          <td class="total"><span><?php echo $product['total']; ?></span></td>
                      </tr>
                  <?php } ?>
                  <?php foreach ($vouchers as $voucher_info) { ?>
                      <tr>
                          <td class="image"></td>
                          <td class="name"><?php echo $voucher_info['description']; ?></td>
                          <td class="model"></td>
                          <td class="quantity">1</td>
                          <td class="price"><?php echo $voucher_info['amount']; ?></td>
                          <td class="total"><?php echo $voucher_info['amount']; ?></td>
                      </tr>
                  <?php } ?>
              </tbody>
          </table>
      </div>
    <?php } ?>

    <?php if (!$display_products && $display_totals_block && !empty($display_totals)) { ?>
      <div class="simplecheckout-summary-totals">
    <?php } ?>

    <?php if ($display_totals_block) { ?>

          <?php if($client_special != 0) { ?>
              <div class="client_special simplecheckout-cart-total">
                  <span><b>Клиентская скидка <?php echo $client_special ?>%</b></span>
                  <span class="simplecheckout-cart-total-value">- <?php echo $different ?>руб.</span>
                  <span class="old_price"><?php echo $origin_total ?> руб.</span>
              </div>
          <?php } ?>

      <?php foreach ($totals as $total) { ?>
        <?php if (in_array($total['code'], $display_totals)) { ?>
          <div class="simplecheckout-cart-total" id="total_<?php echo $total['code']; ?>">
            <span><b><?php echo $total['title']; ?>:</b></span>
            <span class="simplecheckout-cart-total-value"><?php echo $total['text']; ?></span>
          </div>
        <?php } ?>
      <?php } ?>
    <?php } ?>

    <?php if (!$display_products && $display_totals_block && !empty($display_totals)) { ?>
      </div>
    <?php } ?>

    <?php if ($display_comment) { ?>
      <?php if ($summary_comment) { ?>
        <table class="simplecheckout-cart simplecheckout-summary-info">
          <thead>
            <tr>
              <th class="name"><?php echo $text_summary_comment; ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $summary_comment; ?></td>
            </tr>
          </tbody>
        </table>
      <?php } ?>
    <?php } ?>
      
    <?php if ($display_address) { ?>
      <?php if ($summary_payment_address || $summary_shipping_address) { ?>
        <table class="simplecheckout-cart simplecheckout-summary-info">
          <thead>
            <tr>
              <?php if ($summary_payment_address) { ?>
              <th class="name"><?php echo $text_summary_payment_address; ?></th>
              <?php } ?>
              <?php if ($summary_shipping_address) { ?>
              <th class="name"><?php echo $text_summary_shipping_address; ?></th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php if ($summary_payment_address) { ?>
              <td><?php echo $summary_payment_address; ?></td>
              <?php } ?>

            </tr>
          </tbody>
        </table>

            <?php if ($summary_shipping_address) { ?>

                    <div class="add_info clearfix">
                        <div class="add_info-item">
                            <h2>Способ получения заказа</h2>
                            <h3><?php echo $ship_method; ?></h3>

                            <?php if ($ship_cost == 0) { ?>
                                <div class="addr">
                                    <span>Адрес склада ЗАО Чистый берег</span>
                                    <strong><?php echo $ship_stock_address; ?></strong>
                                </div>
                                <div class="date">
                                    <span>Когда: </span>
                                    <strong><?php echo $ship_stock_date; ?></strong>
                                </div>
                                <div class="time">
                                    <span>Во сколько: </span>
                                    <strong><?php echo $ship_stock_time; ?></strong>
                                </div>
                            <?php } else { ?>
                                <div class="addr">
                                    <span>Город</span>
                                    <strong><?php echo $ship_city; ?></strong>
                                </div>
                                <div class="date">
                                    <span>Почтовый индекс</span>
                                    <strong><?php echo $ship_postcode; ?></strong>
                                </div>
                                <div class="time">
                                    <span>Адрес доставки </span>
                                    <strong><?php echo $ship_delivery_address; ?></strong>
                                </div>
                            <?php } ?>

                        </div>


                        <div class="add_info-item">
                            <h2>Получатель</h2>
                            <div class="addr">
                                <span>Имя</span>
                                <strong><?php echo $firstname_custom; ?></strong>
                            </div>
                            <div class="addr">
                                <span>Фамилия</span>
                                <strong><?php echo $lastname_custom; ?></strong>
                            </div>
                            <div class="addr">
                                <span>E-mail</span>
                                <strong><?php echo $email_custom; ?></strong>
                            </div>
                            <div class="addr">
                                <span>Телефон</span>
                                <strong><?php echo $phone_custom; ?></strong>
                            </div>
                        </div>
                        <div class="add_info-item">
                            <h2>Оплата</h2>
                            <div class="addr">
                                <strong><?php echo $payment_method; ?></strong>
                            </div>
                        </div>
                    </div>



            <?php } ?>


      <?php } ?>
    <?php } ?>
</div>
<script type="text/javascript"><!--

        $(document).ready(function() {
            var request_s = $.ajax({
                url: 'index.php?route=checkout/checkout/getSession',
                dataType: 'json',
                type: "POST",
                data: {action: "getSession"}
            });
            request_s.done(function (response) {
                console.log(response)
                if (response.error == 0) {
                    if (response.shipping_method.cost == 0) {
                        $('#total_shipping').remove();
                        $('#total_shipping').remove();

                    }
                }


            })
        })
    //--></script>
