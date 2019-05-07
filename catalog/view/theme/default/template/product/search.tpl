<?php echo $header; ?>

<?php echo $search; ?>

<section class="top-title result">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <a href="<?php echo $back; ?>" class="back_link">
          <i class="icon icon-back"></i>
          <span>Назад</span>
        </a>
        <h1 class="js_result_custom" <?php if ($custom_search == '') { ?> style="display: none"  <?php } ?>>Результаты поиска по запросу «<span class="js_result"><?php echo $custom_search ?></span>»</h1>
      </div>
    </div>
  </div>
</section>

<section class="search_result">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="mobile-txt title js_result_custom" <?php if ($custom_search == '') { ?> style="display: none"  <?php } ?>>Результаты поиска по запросу «<span class="js_result"><?php echo $custom_search ?></span>»</div>
      </div>
    </div>
    <div class="row">

      <?php if ($cats) { ?>
        <div class="col-lg-12" style="margin-left: 250px">
          <?php foreach ($cats as $product) { ?>
            <div class="search_result-title">
              <h3>
                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
              </h3>
            </div>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if ($products) { ?>
        <div class="col-lg-12">
          <?php foreach ($products as $product) { ?>
            <div class="search_result-item clearfix">
              <div class="search_result-img">
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
              </div>
              <div class="search_result-title">
                <h3>
                  <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                </h3>
              </div>
            </div>
          <?php } ?>
        </div>
      <div class="row">
        <div class="col-lg-12 text-left"><?php echo $pagination; ?></div>
        <!--<div class="col-sm-6 text-right"><?php echo $results; ?></div>--!>
      </div>
      <?php } else { ?>
      <p></p>
      <?php } ?>
    </div>
  </div>
</section>

<div id="overlay" style="display: none;"></div>
<?php echo $footer; ?>
