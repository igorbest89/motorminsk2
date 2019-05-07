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
                <h1><?php echo $heading_title; ?></h1>
                <p class="descr descr--data"><?php echo $date; ?></p>
            </div>
        </div>
    </div>
</section>

<section class="news-details">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="mobile-txt title"><?php echo $heading_title; ?></div>
                <div class="details-wrap">
                    <?php echo $description; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="overlay" style="display: none;"></div>
<?php echo $footer; ?>

