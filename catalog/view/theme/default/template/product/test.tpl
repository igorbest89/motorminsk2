<div class="container-fluide">
    <div class="search_wrap">
        <form>
            <div class="srch">
                <input type="text" class="srch__field" placeholder="Что будем искать?" />
            </div>
            <button type="submit">
                <i class="icon icon-search"></i>
            </button>
        </form>
        <div class="dropdown_search">
            <a  class="close js_close">
                <i class="icon icon-close"></i>
            </a>
            <ul class="srch__result">

            </ul>
            <a  class="all_result js_show_all">Найдено <span class="js_elastic_total"></span> результатов, показать все</a>
        </div>
    </div>
</div>

<section class="top-title result">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a href="<?php echo $back; ?>" class="back_link">
                    <i class="icon icon-back"></i>
                    <span>Назад</span>
                </a>
                <h1 class="js_keyword"></h1>
            </div>
        </div>
    </div>
</section>

<div id="overlay" style="display: none;"></div>