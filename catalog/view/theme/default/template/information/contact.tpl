<?php echo $header; ?>
<?php echo $search; ?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
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
      <div class="col-lg-12">
        <a href="<?php echo $back ?>" class="back_link">
          <i class="icon icon-back"></i>
          <span>Назад</span>
        </a>
        <h1><?php echo $heading_title; ?></h1>
        <div class="send-wrap">
          <a href="#popup_email" class="popup_open" data-type="loc" data-id="<?php echo $regions['location_id'] ?>">
            <i class="icon icon-contact-send"></i>
          </a>
          <a href="#popup_phone" class="popup_open" data-type="loc" data-id="<?php echo $regions['location_id'] ?>">
            <i class="icon icon-contact-sms"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="contact">
  <div class="container">
    <div class="row">
      <div class="col-lg-1"></div>
      <div class="col-lg-10">
        <div class="contact-wrap">
          <div class="contact-item">
            <div class="contact-item_inner clearfix">

                <div class="clearfix">
                  <ul>
                    <li>
                      <i class="icon icon-contact-time"></i>
                      <span><?php echo $regions['open'] ?></span>
                    </li>
                    <li>
                      <i class="icon icon-contact-phone"></i>
                      <?php foreach($regions['telephone'] as  $telephone) { ?>
                      <span><?php echo $telephone ?></span>
                      <?php } ?>
                    </li>
                    <li>
                      <i class="icon icon-contact-fax"></i>
                      <span><?php echo $regions['fax'] ?></span>
                    </li>
                    <li>
                      <i class="icon icon-contact-marker"></i>
                      <span><?php echo $regions['address'] ?></span>
                    </li>
                    <li>
                      <i class="icon icon-contact-mail"></i>
                      <span><?php echo $regions['email'] ?></span>
                    </li>
                    <li>
                      <i class="icon icon-contact-pos"></i>
                      <span><?php echo $regions['geocode'] ?></span>
                    </li>
                  </ul>
                  <div class="contact_map">
                    <iframe src="http://maps.google.com/maps?q=<?php echo $regions['geocode_w'] ?>, <?php echo $regions['geocode_h'] ?>&z=15&output=embed" width="360" height="270" frameborder="0" style="border:0"></iframe>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      <div class="col-lg-1"></div>
      </div>
    <div class="row">
      <div class="col-lg-1"></div>
      <div class="col-lg-10">
        <div class="contact-form_wrap">
          <h4>Напишите нам</h4>
          <span class="e-mail"><?php echo $main_email ?></span>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <div class="form-item">
              <label>
                <span>E-mail</span>
                <input id="input-email" type="text" name="email" value="<?php echo $email; ?>" placeholder="Введите ваш E-mail" class="form-control" />
                  <?php if ($error_email) { ?>
                      <div class="text-danger"><?php echo $error_email; ?></div>
                  <?php } ?>
              </label>
            </div>
            <div class="form-item">
              <label>
                <span>Имя</span>
                <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" placeholder="Введите ваше имя" />
                <?php if ($error_name) { ?>
                <div class="text-danger"><?php echo $error_name; ?></div>
                <?php } ?>
              </label>
            </div>
            <div class="form-item">
              <label>
                <span>Сообщение</span>
                <textarea name="enquiry" id="input-enquiry" class="form-control" placeholder="Пишите, не стесняйтесь ;)"></textarea>
                <?php if ($error_enquiry) { ?>
                <div class="text-danger"><?php echo $error_enquiry; ?></div>
                <?php } ?>
              </label>
            </div>
            <div class="form-item">
              <button class="btn-accent" type="submit">Отправить</button>
            </div>
            <div class="form-item">
              <label>
                <input type="checkbox" name="copy" value="1"/>
                <span>Отправить мне копию</span>
              </label>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-1"></div>
    </div>
  </div>
</section>

<div class="popup" id="popup_phone">
  <div class="close_popup">
    <i class="icon icon-close"></i>
  </div>
  <div class="popup_header">
    <h3>Отправить СМС</h3>
    <p class="sms_store_group">Центральный офис</p>
  </div>
  <div class="popup_body">
    <form id="form_phone">
      <label>
        <span>Телефон</span>
        <input type="text" name="phone" class="send_sms"/>
        <input type="hidden" name="location_id" value="" class="hidden_id"/>
        <input type="hidden" name="type" value="" class="hidden_type"/>
        <input type="hidden" name="action" value="send_sms"/>
      </label>
    </form>
  </div>
  <div class="popup_footer clearfix">
    <button class="btn cancel_btn">Отмена</button>
    <button class="btn btn-accent" id="send_sms_btn">Отправить</button>
  </div>
</div>

<div class="popup" id="popup_email">
  <div class="close_popup">
    <i class="icon icon-close"></i>
  </div>
  <div class="popup_header">
    <h3>Отправить на Email</h3>
    <p class="email_store_group">Центральный офис</p>
  </div>
  <div class="popup_body">
    <form id="form_email">
      <label>
        <span>Email</span>
        <input type="text" name="email" class="send_email"/>
        <div style="display: none; color: red" class="danger">Не корректный email</div>
        <input type="hidden" name="location_id" value="" class="hidden_id"/>
        <input type="hidden" name="type" value="" class="hidden_type"/>
        <input type="hidden" name="action" value="send_email"/>
      </label>
    </form>
  </div>
  <div class="popup_footer clearfix">
    <button class="btn cancel_btn">Отмена</button>
    <button class="btn btn-accent" id="send_email_btn">Отправить</button>
  </div>
</div>
<div id="overlay"></div>
<?php echo $footer; ?>
