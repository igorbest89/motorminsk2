<div class="simplecheckout-block" id="simplecheckout_shipping" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
    <?php if ($display_header) { ?>
        <div class="checkout-heading panel-heading">
            <h2>
                <?php echo $text_checkout_shipping_method ?>
            </h2>

        </div>
    <?php } ?>
    <div class="alert alert-danger simplecheckout-warning-block" <?php echo $display_error && $has_error_shipping ? '' : 'style="display:none"' ?>><?php echo $error_shipping ?></div>
    <div class="simplecheckout-block-content">
        <?php if (!empty($shipping_methods)) { ?>
            <?php if ($display_type == 2 ) { ?>
                <?php $current_method = false; ?>
                <select data-onchange="reloadAll" name="shipping_method">
                    <?php foreach ($shipping_methods as $shipping_method) { ?>
                        <?php if (!empty($shipping_method['title'])) { ?>
                        <optgroup label="<?php echo $shipping_method['title']; ?>">
                        <?php } ?>
                        <?php if (empty($shipping_method['error'])) { ?>
                            <?php foreach ($shipping_method['quote'] as $quote) { ?>
                                <option value="<?php echo $quote['code']; ?>" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($quote['dummy']) ? 'data-dummy="true"' : '' ?> <?php if ($quote['code'] == $code) { ?>selected="selected"<?php } ?>><?php echo $quote['title']; ?><?php echo !empty($quote['text']) && !$hide_cost ? ' - '.$quote['text'] : ''; ?></option>
                                <?php if ($quote['code'] == $code) { $current_method = $quote; } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="<?php echo $shipping_method['code']; ?>" disabled="disabled"><?php echo $shipping_method['error']; ?></option>
                        <?php } ?>
                        <?php if (!empty($shipping_method['title'])) { ?>
                        </optgroup>
                        <?php } ?>
                    <?php } ?>
                </select>
                <?php if ($current_method) { ?>
                    <?php if (!empty($current_method['description'])) { ?>
                        <div class="simplecheckout-methods-description"><?php echo $current_method['description']; ?></div>
                    <?php } ?>
                    <?php if (!empty($rows)) { ?>
                        <?php foreach ($rows as $row) { ?>
                          <?php echo $row ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>

                <?php foreach ($shipping_methods as $shipping_method) { ?>
                    <?php if (!empty($shipping_method['title'])) { ?>
                    <p><b><?php echo $shipping_method['title']; ?></b></p>
                    <?php } ?>
                    <?php if (!empty($shipping_method['warning'])) { ?>
                        <div class="simplecheckout-error-text"><?php echo $shipping_method['warning']; ?></div>
                    <?php } ?>
                    <?php if (empty($shipping_method['error'])) { ?>
                        <?php foreach ($shipping_method['quote'] as $quote) { ?>

                            <div class="radio radio-top">
                                <label for="<?php echo $quote['code']; ?>">
                                    <input type="radio" data-onchange="reloadAll" name="shipping_method" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($quote['dummy']) ? 'data-dummy="true"' : '' ?> value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" <?php if ($quote['code'] == $code) { ?>checked="checked"<?php } ?> />
                                    <?php if (!empty($quote['img'])) { ?>
                                        <img src="<?php echo $quote['img']; ?>" width="60" height="32" border="0" style="display:block;margin:3px;">
                                    <?php } ?>
                                    <span>
                                        <?php echo !empty($quote['title']) ? $quote['title'] : ''; ?><?php echo !empty($quote['text']) && !$hide_cost ? ' - ' . $quote['text'] : ''; ?>
                                    </span>

                                </label>
                            </div>
                            <?php if (!empty($quote['description']) && (!$display_for_selected || ($display_for_selected && $quote['code'] == $code))) { ?>
                                <div class="form-group">
                                    <label for="<?php echo $quote['code']; ?>"><?php echo $quote['description']; ?></label>
                                </div>
                            <?php } ?>
                            <?php if ($quote['code'] == $code && !empty($rows)) { ?>
                                    <?php foreach ($rows as $row) { ?>
                                      <?php echo $row ?>
                                    <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="simplecheckout-error-text"><?php echo $shipping_method['error']; ?></div>
                    <?php } ?>
                <?php } ?>

            <?php } ?>
            <input type="hidden" name="shipping_method_current" value="<?php echo $code ?>" />
            <input type="hidden" name="shipping_method_checked" value="<?php echo $checked_code ?>" />
        <?php } ?>
        <?php if (empty($shipping_methods) && $address_empty && $display_address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $text_shipping_address; ?></div>
        <?php } ?>
        <?php if (empty($shipping_methods) && !$address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $error_no_shipping; ?></div>
        <?php } ?>
    </div>
</div>


<script type="text/javascript"><!--

  $(document).ready(function() {

      $('#shipping_address_field28').after('<em class="simple-label-email-text">На указанный E-mail будет отправлен счет</em>');




      $('.row-shipping_field30 .radio input:checked').parent().addClass('active');
      $(document).delegate('.row-shipping_field30 .radio input', 'change', function(){

          $('.row-shipping_field30 .radio input').parent().removeClass('active');
          $(this).parent().addClass('active');
      });

      var request_s = $.ajax({
          url: 'index.php?route=checkout/checkout/getSession',
          dataType: 'json',
          type: "POST",
          data: { action: "getSession" }
      });
      request_s.done(function (response) {
            console.log(response)
          if(response.error == 0){
              if(response.shipping_method.cost == 0) {
                  $('#total_shipping').remove();
                  $('#total_shipping').remove();

              }
          }


      })

        var request = $.ajax({
            url: 'index.php?route=checkout/checkout/getFlatCosts',
            dataType: 'json',
            type: "POST",
            data: { action: "getSession" }
        });
        request.done(function (response) {
            var cost_minsk = response.shipping_flat.cost_minsk;
            var cost2 = response.shipping_flat.cost2;




            if(response.flat_choose == 'minsk'){
                $('input[value="minsk"]').attr('checked', 'checked');
                $('input[value="belarus"]').removeAttr('checked');
            }else{
                $('input[value="belarus"]').attr('checked', 'checked');
                $('input[value="minsk"]').removeAttr('checked');
            }

            $('input[value="minsk"]').after('<span class="simple-label-text">  Минск '+cost_minsk+' руб </span>')
            $('input[value="belarus"]').after(' <span class="simple-label-text"> Республика Беларусь '+cost2+' руб </span>')

        });

      $('input[value="belarus"]').on('change',function () {
          var val = $(this).val();
          var request = $.ajax({
              url: 'index.php?route=checkout/checkout/saveSession',
              dataType: 'json',
              type: "POST",
              data: { val: val }
          });
          request.done(function (response) {
                console.log(response.shipping_method)

          });

      });


      $('input[value="minsk"]').on('change',function () {
          var val = $(this).val();
          var request = $.ajax({
              url: 'index.php?route=checkout/checkout/saveSession',
              dataType: 'json',
              type: "POST",
              data: { val: val }
          });
          request.done(function (response) {
              console.log(response)

          });

      });


       $('.attr').show();
       $('.simplecheckout-right-column .js_price').hide();
       $('.simplecheckout-right-column .js_total').hide();
       $('.simplecheckout-right-column .model').hide();
       $('.simplecheckout-right-column #total_sub_total').hide();
       $('.simplecheckout-right-column .change').show();
       $('.simplecheckout-right-column .cart_name').hide();
       $('.simplecheckout-right-column .order_id').show();




 });


    //--></script>

