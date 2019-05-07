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
                <h1 id="xml_search_category_name"><?php echo $heading_title; ?></h1>
                <div id="xml_search_category_alias" hidden="true" ><?php echo $url_alias; ?></div>
            </div>
        </div>
    </div>
</section>

<section class="listing">
    <div class="container">
        <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-5">
                <div class="all_category">
                    <a href="<?php echo $catalog; ?>">Все категории</a>
                    <i class="icon icon-sandwich"></i>
                </div>
                <div id="allCat" class="menu-wrapper">
                    <div class="first">
                        <input id="match-search" type="text" placeholder="Поиск по категориям"/>
                        <i class="icon icon-search-2"></i>
                    </div>
                    <div class="category_menu">
                        <ul class="custom_layer_cart">
                            <li id="not">
                                <a href="#">Совпадений не найдено!</a>
                            </li>
                            <?php foreach($parent_categories as $category){ ?>
                            <li class="li_custom">
                                <a href="<?php echo $category['href'] ?>"><?php echo $category['name'] ?></a>
                                <?php if(isset($category['sub_categories'])){ ?>
                                <i class="icon icon-arrow"></i>
                                <?php  echo recursive($category['sub_categories']) ?>
                                <?php } ?>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="last">
                        <a href="<?php echo $catalog ?>">Смотреть весь каталог</a>
                    </div>
                </div>


                <?php
                function recursive ($categories){
                     $tree = '<ul class="category_menu-sub_menu">';
                foreach($categories as $category){
                $tree .= '
                <li>';
                    $tree .= '<a href="'.$category['href'] .'">'. $category['name'] .'</a>';
                    if(isset($category['sub_categories'])){
                    $tree .= '<i class="icon icon-arrow"></i>';
                    $tree .= recursive($category['sub_categories']);
                    }
                    $tree .= '
                </li>
                ';
                }
                $tree .= '</ul>';
                return $tree;
                }
                ?>


                <!--<?php if(isset($categories)){ ?>
                <ul class="category_menu">
                    <?php foreach($categories as $category){ ?>
                    <li>
                        <a href="<?php echo $category['href'] ?>"><?php echo $category['name'] ?></a>
                        <?php if(isset($category['sub_categories'])){ ?>
                        <i class="icon icon-arrow"></i>
                        <?php  echo recursive($category['sub_categories']) ?>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>-->

                <h4 class="filter-title" style="cursor: pointer">Фильтр</h4>
                <button type="button" id="reset_filter">Сбросить фильтр</button>
                <div class="filter-wrap">
                    <form class="clearfix" action="<?php echo $siteBase; ?>" method="post" id="form__filters" autocomplete="off">
                        <input id="custom_category" type="hidden" value="<?= $category_custom ?>">
                        <div class="filter-item">
                            <span>Авто</span>
                            <div class="fastSearch__block fastSearch__mark jq-selectbox jqselect">
                                <select name="filter[]" id="marks" class="data-select_id">
                                    <option value="" class="default_custom">Марка</option>
                                    <?php foreach ($marks as $mark): ?>
                                    <option value="<?php echo $mark['keyword'] ?>"
                                    <?php if(isset($filter_marks) && ($filter_marks['data']['filter_id'] == $mark['filter_id'])): ?> selected <?php endif; ?>
                                    id="<?php echo $mark['filter_id'] ?>"><?php echo $mark['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="fastSearch__block fastSearch__model jq-selectbox jqselect">
                                <select name="filter[]" id="models" class="data-select_id">
                                    <?php if(isset($filter_models)): ?>
                                        <?php foreach ($filter_models['list'] as $model_item): ?>
                                            <option value="<?= $model_item['keyword'] ?>" class="custom_filter"
                                    <?php if($model_item['filter_id'] == $filter_models['data']['filter_id']): ?> selected <?php endif; ?>
                                                    data-filter_id="<?= $model_item['filter_id'] ?>"
                                                    id="<?= $model_item['filter_id'] ?>"><?= $model_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option style="display: none" class="default_custom" value="">Модель</option>
                                    <?php elseif(isset($model_if_mark)): ?>
                                             <option value="" class="default_custom">Модель</option>
                                        <?php foreach ($model_if_mark['list'] as $model_item): ?>
                                            <option value="<?= $model_item['keyword'] ?>" class="custom_filter"
                                            data-filter_id="<?= $model_item['filter_id'] ?>"
                                            id="<?= $model_item['filter_id'] ?>"><?= $model_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                         <option value="" class="default_custom">Модель</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="fastSearch__block fastSearch__generation jq-selectbox jqselect">
                                <select name="filter[]" id="generation" class="data-select_id">
                                    <?php if(isset($filter_modification)): ?>
                                        <?php foreach ($filter_modification['list'] as $modification_item): ?>
                                            <option value="<?= $modification_item['keyword'] ?>" class="custom_filter"
                                            <?php if($modification_item['filter_id'] == $filter_modification['data']['filter_id']): ?> selected <?php endif; ?>
                                            data-filter_id="<?= $modification_item['filter_id'] ?>"
                                            id="<?= $modification_item['filter_id'] ?>"><?= $modification_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option style="display: none" value="" class="default_custom">Поколение</option>
                                    <?php elseif(isset($modification_if_models)): ?>
                                        <option value="" class="default_custom">Поколение</option>
                                        <?php foreach ($modification_if_models['list'] as $modification_item): ?>
                                            <option value="<?= $modification_item['keyword'] ?>" class="custom_filter"
                                                    data-filter_id="<?= $modification_item['filter_id'] ?>"
                                                    id="<?= $modification_item['filter_id'] ?>"><?= $modification_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" class="default_custom">Поколение</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                            <div class="filter-item custom_preload">
                                <span>Двигатель</span>
                                <select name="filter_js[]" id="fuel" class="data-select_id">
                                    <?php if(isset($filter_fuel)): ?>
                                        <?php foreach ($filter_fuel['list'] as $fuel_item): ?>
                                            <option value="<?= $fuel_item['filter_id'] ?>" class="custom_filter"
                                            <?php if($fuel_item['filter_id'] == $filter_fuel['data']['filter_id']): ?> selected <?php endif; ?>
                                            data-filter_id="<?= $fuel_item['filter_id'] ?>"
                                            id="<?= $fuel_item['filter_id'] ?>"><?= $fuel_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option style="display: none" value="" class="default_custom">Тип топлива</option>
                                    <?php elseif(isset($fuel_if_models)): ?>
                                            <option value="" class="default_custom">Тип топлива</option>
                                        <?php foreach ($fuel_if_models['list'] as $fuel_item): ?>
                                            <option value="<?= $fuel_item['filter_id'] ?>" class="custom_filter"
                                                    data-filter_id="<?= $fuel_item['filter_id'] ?>"
                                                    id="<?= $fuel_item['filter_id'] ?>"><?= $fuel_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" class="default_custom">Тип топлива</option>
                                    <?php endif; ?>
                                </select>
                                <select name="filter_js[]" id="motor" class="data-select_id">
                                    <?php if(isset($filter_motor)): ?>
                                        <?php foreach ($filter_motor['list'] as $motor_item): ?>
                                            <option value="<?= $motor_item['filter_id'] ?>" class="custom_filter"
                                            <?php if($motor_item['filter_id'] == $filter_motor['data']['filter_id']): ?> selected <?php endif; ?>
                                            data-filter_id="<?= $motor_item['filter_id'] ?>"
                                            id="<?= $motor_item['filter_id'] ?>"><?= $motor_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option style="display: none" value="" class="default_custom">Тип двигателя </option>
                                    <?php elseif(isset($motor_if_models)): ?>
                                            <option value="" class="default_custom">Тип двигателя </option>
                                        <?php foreach ($motor_if_models['list'] as $motor_item): ?>
                                            <option value="<?= $motor_item['filter_id'] ?>" class="custom_filter"
                                                    data-filter_id="<?= $motor_item['filter_id'] ?>"
                                                    id="<?= $motor_item['filter_id'] ?>"><?= $motor_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" class="default_custom">Тип двигателя </option>
                                    <?php endif; ?>
                                </select>
                                <select  name="filter_js[]" id="engine_capacity" class="data-select_id">
                                    <?php if(isset($filter_engine_capacity)): ?>
                                        <?php foreach ($filter_engine_capacity['list'] as $engine_capacity_item): ?>
                                            <option value="<?= $engine_capacity_item['filter_id'] ?>" class="custom_filter"
                                            <?php if($engine_capacity_item['filter_id'] == $filter_engine_capacity['data']['filter_id']): ?> selected <?php endif; ?>
                                            data-filter_id="<?= $engine_capacity_item['filter_id'] ?>"
                                            id="<?= $engine_capacity_item['filter_id'] ?>"><?= $engine_capacity_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option style="display: none" value="" class="default_custom">Объем двигателя </option>
                                    <?php elseif(isset($engine_capacity_if_models)): ?>
                                            <option value="" class="default_custom">Объем двигателя </option>
                                        <?php foreach ($engine_capacity_if_models['list'] as $engine_capacity_item): ?>
                                            <option value="<?= $engine_capacity_item['filter_id'] ?>" class="custom_filter"
                                                    data-filter_id="<?= $engine_capacity_item['filter_id'] ?>"
                                                    id="<?= $engine_capacity_item['filter_id'] ?>"><?= $engine_capacity_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" class="default_custom">Объем двигателя </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="filter-item">
                                <span>Трансмиссия</span>
                                <select name="filter_js[]" id="transmission" class="data-select_id">
                                    <?php if(isset($filter_transmission)): ?>
                                        <?php foreach ($filter_transmission['list'] as $transmission_item): ?>
                                            <option value="<?= $transmission_item['filter_id'] ?>" class="custom_filter"
                                            <?php if($transmission_item['filter_id'] == $filter_transmission['data']['filter_id']): ?> selected <?php endif; ?>
                                            data-filter_id="<?= $transmission_item['filter_id'] ?>"
                                            id="<?= $transmission_item['filter_id'] ?>"><?= $transmission_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option style="display: none" value="" class="default_custom">КПП</option>
                                    <?php elseif(isset($transmission_if_models)): ?>
                                            <option value="" class="default_custom">КПП</option>
                                        <?php foreach ($transmission_if_models['list'] as $transmission_item): ?>
                                            <option value="<?= $transmission_item['filter_id'] ?>" class="custom_filter"
                                                    data-filter_id="<?= $transmission_item['filter_id'] ?>"
                                                    id="<?= $transmission_item['filter_id'] ?>"><?= $transmission_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" class="default_custom">КПП</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="filter-item">
                                <span>Кузов</span>
                                <select name="filter_js[]" id="body_type" class="data-select_id">
                                    <?php if(isset($filter_body_type)): ?>
                                        <?php foreach ($filter_body_type['list'] as $body_type_item): ?>
                                            <option value="<?= $body_type_item['filter_id'] ?>" class="custom_filter"
                                            <?php if($body_type_item['filter_id'] == $filter_body_type['data']['filter_id']): ?> selected <?php endif; ?>
                                            data-filter_id="<?= $body_type_item['filter_id'] ?>"
                                            id="<?= $body_type_item['filter_id'] ?>"><?= $body_type_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option style="display: none" value="" class="default_custom">Тип кузова</option>
                                    <?php elseif(isset($body_type_if_models)): ?>
                                            <option value="" class="default_custom">Тип кузова</option>
                                        <?php foreach ($body_type_if_models['list'] as $body_type_item): ?>
                                            <option value="<?= $body_type_item['filter_id'] ?>" class="custom_filter"
                                                    data-filter_id="<?= $body_type_item['filter_id'] ?>"
                                                    id="<?= $body_type_item['filter_id'] ?>"><?= $body_type_item['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" class="default_custom">Тип кузова</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        <div class="filter-result">
                            <span>Надено товаров <em class="js_total"><?= (isset($total)) ? $total : 0; ?></em></span>
                            <button  type="text" class="fastSearch__button button-filter" id="filter-search_button" value="Фильтровать">Фильтровать</button>
                        </div>
                    </form>
                </div>


            </div>


            <div class="col-lg-8 col-md-8 col-sm-7">
                <div class="sorting">
                    <span>Сортировать по:</span>
                    <form action="#">
                        <select onchange="location = this.value;">
                            <?php foreach ($sorts as $sorts) { ?>
                            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                            <option value="<?php echo $sorts['href']; ?>"
                                    selected="selected"><?php echo $sorts['text']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>

                    </form>
                    <div class="listing-icon_wrap">
                        <i id="listing_string" class="icon icon-listing_string active"></i>
                        <i id="listing_card" class="icon icon-listing_card"></i>
                    </div>
                </div>
                <div class="mobile-txt title"><?php echo $heading_title; ?></div>
                <div class="listing-wrap clearfix catcards">
                    <?php if ($products) { ?>
                    <?php foreach ($products as $product) { ?>
                    <div class="listing-item listing-item--string clearfix">
                        <div class="listing-item_img listing--several_img" id="slick-js">
                            <div class="img" style="background-image: url(<?php echo $product['thumb'] ?>);"></div>
                            <?php if(!empty($product['thumbs'])) { ?>
                            <?php foreach($product['thumbs'] as $key => $product_image){ ?>
                            <?php if($key < 3){ ?>
                            <div class="img"
                                 style="background-image: url(<?php echo $product_image['thumb'] ?>);"></div>
                            <?php } elseif($key === 4) { ?>
                            <div class="img" style="background-image: url(<?php echo $product_image['thumb'] ?>);">
                                <a href="<?php echo $product['newHref']; ?>">
                                    Еще <span><?php echo (count($product['thumbs']) - 4) ?></span> фото
                                </a>
                            </div>
                            <?php } else { continue ; } ?>
                            <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="listing-item_descr">
                            <a href="<?php echo $product['newHref']; ?>"><h4><?php echo $product['name']; ?></h4></a>
                            <div class="price">
                                <em>Цена с НДС</em><strong><?php echo $product['price']; ?></strong>
                            </div>
                            <?php if($product['attributes'] != ''): ?>
                                <?php foreach($product['attributes'] as $key => $product_attribute){ ?>
                                     <?php if(in_array($product_attribute['name'], ['КПП', 'Объем', 'Тип двигателя', 'Тип кузова', 'Тип топлива'])): ?>
                                        <div>
                                            <em><?php echo $product_attribute['name'] ?></em><span><?php echo $product_attribute['text'] ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php } ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>

    <!-- this is pagination for scrolling -->

    <!--<?php if ($loadmore_arrow_status) { ?>
    <a id="arrow_top" style="display:none;" onclick="scroll_to_top();"></a>
    <?php } ?>
    <div id="load_more" style="display:none;">
        <div class="row text-center" style="display:none;">
            <a href="#" class="load_more">Подгрузить ещё</a>
        </div>
    </div>-->

    <script type="text/javascript"><!--


        $('.last-item').click(function () {
            $('.category_menu li').removeAttr('hidden');
            $(this).remove();
        })

        $('.category_menu li').hover(function () {
            $('.category_menu-sub_menu li').show();
        });
        --></script>
</section>


<div id="overlay" style="display: none;"></div>

<!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cart-body">

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="success-body">
                <div id="add_to_cart-done">
                    <section class="cart-done">
                        <div class="cart-done-wrap">
                            <div class="popup_top">
                                <i class="icon icon-done-cart"></i>
                            </div>
                            <div class="popup_middle clearfix">
                                <h3>Товар добавлен в корзину</h3>
                            </div>
                            <div class="popup_bottom clearfix">
                                <a onclick="continueShopping()" class="bt">Продолжить покупки</a>
                                <a href="/checkout" id="checkout_btn" class="bt-2">Оформить заказ</a>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $('#reset_filter').on('click', function () {
        $('select.data-select_id > option.custom_filter').remove();
        $('.jq-selectbox__dropdown').remove();
        $('select.data-select_id > option').show();
        $('select#marks option:selected').removeAttr('selected');
        $('select option.default_custom').prop('selected', true);
        $('select').trigger('refresh');
    });


    $('select.data-select_id').on('change', function (e) {
        let updateSelector = this.id;
        let updateValue = this.value;
        if(updateSelector === 'marks'){
            $('select.data-select_id > option.custom_filter').remove();
            $('select.data-select_id > option').show();
        }else if(updateSelector === 'models'){
            $('select.data-select_id').each(function() {
                if($(this).attr('id') !== "models") {
                    $(this).children().show();
                    $(this).children('.custom_filter').remove();
                }
            });
        }else if(updateSelector === 'generation'){
            $('select.data-select_id').each(function() {
                if($(this).attr('id') !== "models" && $(this).attr('id') !== 'generation') {
                    $(this).children().show();
                    $(this).children('.custom_filter').remove();
                }
            });
        }
    });



    $('select.data-select_id').on('change', function (e) {
        $('.ajax_loader').remove();
        let updateSelector = this.id;
        let updateValue = this.value;

        if(updateSelector === 'generation'){
            $('.custom_preload').before('<div class="ajax_loader"><img src="/image/catalog/17.gif" /></div>')
        }else{
            $(e.target).closest('.fastSearch__block').next().before('<div class="ajax_loader"><img src="/image/catalog/17.gif" /></div>');
        }

        $.post('index.php?route=extension/module/filtersearch/getSelectFilters', {
            filterKeyword: this.value,
            filterType: this.name,
            filterMethod: this.id,
            filterId: $(this).children(':selected').attr('id')
        }, function (data) {

            $('.ajax_loader').remove();
            if (updateSelector === 'marks') {
                data = '<option value="" class="default_custom">Модель</option>' + data;
                $('select#models').html(data).trigger('refresh');
                let clearData = '<option value="" class="default_custom">Поколение</option>';
                $('select#generation').html(clearData).trigger('refresh');
                $('#extended-div').css('cursor', 'not-allowed');
                $('#filter-search_extended').css('pointer-events', 'none');
                $('.filters.filters-main').slideUp(500);
                $('.filters.filters-main, .fastSearch__more').removeClass('open');
            } else if (updateSelector === 'models') {

                if (data === '') {
                    data = '<option value="" id="0" class="default_custom">Без модификации</option>';
                    $('select#generation').html(data).trigger('refresh');
                    $('#filter-search_extended').css('pointer-events', 'all');
                    $('#extended-div').css('cursor', 'not-allowed');
                } else {
                    data = '<option value="" class="default_custom">Поколение</option>' + data;
                    $('select#generation').html(data).trigger('refresh');
                    $('#extended-div').css('cursor', 'not-allowed');
                    $('#filter-search_extended').css('pointer-events', 'none');
                    $('.filters.filters-main').slideUp(500);
                    $('.filters.filters-main, .fastSearch__more').removeClass('open');
                }
            } else if (updateSelector === 'generation' && updateValue !== '') {
                $.each(data, function (index, value) {
                    let html = '' + build_option(index, value);
                    if (index === 'Тип двигателя') {
                        $('select#motor').html(html).trigger('refresh');
                    }else if(index === 'КПП'){
                        $('select#transmission').html(html).trigger('refresh');
                    }else if(index === 'Объем'){
                        $('select#engine_capacity').html(html).trigger('refresh');
                    }else if(index === 'Тип топлива'){
                        $('select#fuel').html(html).trigger('refresh');
                    }else if(index === 'Тип кузова'){
                        $('select#body_type').html(html).trigger('refresh');
                    }
                });
            } else {
                $('#extended-div').css('cursor', 'not-allowed');
                $('#filter-search_extended').css('pointer-events', 'none');
                $('.filters.filters-main').slideUp(500);
                $('.filters.filters-main, .fastSearch__more').removeClass('open');
            }
        });
    });

    function build_option(index, value){
        let html = '<option value="" class="default_custom">'+ index +'</option>';
        $.each(value, function (index2, value2) {
            html = html + '<option value="' + value2['filter_id'] + '" data-filter_id="' + value2.filter_id + '" id="' + value2['filter_id'] + '">' + value2['name'] + '</option>'
        });
        return html;
    }

    $('#extended_filters-ajax').delegate('input[name^=\'filter-search\']', 'change', function () {
        if ($(this).is(':checked')) {
            $('#filter_status').append('<a href="javascript:(0)" id="delete_status_' + $(this).attr('data-filter_id') + '"><span>' + $(this).attr('data-filter_name') + '</span></a>');
        } else {
            $('#delete_status_' + $(this).attr('data-filter_id')).remove();
        }
    });



    $('button#extended_filters__resetButton').on('click', function () {
        $('select.data-select_id').val('').trigger('refresh');
        $('input:checkbox').removeAttr('checked');
        $('label.checkbox__label').removeClass('active');
        $('#extended-div').css('cursor', 'not-allowed');
        $('#filter-search_extended').css('pointer-events', 'none');
        $('.filters.filters-main').slideUp(500);
        $('.filters.filters-main, .fastSearch__more').removeClass('open');
        $('#filter_status').empty();
    });

    function searchingExtendedFilters() {
        let input, hideElements, checkboxList, filter, list, elements, text, i;
        input = document.getElementById('searchingInput');
        filter = input.value.toUpperCase();
        list = document.getElementById("searchingList");
        elements = list.querySelectorAll('span.fillengine__model');
        hideElements = list.querySelectorAll('.fillengine__type');
        checkboxList = list.querySelectorAll('label');

        for (i = 0; i < elements.length; i++) {
            text = elements[i];
            if (text.innerHTML.toUpperCase().indexOf(filter) > -1) {
                elements[i].style.display = '';
                checkboxList[i].style.display = '';
                hideElements[i].style.display = '';
            } else {
                hideElements[i].style.display = 'none';
                elements[i].style.display = 'none';
                checkboxList[i].style.display = 'none';
            }
        }
    }


    $('#search_detal').keyup(function (event) {
        if ($('#search_detal').val().length > 2) {
            $.ajax({
                url: $('base').attr('href') + 'index.php?route=extension/module/filtersearch/autocomplete&filter_name=' + $('#search_detal').val(),
                dataType: 'json',
                beforeSend: function (xhr) {
                },
                success: function (result) {
                    var html = "";
                    for (var item in result) {
                        html += "<option name='filter[]' id='search-detail__selected' class='search-detail' data-keyword='" + result[item].keyword + "' data-idcategory='" + result[item].category_id
                            + "'>" + result[item].name + "</option>";
                    }
                    $('#category_search_result').html(html);
                    if ($('#category_search_result option').length > 1) {
                        $('#search_detal').parent().addClass('open');
                    }
                }
            });
        }


        if ($('#search_detal').val().length == 0 || $('#search_detal').val().length == undefined) {
            $('#category_search_result .search-detail').remove();
            $(this).parent().removeClass('open');
        }

    });

    $('#category_search_result').delegate('option.search-detail', 'click', function () {
        $(this).attr('selected', 'selected');
        var optionVal = $(this).val();
        $('#search_detal').val(optionVal);

        $('#category_search_result .search-detail').not($(this)).remove();
        $(this).parent().parent().removeClass('open');

    });

    // close dropdown by click on body
    $(document).click(function (e) {

        var div = $("#category_search_result, #search_detal");

        if (!div.is(e.target) && div.has(e.target).length === 0) {
            div.parent().removeClass('open');
        }

    });

    // open dropdown by click on input
    $('#search_detal').on('focus', function () {

        if ($(this).val().length > 1) {
            $(this).parent().addClass('open');
        }

    });


    function showCart(product_id) {
        $.post('index.php?route=product/product/getproductInfo',{product_id:product_id}, function (data) {
            $('#cartModal').show();
            $('#cart-body').html(data);
            $('#cartModal').modal('show');
            $('input, select').styler();
        });
    }

    function cartAdd(product_id) {
        $('#cartModal').modal('hide');
        cart.add(product_id, $('#productLimit').val());
        $('#successModal').show();
        //$('#overlay').hide();
        $('#successModal').modal('show');
    }

    function continueShopping() {

        $('#successModal').hide();
        $('#overlay').hide();
    }


    $('.filter-title').on('click', function() {
        let inputs	   = [];
        $('select[name="filter_js[]"]').each(function () {
            console.log($(this).val())
            inputs.push($(this).val());
        });
        console.log(inputs);

    });





        $('#filter-search_button, #filterSearch_button-filter').on('click', function() {

        let mark       = $('select#marks').val(),
            model      = $('select#models').val(),
            inputs	   = [],
            category   = $('#custom_category').val(),
            generation = $('select#generation').val();

        $('select[name="filter_js[]"]').each(function () {
            if($(this).val() !== ''){
                inputs.push($(this).val());
            }
        });

        let url = '<?php echo $siteBase; ?>' + urlGeneration(category, mark, model, generation, inputs);


        $('#form__filters').attr('action', url);



    });
</script>

<?php echo $footer; ?>