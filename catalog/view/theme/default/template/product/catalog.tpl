<?php echo $header; ?>
<?php echo $search; ?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb">
                    <ul>
                        <li>
                            <a href="<?php echo $home ?>">Главная /</a>
                        </li>
                        <li>Каталог</li>
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
                <h1>Каталог запчастей</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="fast-search">
                    <input id="match-search" type="text" placeholder="Введите название запчасти"/>
                    <i class="icon icon-search-2"></i>
                    <span>Быстрый поиск по каталогу</span>
                    <ul id="append_ul">

                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="catalog">
    <div class="container">
        <div class="cat-item">
            <div class="row">
                <div class="col-lg-3">
                    <h3 class="main-cat">Двигатели</h3>
                </div>
                <?php foreach (array_chunk($marks, ceil(count($marks)/2)) as $key => $chunk_categories): ?>
                    <div class="<?php if($key == 0){ ?> col-lg-4 <?php } else { ?> col-lg-5 <?php } ?>col-md-4">
                        <ul class="cat">
                            <?php foreach ($chunk_categories as $sub_category): ?>
                                <li>
                                   <a href="<?php echo $sub_category['href']; ?>"><?php echo $sub_category['name']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php foreach ($categories as $key => $category): ?>
            <div class="cat-item">
                <div class="row">
                    <div class="col-lg-3">
                        <a href="<?php echo $category['href']; ?>"><h3 class="main-cat"><?php echo $category['name']; ?></h3></a>
                    </div>
                    <?php if($category['children']): ?>
                        <?php foreach (array_chunk($category['children'], ceil(count($category['children'])/2)) as $key => $chunk_categories): ?>
                            <div class="<?php if($key == 0){ ?> col-lg-4 <?php } else { ?> col-lg-5 <?php } ?>col-md-4">
                                <ul class="cat">
                                    <?php foreach ($chunk_categories as $sub_category): ?>
                                        <li>
                                            <a href="<?php echo $sub_category['href']; ?>"><?php echo $sub_category['name']; ?></a>
                                            <?php if($sub_category['children']): ?>
                                                <ul class="sub-cat">
                                                    <?php foreach ($sub_category['children'] as $sub_sub_category): ?>
                                                        <li>
                                                            <a href="<?php echo $sub_sub_category['href']; ?>"><?php echo $sub_sub_category['name']; ?></a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<div id="overlay" style="display: none;"></div>
<?php echo $footer; ?>