<?php echo $header; ?>

<div id="search" class="container-fluide">
  <?php echo $search; ?>
</div>

<section class="not_found">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="wrap-404 clearfix">
          <div class="wrap-404_type">
            <p>404</p>
          </div>
          <div class="wrap-404_descr">
            <p class="big">Ой!</p>
            <p>Возможно вы указали несуществующий URL или страница была удалена. </p>
            <a href="<?php echo $continue; ?>" class="btn-accent">Вернуться назад</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<?php echo $footer; ?>



