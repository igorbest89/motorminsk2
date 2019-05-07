<div id="add_to_cart">
    <div class="popup_top">
        <h3><?php echo $product_name; ?></h3>
        <div class="price">
            <em>Цена с НДС</em>
            <strong><?php echo $price; ?></strong>
        </div>
    </div>
    <div class="popup_middle clearfix">
        <div class="product_img">
            <img src="<?php echo $thumb; ?> ">
        </div>

        <?php if ($attribute_groups) { ?>
            <table class="table table-bordered">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
              
                <tbody>
                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                </tr>
                <?php } ?>
                </tbody>
                <?php } ?>
            </table>
        <?php } ?>

    </div>
    <div class="popup_bottom clearfix">
        <span class="txt">Количество</span>
        <input id="productLimit" name="quantity" type="number" value="1" />
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
        <div id="button-cart" onclick="cartAdd(<?php echo $product_id; ?>)" class="btn-accent">
            <span>Добавить в корзину</span>
            <span class="icon-wrap">
                <i class="icon icon-cart_white"></i>
            </span>
        </div>
    </div>
</div>
<!-- <div>
    <label>Производитель:</label>
    <?php echo $manufacturer; ?>
</div> -->
