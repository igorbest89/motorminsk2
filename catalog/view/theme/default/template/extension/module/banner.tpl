<?php foreach($banners as $banner){ ?>
<section class="banner">
<div class="custom_banner">
    <div class="row">
      <div class="col-lg-3">
        <div class="img" href="/">
          <img src="image/search_logo.png" alt="logo" />
        </div>
      </div>
      <div class="col-lg-9">
        <h1><?php echo $banner['title']?></h1>
        <input type="hidden" id="custom_input">
        <p>В наличии более 60 000 запчастей, 450 моторов, 800 КПП</p>
        <div class="search_wrap">
          <form class="clearfix" action="<?php echo $siteBase; ?>" method="post" id="form__filters" autocomplete="off">

            <div class="srch">
              <input type="text"  autocomplete="off" class="srch__field" name="search" placeholder="Что будем искать?" />
              <div class="dropdown_search" style="display: none">
                <a  class="close js_close">
                  <i class="icon icon-close"></i>
                </a>
                <ul class="srch__result">

                </ul>
                <a  class="all_result js_show_all">Найдено <span class="js_elastic_total_product"></span> продуктов и <span class="js_elastic_total_category"></span> категорий <span class="go_to_search">показать все</span></a>

              </div>
              <button id="custom_search_button" type="button">
                <i class="icon icon-search" id="button-search"></i>
              </button>
            </div>

            <button type="submit" id="filter-search_button">
              <i class="icon icon-search"></i>
            </button>

            <p class="custom_p">Выбор по машине и категории запчасти</p>

            <!-- Marks select -->
            <div class="fastSearch__block fastSearch__mark jq-selectbox jqselect">
              <select name="filter[]" id="marks" class="data-select_id">
                <option value="" selected="">Марка</option>
                <?php foreach ($marks as $mark): ?>
                <option value="<?php echo $mark['keyword'] ?>" id="<?php echo $mark['filter_id'] ?>"><?php echo $mark['name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <!-- Marks select END -->

            <!-- Models select -->
            <div class="fastSearch__block fastSearch__model jq-selectbox jqselect">
              <select name="filter[]" id="models" class="data-select_id">
                <option value="" selected="">Модель</option>
              </select>
            </div>
            <!-- Models select END -->

            <!-- Generation select -->
            <div class="fastSearch__block fastSearch__generation jq-selectbox jqselect">
              <select name="filter[]" id="generation" class="data-select_id">
                <option value="" selected="">Поколение</option>
              </select>
            </div>
            <!-- Generation select END -->

            <!-- Fast_search select -->
            <div class="fastSearch__block fastSearch__search jq-selectbox jqselect">
              <select id="filterSearch__categories" data-placeholder="Выберите делать..." class="chosen-select">
                <option value="">Выберите делать...</option>
                <?php foreach ($allCategories as $category): ?>
                <option value="<?php echo $category['href'] ?>"><?php echo $category['name']; ?></option>
                <?php endforeach; ?>
              </select>


            </div>
            <!-- Fast_search select END -->

          </form>
        </div>
      </div>
    </div>
</div>
</section>
<?php } ?>
<script>



  $('#filter-search_button, #filterSearch_button-filter').on('click', function() {

         let mark      = $('select#marks').val(),
            model      = $('select#models').val(),
            category   = $('select#filterSearch__categories').val(),
            generation = $('select#generation').val();



    let url = '<?php echo $siteBase; ?>' + urlGeneration(category, mark, model, generation);
    $('#form__filters').attr('action', url);



  });
</script>
