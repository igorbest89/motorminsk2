<div class="custom_cat">
    <div class="col-lg-4 col-md-4 col-sm-6 ">
        <div class="all_category">
            <a href="<?php echo $catalog ?>">Каталог</a>
        </div>
        <ul id="mainPgCat" class="category_menu custom_layer_cart">
            <?php foreach ($categories as $key => $category) { ?>
            <li class="li_custom">
                <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
                <?php if ($category['children']) { ?>
                <i class="icon icon-arrow"></i>
                <ul class="category_menu-sub_menu">
                    <?php foreach ($category['children'] as $child) { ?>
                    <li>
                        <a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
            <!--<li class="last-item li_custom">
                <a>Показать все категории</a>
                <i class="icon icon-expand"></i>
            </li>-->
        </ul>
    </div>
</div>