<section class="section-brand">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Популярные марки</h2>
            </div>
        </div>
        <div class="row">
            <?php  foreach ($categories as $category) { ?>
            <div class="col-lg-4">
                <div class="pop_cat-item clearfix">
                    <div class="pop_cat-item_icon">
                        <a href="<?php echo $category['href']?>"><img src="<?php echo $category['thumb']?>" alt="<?php echo $category['name']?>"></a>
                    </div>
                    <div class="pop_cat-item_text">
                        <a href="<?php echo $category['href']?>"><h3><?php echo $category['name']?></h3></a>
                        <ul>
                            <?php  foreach ($category['sub_categories'] as $sub_category) { ?>
                            <li>
                                <a href="<?php echo $sub_category['sub_href']?>"><?php echo $sub_category['sub_name']?></a>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <a href="<?= $all_manuf; ?>" class="link_all">Смотреть все марки</a>
            </div>
        </div>
    </div>
</section>