<?php echo $header; ?>
<?php echo $search; ?>


<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="breadcrumb">
          <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php if($breadcrumb != end($breadcrumbs)) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?> /</a></li>
            <?php } else { ?>
            <li><?php echo $breadcrumb['text']; ?></li>
            <?php  } ?>
            <?php  } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="top-title">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <a href="<?php echo $back ?>" class="back_link">
          <i class="icon icon-back"></i>
          <span>Назад</span>
        </a>
        <h1  class="product__title" id="xml_search_product_name"><?php echo $heading_title; ?></h1>
        <div id="xml_search_product_id" hidden="true" ><?php echo $product_id; ?></div>
        <div id="xml_search_product_alias" hidden="true" ><?php echo $url_alias; ?></div>
      </div>
    </div>
  </div>
</section>

<section class="product">
  <div class="container">
    <div class="row">
      <div class="mobile-txt title"><?php echo $heading_title; ?></div>
      <div class="col-lg-6 col-md-5 col-sm-6">
        <div class="product_img">
          <div class="fotorama" id="fotorama"
               data-allowfullscreen="true"
               data-width="100%"
               data-stopautoplayontouch="true"
               data-nav="thumbs"
               data-thumbmargin="13"
               data-thumbwidth="100px"
               data-thumbheight="62px"
               data-arrows="always">
            <a href="<?php echo $thumb; ?>"><img src="<?php echo $thumb; ?>" alt="#" /></a>
            <?php foreach ($images as $image) { ?>
            <a href="<?php echo $image['thumb']; ?>"><img src="<?php echo $image['thumb']; ?>" alt="#" /></a>
            <?php } ?>
          </div>
          <div class="slider-thumb" data-thumbs-list="">
            <button type="button" data-switch-slide="0" class="slider-thumb__item" style="background-image: url(image/catalog/product.png);">0</button>

            <button type="button" data-switch-slide="1" class="slider-thumb__item" style="background-image: url(image/catalog/product.png);">1</button>

            <button type="button" data-switch-slide="2" class="slider-thumb__item " style="background-image: url(image/catalog/product.png);">2</button>

            <button type="button" data-switch-slide="3" class="slider-thumb__item " style="background-image: url(image/catalog/product.png);">3</button>

            <button type="button" data-switch-slide="4" class="slider-thumb__item " style="background-image: url(image/catalog/product.png);">4</button>

            <button type="button" data-switch-slide="5" class="slider-thumb__item" style="background-image: url(image/catalog/product.png);">5</button>

            <button type="button" data-switch-slide="5" class="slider-thumb__item" style="background-image: url(image/catalog/product.png);">6</button>

            <button type="button" data-switch-slide="5" class="slider-thumb__item  active" style="background-image: url(image/catalog/product.png);">7</button>

            <button type="button" data-switch-slide="6" class="slider-thumb__item" style="background-image: url(image/catalog/product.png);">8</button>

            <button type="button" data-switch-fullscreen="" class="slider-thumb__item slider-thumb__item--more" style="background-image: url(image/catalog/product.png);">Еще 3 фото </button>
          </div>
          <div class="tabs">
            <ul class="tabs_control clearfix">
              <li class="tabs_control_item active">
                <a class="tabs_control_link" href="#">Описание</a>
              </li>
              <li class="tabs_control_item">
                <a class="tabs_control_link" href="#">Комплектность</a>
              </li>
            </ul>
            <ul class="tabs_list">
              <li class="tabs_item active">
                <table>
                  <tbody>
                    <?php if(isset($attributes)): ?>
                      <?php foreach($attributes['attribute'] as $attribute): ?>
                        <tr>
                           <td><?= $attribute['name'] ?></td>
                           <td><?= $attribute['text'] ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </li>
              <li class="tabs_item">
                <table>
                  <tbody>
                  <tr>
                    <td>Впускной коллектор бензиновый</td>
                    <td>1 шт.</td>
                  </tr>
                  <tr>
                    <td>Катушка зажигания бензиновая</td>
                    <td>4 шт.</td>
                  </tr>
                  <tr>
                    <td>ГБЦ бензиновая</td>
                    <td>1 шт.</td>
                  </tr>
                  <tr>
                    <td>Форсунка бензиновая</td>
                    <td>4 шт.</td>
                  </tr>
                  </tbody>
                </table>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="product_descr" id="product">
          <div class="product_descr-top clearfix">
            <div class="price">
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <div style="display: none">
                <input name="quantity" type="number" value="<?php echo $minimum; ?>" />
              </div>

              <strong><?php echo $price; ?></strong>
              <em>Цена  за комплект</em>
              <i class="icon icon-info"></i>
              <div class="price-2">
                <strong>205,00 руб</strong>
                <em>Цена в разобранном  виде</em>
              </div>
            </div>
              <button id="button-cart" <?php if($price == false ){ ?>  disabled="disabled" <?php } ?> class="btn-accent">
              <i class="icon icon-cart_white"></i>
              <?php if($price != false ){ ?>
              <span>Добавить в корзину</span>
              <?php } else { ?>
              <span>Нельзя добавить</span>
              <?php }  ?>
              </button>
          </div>
          <table>
            <tbody>
            <tr>
              <td>Тип топлива</td>
              <td>Дизель</td>
            </tr>
            <tr>
              <td>Объем двигателя</td>
              <td>1 900 см.</td>
            </tr>
            <tr>
              <td>Тип двигателя</td>
              <td>ABT</td>
            </tr>
            <tr>
              <td>КПП</td>
              <td>Механическая</td>
            </tr>
            <tr>
              <td>Привод</td>
              <td>Передний</td>
            </tr>
            <tr>
              <td>Складской артикул </td>
              <td>51P46AQ01</td>
            </tr>
            </tbody>
          </table>
          <div class="info-wrap">
            <i class="icon icon-info-2"></i>
            <p>Фото не актуально. Сняты следующие запчасти: корпус топливного фильтра, механизм натяжения ремня (цепи) (диз), насос вакуумный</p>
            <i class="icon icon-info_close"></i>
          </div>
          <div class="info-wrap aditional">
            <div class="head">
              <strong>205,00 руб</strong>
              <span>Цена в разобранном  виде</span>
              <i class="icon icon-info_close"></i>
            </div>
            <div class="descr">
              <h4>В стоимость комплекта включены:</h4>
              <table>
                <tbody>
                <tr>
                  <td>Впускной коллектор бензиновый</td>
                  <td>1 шт.</td>
                </tr>
                <tr>
                  <td>Катушка зажигания бензиновая</td>
                  <td>4 шт.</td>
                </tr>
                <tr>
                  <td>ГБЦ бензиновая</td>
                  <td>1 шт.</td>
                </tr>
                <tr>
                  <td>Форсунка бензиновая</td>
                  <td>4 шт.</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="product-recomended">
  <?php if ($products) { ?>
    <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="recomended-wrap">
          <h3>Похожие товары / Аналоги</h3>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="slider_recomend">
        <?php foreach ($products as $product) { ?>
        <div class="listing-item clearfix">
          <div class="listing-item_img">
            <div class="img" style="background-image: url(<?php echo $product['thumb']; ?>);"></div>
          </div>
          <div class="listing-item_descr">
            <a href="<?php echo $product['href']; ?>"><h3><?php echo $product['name']; ?></h3></a>
            <div>
              <em>Тип ДВС</em><span>AR7</span>
            </div>
            <div>
              <em>Топливо</em><span>Дизель</span>
            </div>
            <div class="price">
              <em>Цена с НДС</em><strong><?php echo $product['price']; ?></strong>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      </div>
    </div>
  </div>
  <?php } ?>
</section>
<script type="text/javascript"><!--

  $('.jq-number__spin minus').on('click',function () {
    alert(111)
    $('#productLimit').val()
  })

  $('.jq-number__spin plus').on('click',function () {
    $('#productLimit').val()
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
          if (json['error']['quantity']) {
            $('#button-cart').after('<p class="text-danger">' + json['error']['quantity'] + '</p>');
            setTimeout(function () {
              $('.text-danger').remove();
            },2000)

          }
          // Highlight any found errors
          $('.text-danger').parent().addClass('has-error');
        }

        if (json['success']) {
          $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          $('.cart-count').html(json['total']);

          $('html, body').animate({ scrollTop: 0 }, 'slow');

          $('#cart > ul').load('index.php?route=common/cart/info ul li');

          $('#modal-cart').load('index.php?route=common/cart/info .test', function () {

            $(".custom_layer_cart").mCustomScrollbar({
              theme: "minimal-dark"
            });

            $('#modal-cart .bt').on('click', function(e){
              e.preventDefault();

              $('#modal-cart').slideToggle(200);
            });
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
  //--></script>
<div id="overlay" style="display: none;"></div>
<?php echo $footer; ?>