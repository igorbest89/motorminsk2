<?php echo $header; ?>
<?php echo $search; ?>

<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
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
      <div class="col-lg-12 col-md-12">
        <a href="<?php echo $back; ?>" class="back_link">
          <i class="icon icon-back"></i>
          <span>Назад</span>
        </a>
        <h1>Статьи <?php if(isset($year)) echo ' за ' . $year  ?></h1>
      </div>
    </div>
  </div>
</section>

<section class="news">
  <div class="container">
    <div class="row">
      <div class="col-lg-2 col-md-1 col-sm-1">
      <div class="mobile-txt title">Статьи <?php if(isset($year)) echo ' за ' . $year  ?></div>
        <div class="sidebar">
          <ul>
            <?php foreach ($years as $year) { ?>
              <li>
                <a href="<?php echo $year['href']; ?>"><?php echo $year['text']; ?></a>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <div class="col-lg-1 col-md-1 hidden-sm"></div>
      <div class="col-lg-9 col-md-10 col-sm-11">
        <?php foreach ($articles as $article) { ?>
          <div class="news-item clearfix">
            <div class="img-wrap">
             <img src="<?php echo $article['thumb']; ?>" alt="#">
            </div>
            <div class="descr-wrap">
              <h3>
                <a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a>
              </h3>
              <p class="date"><?php echo $article['date_modified']; ?></p>
              <?php echo $article['intro_text']; ?>
              <a href="<?php echo $article['href']; ?>" class="read_more">Читать далее</a>
            </div>
          </div>
        <?php } ?>
      </div>
      <div class="col-lg-12 col-md-12">


            <?php echo $pagination; ?>


      </div>
    </div>
  </div>
</section>
<div id="overlay" style="display: none;"></div>
<?php echo $footer; ?>
