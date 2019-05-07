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
          <span>У вас нет аккаунта?</span>
          <a href="<?php echo $register; ?>" class="registr">Зарегистрироваться</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-4"></div>
      <div class="col-lg-5 col-md-5 col-sm-6">
        <div class="form-wrap">
          <h2>Забыли пароль?</h2>
          <?php if ($error_warning) { ?>
          <p class="info error">Указанный E-mail, не зарегистрирован в системе</p>
          <?php } ?>
          <!-- <p class="info">Для восстановления пароля укажите E-mail, на который будет отправлен Ваш пароль</p> -->
          <!-- <p class="info error">Указанный E-mail, не зарегистрирован в системе</p>-->
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
            <label>
              <span>E-mail</span>
              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
            </label>
            <button class="btn-accent" type="submit">Отправить</button>
            <a href="<?php echo $login; ?>" class="back">Вернуться во Вход в аккаунт</a>
          </form>
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2"></div>
    </div>
  </div>
</section>