<div class="contact-item">
    <h3>
        <i class="icon icon-plus"></i>
        <span><?php echo $module_name ?></span>
    </h3>
    <div class="contact-item_inner clearfix">
        <div class="send-wrap">
            <a href="#popup_email" class="popup_open" data-type="req" data-id="<?php echo $module_id ?>">
                <i class="icon icon-contact-send" ></i>
            </a>
            <a href="#popup_phone" class="popup_open" data-type="req" data-id="<?php echo $module_id ?>">
                <i class="icon icon-contact-sms" ></i>
            </a>
        </div>
        <div class="requisites-wrap">
            <div class="requisites-item">
                <ul>
                    <li><strong><?php echo $store_name ?></strong></li>
                    <li><?php echo $address ?></li>
                    <li><?php echo $republic ?></li>

                    <li>УНП <?php echo $unp ?></li>
                    <li>ОКПО <?php echo $okpo ?></li>
                </ul>
            </div>
            <?php foreach($requisites as $requisite){ ?>
                <div class="requisites-item">
                    <img src="<?php echo $requisite['thumb'] ?>" alt="belgazbank">
                    <ul>
                        <li><strong><?php echo $requisite['requisite_name'] ?></strong></li>
                        <li>BIC <?php echo $requisite['bic'] ?></li>
                        <li>р/с <?php echo $requisite['rs'] ?></li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

