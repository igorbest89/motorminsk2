<?php echo $header; ?>

<section class="login">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-4">
        <a href="<?php echo $home ?>">
          <div class="logo">
              <img src="/image/logo.png" alt="logo">
          </div>
          <div class="mobile-logo">
              <img src="/image/logo_mobile.png" alt="logo">
          </div>
        </a>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-8">
        <div class="login-link">
          <span>У вас есть аккаунт?</span>
          <a href="<?php echo $login; ?>" class="registr">Войти</a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-4"></div>
      <div class="col-lg-5 col-md-5 col-sm-6">
        <div class="form-wrap">
          <h2>Регистрация</h2>

          <?php if ($error_email) { ?>
          <p class="info error"> <?php echo $error_email; ?></p>
          <?php } ?>
          <?php if ($error_warning) { ?>
          <p class="info error"> <?php echo $error_warning; ?></p>
          <?php } ?>
          <?php if ($error_password) { ?>
          <p class="info error"> <?php echo $error_password; ?></p>
          <?php } ?>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" >
            <label>
              <span>E-mail</span>
              <input type="email" name="email" value="<?php echo $email; ?>" placeholder="E-Mail" id="input-email" class="form-control" />
            </label>
            <label>
              <span>Пароль</span>
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="не менее 5 символов" id="input-password" class="form-control" />
              <i class="icon icon-visible"></i>
            </label>
            <button class="btn-accent" type="submit">Зарегистрироваться</button>
            <?php echo $content_top; ?>
          </form>
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-2"></div>
      <div class="col-lg-8 col-md-8 col-sm-10">
        <div class="login_social-wrap">
          <div class="login_social">
            <div class="top">Или</div>
            <span class="reg">Зарегистрироваться через</span>
            <a href="<?= $fb ?>">
              <i class="icon icon-fb-2"></i>
            </a>
            <a href="<?= $vk ?>">
              <i class="icon icon-vk-2"></i>
            </a>
            <a href="<?= $tw ?>">
              <i class="icon icon-tw-2"></i>
            </a>
          </div>
          <p>Нажимая «Зарегистрироваться» вы даете согласие на обработку персональных данных</p>
        </div>
      </div>
    </div>
  </div>
</section>




