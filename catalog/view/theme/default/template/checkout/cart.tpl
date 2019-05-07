<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($attention) { ?>
  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-center"><?php echo $column_image; ?></td>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td class="text-left"><?php echo $column_model; ?></td>
                <td class="text-left"><?php echo $column_quantity; ?></td>
                <td class="text-right"><?php echo $column_price; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td class="text-center"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?></td>
                <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                  <?php if (!$product['stock']) { ?>
                  <span class="text-danger">***</span>
                  <?php } ?>
                  <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br />
                  <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php } ?>
                  <?php } ?>
                  <?php if ($product['reward']) { ?>
                  <br />
                  <small><?php echo $product['reward']; ?></small>
                  <?php } ?>
                  <?php if ($product['recurring']) { ?>
                  <br />
                  <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                  <?php } ?></td>
                <td class="text-left"><?php echo $product['model']; ?></td>
                <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    <span class="input-group-btn">
                    <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-times-circle"></i></button>
                    </span></div></td>
                <td class="text-right"><?php echo $product['price']; ?></td>
                <td class="text-right"><?php echo $product['total']; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                <td></td>
                <td class="text-left"><?php echo $voucher['description']; ?></td>
                <td class="text-left"></td>
                <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $voucher['key']; ?>');"><i class="fa fa-times-circle"></i></button>
                    </span></div></td>
                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                <td class="text-right"><?php echo $voucher['amount']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <?php if ($modules) { ?>
      <h2><?php echo $text_next; ?></h2>
      <p><?php echo $text_next_choice; ?></p>
      <div class="panel-group" id="accordion">
        <?php foreach ($modules as $module) { ?>
        <?php echo $module; ?>
        <?php } ?>
      </div>
      <?php } ?>
      <br />
      <div class="row">
        <div class="col-sm-4 col-sm-offset-8">
          <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="buttons clearfix">
        <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
        <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>




