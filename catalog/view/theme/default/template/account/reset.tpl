<?php echo $header; ?>

<section class="login">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
        <a href="<?php echo $home ?>"><div class="logo">Берег</div></a>
      </div>
      <div class="col-lg-8">
        <div class="login-link">
          <span>У вас есть аккаунт?</span>
          <a href="<?php echo $back; ?>" class="registr">Войти</a>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="login-link">
          <span>У вас нет аккаунта?</span>
          <a href="<?php echo $register; ?>" class="registr">Зарегистрироваться</a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-5"></div>
      <div class="col-lg-5">
        <div class="form-wrap">
          <h2>Сброс пароля</h2>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" >
            <label>
              <span>Пароль</span>
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="не менее 5 символов" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
              <i class="icon icon-visible"></i>
            </label>

            <label>
              <span>Подтвердите пароль</span>
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="не менее 5 символов" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php } ?>
              <i class="icon icon-visible"></i>
            </label>
            <br>
            <div class="buttons clearfix">
              <button class="custom_button" type="submit">Сохранить</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-2"></div>
    </div>
    <div class="row">
      <div class="col-lg-4"></div>
      <div class="col-lg-8">
        <div class="login_social-wrap">
          <div class="login_social">
            <div class="top">Или</div>
            <span class="reg">Зарегистрироваться через</span>
            <a href="<?php echo $fb ?>">
              <i class="icon icon-fb-2"></i>
            </a>
            <a href="<?php echo $vk ?>">
              <i class="icon icon-vk-2"></i>
            </a>
            <a href="<?php echo $tw ?>">
              <i class="icon icon-tw-2"></i>
            </a>
          </div>
          <p>Нажимая «Зарегистрироваться» вы даете согласие на обработку персональных данных</p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php echo $footer; ?>