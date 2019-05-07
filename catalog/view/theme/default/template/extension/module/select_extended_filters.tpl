<?php foreach ($parentFilters as $parentFilter): ?>
    <!-- begin filters__block -->
    <div cla<!--filters__block">

        <div class="filters__subtitle">
            <span><?php echo $parentFilter['name'] ?></span><i class="fa fa-chevron-right"></i>
        </div>

        <!-- begin filters__content filters__content-js -->
        <div class="filters__content fillengine">

            <?php if ($parentFilter['name'] == 'Тип двигателя'): ?>
            <div><input type="text" class="fillengine__input" onkeyup="searchingExtendedFilters()" id="searchingInput"  placeholder="Введите тип..."></div>
            <?php endif; ?>

            <!-- begin fillmark__scroll -->
            <div class="fillengine__scroll">

                <?php if ($parentFilter['name'] == 'Тип двигателя'): ?> <div class="fillengine__innerScroll sidebar-scroll" id="searchingList">
                <?php else: ?>
                <div class="fillengine__innerScroll sidebar-scroll">
                <?php endif; ?>

                    <?php foreach ($childFilters as $childFilter): ?>
                        <?php if ($childFilter['filter_group_id'] == $parentFilter['filter_group_id']): ?>
                            <div class="fillengine__type">

                                <div>
                                    <label class="checkbox__label">
                                        <input type="checkbox"
                                               value="<?php echo $childFilter['filter_id'] ?>"
                                               name="filter-search[]"
                                               data-filter_name="<?php echo $childFilter['name'] ?>"
                                               data-filter_id="<?php echo $childFilter['filter_id'] ?>">
                                    </label>
                                </div>
                                <span class="fillengine__model"><?php echo $childFilter['name'] ?></span>
                                <span class="fillengine__count"><?php echo $childFilter['count_product'] ?></span>
                                <?php $massSum += $childFilter['count_product']; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>

            </div>
            <!-- end fillmark__scroll -->

        </div>
        <!-- end filters__content filters__content-js -->

    </div>
    <!-- end filters__block -->
<?php endforeach; ?>
    <input type="hidden" value="<?php echo $massSum; ?>" id="hidden-massSum">
    <div class="filters__block">

        <div class="filters__subtitle">
            <span>Цена</span><i class="fa fa-chevron-right"></i>
        </div>

        <!-- begin filters__content filters__content-js -->
        <div class="filters__content fillprice">

            <!-- fillprice__range begin -->
            <div class="fillprice__range">

                <div class="slider-range">
                    <span class="ui-slider-handle ui-corner-all ui-state-default min-sl"></span><span class="ui-slider-handle ui-corner-all ui-state-default max-sl"></span>
                </div>

                <div class="fillprice__inner">
                    <div class="fillprice__wrapInp">
                        <span>от</span><input type="text" readonly class="amount">
                        <input type="hidden" id="hidden__min-price" name="hidden__min-price">
                    </div>
                    <div class="fillprice__wrapInp">
                        <span>до</span><input type="text" readonly class="amount2">
                        <input type="hidden" id="hidden__max-price" name="hidden__max-price">
                    </div>
                </div>

            </div>
            <!-- fillprice__range end -->

        </div>
        <!-- end filters__content filters__content-js -->

    </div>
    <!-- end filters__block -->
