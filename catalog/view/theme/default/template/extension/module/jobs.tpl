<?php echo $search; ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb">
                    <ul>
                        <li>
                            <a href="<?php echo $home ?>">Главная /</a>
                        </li>
                        <li><?php echo $module_name ?></li>
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
                <h1>Вакансии</h1>
                <p class="descr descr--jobs">
                    Мы предлагаем достойную и высокооплачиваемую работу.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="job-info">
    <?php echo $module_description?>
</section>

<section class="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="contact-wrap">
                    <?php foreach($jobs as $job){ ?>
                        <div class="contact-item">
                            <h3>
                                <i class="icon icon-plus"></i>
                                <span><?php echo $job['job_name']?></span>
                            </h3>
                            <div class="jobs-item_inner clearfix">
                                <div class="clearfix">
                                    <?php echo $job['description']?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>
</section>
<div id="overlay" style="display: none;"></div>