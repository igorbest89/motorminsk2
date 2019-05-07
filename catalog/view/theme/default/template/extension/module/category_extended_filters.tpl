<?php foreach ($parentFilters as $parentFilter): ?>
    <!-- begin filters__block -->
    <div class="filters__block">

        <div class="filters__subtitle filters__subtitle-js">
            <span><?php echo $parentFilter['name'] ?></span><i class="fa fa-chevron-right"></i>
        </div>

        <!-- begin filters__content filters__content-js -->
        <?php if ($parentFilter['name'] == 'Тип двигателя'): ?>
            <div class="filters__content fillengine">
        <?php else: ?>
            <div class="filters__content filters__content-js fillengine">
        <?php endif; ?>

        <?php if ($parentFilter['name'] == 'Тип двигателя'): ?>
            <div><input type="text" class="fillengine__input" onkeyup="searchingExtendedFilters()" id="searchingInput"  placeholder="Введите тип..."></div>
        <?php endif; ?>

            <!-- begin fillmark__scroll -->
            <div class="fillengine__scroll">

                <?php if ($parentFilter['name'] == 'Тип двигателя'): ?>
                <div class="fillengine__innerScroll sidebar-scroll" id="searchingList">
                <?php else: ?>
                <div class="fillengine__innerScroll sidebar-scroll">
                <?php endif; ?>

                    <?php foreach ($childFilters as $childFilter): ?>
                        <?php if ($childFilter['filter_group_id'] == $parentFilter['filter_group_id']): ?>
                            <div class="fillengine__type">

                                <div>
                                    <label class="checkbox__label">
                                        <input type="checkbox"
                                               value="<?php echo $childFilter['keyword'] ?>"
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
<input type="hidden" value="" id="hidden__min_price">
<input type="hidden" value="" id="hidden__max_price">