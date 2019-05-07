<section class="section-special">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h2>Специальные предложения</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="slider">
          <?php foreach ($banners as $banner) { ?>
            <div class="slider-item clearfix">
              <div class="slider-item_img">
                <div class="img" style="background-image: url(<?php echo $banner['image']; ?>);"></div>
              </div>
              <div class="slider-item_descr">
                <h4>Специальное предложение</h4>
                <h2><?php echo $banner['title']; ?></h2>
                <a href="<?php echo $banner['link']; ?>">Посмотреть</a>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>