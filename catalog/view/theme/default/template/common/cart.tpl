<div class="cart">
  <a>
    <i class="icon icon-cart" id="modal-cart-link"></i>
    <span class="cart-count"><?php echo $count ?></span>
  </a>
  <div id="modal-cart" class="modal-cart">
    <div class="modal-cart_head">
      <span>В корзине</span>
      <span><?php echo $count ?> товаров</span>
    </div>
    <div class="test">


    <div class="modal-cart_body custom_layer_cart">

      <?php foreach ($products as $product) { ?>
      <div class="modal-cart_item clearfix">
        <div class="cart-item_img">
          <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
        </div>
        <div class="cart-item_descr">
          <h3><?php echo $product['name']; ?></h3>
          <span class="price"><?php echo $product['quantity']; ?> x <?php echo $product['price']; ?> </span>
        </div>
        <a>
          <i class="icon icon-delete" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"></i>
        </a>
      </div>
      <?php } ?>
    </div>
    <div class="modal-cart_footer">
      <div class="modal-cart_result">
        <span>Итого</span>
        <strong><?php echo $summa ?> BYN</strong>
      </div>
      <div class="modal-cart_btn">
        <a class="bt">Продолжить покупки</a>
        <a href="<?php echo $go_to_cart ?>" id="checkout_btn" class="bt-2">Оформить заказ</a>
      </div>
    </div>
    </div>
  </div>
</div>

<script>
  $('#checkout_btn').click(function () {
      if(<?php echo $count ?>)
    location.href = "<?php echo $go_to_cart ?>";
  })

</script>