<section class="cart">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="cart-wrap">
          <div class="cart-head">
            <a href="<?php echo $back ?>" class="back_link">
              <i class="icon icon-back"></i>
              <span>Назад</span>
            </a>
            <ul class="cart-nav clearfix">
              <li id="cart_step_1" class="cart-nav_item active">
                <div class="cart-nav_txt" href="#">
                  <span>1</span>
                  Корзина
                </div>
              </li>
              <li id="cart_step_2" class="cart-nav_item">
                <div class="arr-w"></div>
                <div class="cart-nav_txt" href="#">
                  <span>2</span>
                  Доставка
                </div>
              </li>
              <li id="cart_step_3" class="cart-nav_item">
                <div class="arr-w"></div>
                <div class="cart-nav_txt" href="#">
                  <span>3</span>
                  Способ оплаты
                </div>
              </li>
            </ul>
          </div>
          <div class="cart-body">
            <ul class="cart-view">
              <li id="cart_item_1" class="cart-view_item active">
                <h2>Моя корзина</h2>
                <table id="cart-list">
                  <thead>
                  <tr>
                    <th colspan="2">Наименование</th>
                    <th>Ед. изм.</th>
                    <th>Цена ед.</th>
                    <th>Количество</th>
                    <th>Стоимость</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php foreach($products as $key => $product){ ?>
                      <tr>
                      <td>
                        <a href="<?php echo $product['href']; ?>" style="all: unset; cursor: pointer"><img src="<?php echo $product['thumb'] ?>" alt="#" /></a>
                      </td>

                      <td>
                        <span><a href="<?php echo $product['href']; ?>" style="all: unset; cursor: pointer"><?php echo $product['name'] ?></a></p></span>
                      </td>

                      <td>шт.</td>
                      <td>
                        <em class="price"><?php echo $product['price'] ?></em>
                      </td>
                      <td>
                        <div class="jq-number quantity">
                          <div class="jq-number__field">
                            <input  class="quantity_value" data-cart="<?php echo $product['cart_id'] ?>" value="<?php echo $product['quantity'] ?>">
                          </div>
                          <div class="jq-number__spin minus"></div>
                          <div class="jq-number__spin plus"></div>
                        </div>
                      </td>
                      <td>
                        <em class="result"><?php echo $product['total'] ?></em>

                        <a>
                          <i class="icon icon-remove_prod" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"></i>
                        </a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <div class="clearfix">
                  <div class="cart-promo">
                    <span>ПРОМОКОД</span>
                    <label>
                      <input type="text" />
                      <i class="icon icon-done-2"></i>
                    </label>
                  </div>
                  <div class="cart-result clearfix">
                    <div class="result-wrap">
                      <span id="totalSum">264.40 руб.</span>
                    </div>
                    <div class="result-wrap">
                      <div class="result-item">
                        <span>Скидка по промокоду</span>
                        <strong>−
                          <em id="promoSale">5.59</em>

                        </strong>
                      </div>
                      <div class="result-item">
                        <span>Клиентская скидка 10%</span>
                        <strong>
                          <em id="clientSale">00.00</em>

                        </strong>
                      </div>
                      <div class="result-item">
                        <span>Итого:</span>
                        <strong>
                          <em id="resultSum"><?php echo $totals[0]['text']; ?></em>

                        </strong>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li id="cart_item_2" class="cart-view_item">
                <div class="info clearfix">
                  <h2>Выбирите способ получения заказа</h2>
                  <div class="tabs">
                    <ul class="tabs_control clearfix">
                      <li class="tabs_control_item active">
                        <a class="tabs_control_link" href="#">Самовывоз</a>
                      </li>
                      <li class="tabs_control_item">
                        <a class="tabs_control_link" href="#">Доставка</a>
                      </li>
                    </ul>
                    <ul class="tabs_list">
                      <li class="tabs_item active">
                        <div class="addr">
                          <span>Адрес склада ЗАО Чистый берег</span>
                          <strong>п/у Колядичи (напротив ул. Бабушкина, 78)</strong>
                        </div>
                        <div class="date">
                          <span>Когда: </span>
                          <strong>17.10.2018г.</strong>
                        </div>
                        <div class="time">
                          <span>Во сколько: </span>
                          <strong>с 9-00 до 18-00</strong>
                        </div>
                      </li>
                      <li class="tabs_item">
                        <h2>Адрес доставки</h2>
                        <form action="#">
                          <p>
                            <input type="radio" name="addr" checked="checked" />
                            <span>Минск 12 </span>
                            <span>На завтра 12/06/18  с 16:00 до 20:00</span>
                          </p>
                          <p>
                            <input type="radio" name="addr" />
                            <span>Республика Беларусь 25 руб.</span>
                            <span>На завтра 12/06/18  с 16:00 до 20:00</span>
                          </p>
                          <label>
                            <span>Город</span>
                            <input type="text" placeholder="Ваш город" />
                          </label>
                          <label>
                            <span>Почтовый индекс</span>
                            <input type="text" placeholder="Ваш индекс" />
                          </label>
                          <label>
                            <span>Адрес доставки</span>
                            <input type="text" placeholder="Адрес доставки" />
                          </label>
                        </form>
                      </li>
                    </ul>
                  </div>
                  <div class="ready_order">
                    <div class="order_header">
                      <h3>
                        <span>Ваш заказ №</span>
                        <em>213345</em>
                        <a href="#">Изменить</a>
                      </h3>
                    </div>
                    <div class="order_body">
                      <table>
                        <tr>
                          <td>
                            <img src="img/cart-img-1.png" alt="#" />
                          </td>
                          <td>Магнитный сепаратор шлама для систем водоснабжения SelfCleaner</td>
                          <td>
                            1 шт.
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <img src="img/cart-img-2.png" alt="#" />
                          </td>
                          <td>Труба RAUTHERM S</td>
                          <td>
                            24 м.
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <img src="img/cart-img-2.png" alt="#" />
                          </td>
                          <td>Труба RAUTHERM S</td>
                          <td>
                            24 м.
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div class="order_footer">
                      <p class="delivery_price">
                        <span>Доставка</span>
                        <strong><b>12,00</b> руб.</strong>
                      </p>
                      <p>
                        <span>Итого:</span>
                        <strong>232,37 руб.</strong>
                      </p>
                    </div>
                  </div>
                </div>
                <div id="index"></div>
                <div class="add_info">
                  <h2>Для получения заказа укажите Ваши данные</h2>
                  <form action="#">
                    <label>
                      <span>Имя</span>
                      <input type="text" placeholder="Ваше имя" />
                    </label>
                    <label>
                      <span>Фамилия</span>
                      <input type="text" placeholder="Ваша фамилия" />
                    </label>
                    <label>
                      <span>E-mail</span>
                      <input id="inputEmail" type="text" placeholder="Ваш E-mail" />
                      <em>На указанный E-mail будет отправлен счет</em>
                    </label>
                    <label>
                      <span>Телефон</span>
                      <input type="text" placeholder="+375 (29) 123-45-67" />
                    </label>
                  </form>
                </div>
              </li>
              <li id="cart_item_3" class="cart-view_item">
                <h2>Выбирите способ оплаты заказа</h2>
                <div class="info clearfix">
                  <div class="payment_method">
                    <form action="#">
                      <p>
                        <input type="radio" name="payment_method" checked="checked" />
                        <span>Наличными в момент получения заказа</span>
                      </p>
                      <p>
                        <input type="radio" name="payment_method" />
                        <span>Банковской картой при получения заказа</span>
                      </p>
                      <p>
                        <input type="radio" name="payment_method" />
                        <span>Банковской картой сейчас</span>
                      </p>
                      <p>
                        <input type="radio" name="payment_method" />
                        <span>Безналичный расчет для ИП</span>
                      </p>
                      <p>
                        <input id="radioPopup" type="radio" name="payment_method" />
                        <span>Безналичный расчет для юридических лиц</span>
                      </p>
                      <p>
                        <input type="radio" name="payment_method" />
                        <span>Оплата через ЕРИП</span>
                      </p>
                    </form>
                  </div>
                  <div class="ready_order">
                    <div class="order_header">
                      <h3>
                        <span>Ваш заказ №</span>
                        <em>213345</em>
                        <a href="#">Изменить</a>
                      </h3>
                    </div>
                    <div class="order_body">
                      <table>
                        <tr>
                          <td>
                            <img src="img/cart-img-1.png" alt="#" />
                          </td>
                          <td>Магнитный сепаратор шлама для систем водоснабжения SelfCleaner</td>
                          <td>
                            1 шт.
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <img src="img/cart-img-2.png" alt="#" />
                          </td>
                          <td>Труба RAUTHERM S</td>
                          <td>
                            24 м.
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <img src="img/cart-img-2.png" alt="#" />
                          </td>
                          <td>Труба RAUTHERM S</td>
                          <td>
                            24 м.
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div class="order_footer">
                      <p class="delivery_price">
                        <span>Доставка</span>
                        <strong>12,00 руб.</strong>
                      </p>
                      <p>
                        <span>Итого:</span>
                        <strong>232,37 руб.</strong>
                      </p>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="cart-footer">
            <div class="btn-wrap clearfix">
              <a href="#" class="btn" id="prev-step">
                <i class="icon icon-cart-back"></i>
                <span>Назад в магазин</span>
              </a>
              <a href="#" class="btn-accent" id="next-step">
                <span>Доставка</span>
                <i class="icon icon-cart-next"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="popup" id="popup_data">
  <div class="close_popup">
    <i class="icon icon-close"></i>
  </div>
  <div class="popup_header">
    <h3>Данные организации</h3>
    <p>Для оформления документов</p>
  </div>
  <div class="popup_body">
    <form action="#">
      <label>
        <span>Название организации</span>
        <input type="text" />
      </label>
      <label>
        <span>УНН</span>
        <input type="text" />
      </label>
    </form>
  </div>
  <div class="popup_footer clearfix">
    <button class="btn cancel_btn">Отмена</button>
    <button class="btn btn-accent">Подтвердить</button>
  </div>
</div>
<div id="overlay"></div>

<script type="text/javascript"><!--

  $('.minus').on('click',function () {
    var input = $(this).parent().find('.quantity_value');
    var value = input.val();
    var cart_id = input.attr('data-cart');
    value = parseInt(value);
    if(value > 1){
      input.val(value - 1)
      var request = $.ajax({
        url: 'index.php?route=checkout/cart/editAjax',
        type: 'post',
        data:{ cart_id: cart_id , quantity: value - 1},
        dataType: 'json'
      });
      request.done(function (response) {
        input.closest('tr').find('.result').text(response.total);
        $('#resultSum').text(response.all_total);
      })
    }
  })

  $('.plus').on('click',function () {
    var input = $(this).parent().find('.quantity_value');
    var value = input.val();
    var cart_id = input.attr('data-cart');
    value = parseInt(value);

      input.val(value + 1)
      var request = $.ajax({
        url: 'index.php?route=checkout/cart/editAjax',
        type: 'post',
        data: { cart_id: cart_id, quantity: value + 1} ,
        dataType: 'json'
      });
    request.done(function (response) {
      input.closest('tr').find('.result').text(response.total);
      $('#resultSum').text(response.all_total);
    })
  })






  $('#button-cart').on('click', function() {
    $.ajax({
      url: 'index.php?route=checkout/cart/add',
      type: 'post',
      data: $('#product input[type=\'text\'], #product input[type=\'number\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
      dataType: 'json',
      beforeSend: function() {
        $('#button-cart').button('loading');
      },
      complete: function() {
        $('#button-cart').button('reset');
      },
      success: function(json) {
        $('.alert, .text-danger').remove();
        $('.form-group').removeClass('has-error');

        if (json['error']) {
          if (json['error']['option']) {
            for (i in json['error']['option']) {
              var element = $('#input-option' + i.replace('_', '-'));

              if (element.parent().hasClass('input-group')) {
                element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
              } else {
                element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
              }
            }
          }

          if (json['error']['recurring']) {
            $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
          }

          // Highlight any found errors
          $('.text-danger').parent().addClass('has-error');
        }

        if (json['success']) {
          $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');

          $('html, body').animate({ scrollTop: 0 }, 'slow');

          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  //--></script>
