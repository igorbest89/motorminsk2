<?php echo $header; ?>
<?php echo $search; ?>

<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="breadcrumb">
          <ul>

            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
              <li>
                <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?> /</a>
              </li>
            <?php } ?>
            <li>Производители</li>
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
        <a href="<?php echo $back; ?>" class="back_link">
          <i class="icon icon-back"></i>
          <span>Назад</span>
        </a>
        <h1>Каталог марок</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="fast-search">
          <input id="match-search_manufacturers" type="text" placeholder="Введите название марки или модели" />
          <i class="icon icon-search-2"></i>
          <span>Быстрый поиск по производителям</span>
          <ul id="append_ul">

          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="brand_list">
  <div class="container">
    <?php foreach ($categories as $category): ?>
      <div class="row">
        <div class="col-lg-2">
          <div class="character"><?= implode('/', $category['literal']) ?></div>
        </div>
        <div class="col-lg-10">
          <div class="wrapper">
            <div class="row">
              <?php foreach ($category as $key => $mark): ?>
                <?php if(is_numeric($key)): ?>
                  <div class="col-lg-4">
                    <div class="brand_list-item">
                      <a href="<?= $base . 'zapchasti/' . $mark['keyword'] ?>"><span><?= $mark['name'] ?></span></a>
                      <ul class="items_list clearfix">
                        <?php if(isset($mark['models'])): ?>
                          <?php foreach ($mark['models'] as $models): ?>
                            <li>
                              <a href="<?= $base . 'zapchasti/' . $mark['keyword'] . '/' . $models['keyword'] ?>"><?= $models['name'] ?></a>
                            </li>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </ul>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<div id="overlay" style="display: none;"></div>
<?php echo $footer; ?>