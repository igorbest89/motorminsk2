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

      </div>
    </div>
  </div>
</section>

<section class="listing">
  <div class="container">
    <div class="row">

      <div class="col-lg-4 col-md-4 col-sm-5">
        <div class="all_category">
          <a href="<?php echo $catalog; ?>">Все категории</a>
          <i class="icon icon-sandwich"></i>
        </div>
        <div id="allCat" class="menu-wrapper">
          <div class="first">
            <input id="match-search" type="text" placeholder="Поиск по категориям" />
            <i class="icon icon-search-2"></i>
          </div>
          <div class="category_menu">
            <ul>
              <li id="not">
                <a href="#">Совпадений не найдено!</a>
              </li>
              <?php foreach($parent_categories as $category){ ?>
              <li>
                <a href="<?php echo $category['href'] ?>"><?php echo $category['name'] ?></a>
                <?php if(isset($category['sub_categories'])){ ?>
                <i class="icon icon-arrow"></i>
                <?php  echo recursive($category['sub_categories']) ?>
                <?php } ?>
              </li>
              <?php } ?>
            </ul>
          </div>
          <div class="last">
            <a href="<?php echo $catalog ?>">Смотреть весь каталог</a>
          </div>
        </div>


        <?php
                function recursive ($categories){
                     $tree = '<ul class="category_menu-sub_menu">';
        foreach($categories as $category){
        $tree .= '<li>';
          $tree .= '<a href="'.$category['href'] .'">'. $category['name'] .'</a>';
          if(isset($category['sub_categories'])){
          $tree .= '<i class="icon icon-arrow"></i>';
          $tree .=  recursive($category['sub_categories']);
          }
          $tree .= '</li>';
        }
        $tree .= '</ul>';
        return $tree;
        }
        ?>


        <?php if(isset($categories)){ ?>
        <ul class="category_menu">
          <?php foreach($categories as $category){ ?>
          <li>
            <a href="<?php echo $category['href'] ?>"><?php echo $category['name'] ?></a>
            <?php if(isset($category['sub_categories'])){ ?>
            <i class="icon icon-arrow"></i>
            <?php  echo recursive($category['sub_categories']) ?>
            <?php } ?>
          </li>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>



      <div class="col-lg-8 col-md-8 col-sm-7">
        <div class="sorting">
          <span>Сортировать по:</span>
          <form action="#">
            <select id="input-sort" class="form-control" onchange="location = this.value;">
              <?php foreach ($sorts as $sorts) { ?>
              <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
              <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>

          </form>
          <div class="listing-icon_wrap">
            <i id="listing_string" class="icon icon-listing_string active"></i>
            <i id="listing_card" class="icon icon-listing_card"></i>
          </div>
        </div>
        <div class="listing-wrap clearfix catcards">
          <?php if ($products) { ?>
          <?php foreach ($products as $product) { ?>
          <div class="listing-item listing-item--string clearfix product-list">
            <div class="listing-item_img">
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="product img"></a>
            </div>
            <div class="listing-item_descr">
              <a href="<?php echo $product['href']; ?>"><h3><?php echo $product['name']; ?></h3></a>
              <?php if ($product['price']) { ?>
              <div class="price">
                <em>Цена с НДС</em><strong><?php echo $product['price']; ?></strong>
              </div>
              <?php } ?>
              <?php if($product['manufacturer'] != ''){ ?>
              <div class="producer">
                <em>Производитель</em><span><?php echo $product['manufacturer']; ?></span>
              </div>
              <?php } ?>
              <?php if($product['weight'] != ''){ ?>
              <div class="weight">
                <em>Масса</em><span><?php echo $product['weight']; ?></span>
              </div>
              <?php } ?>
              <!-- <?php if (isset($technical_attributes)){ ?>
                       <?php foreach($technical_attributes['attribute'] as $attribute){ ?>
                           <div class="press">
                               <em><?php echo $attribute['name'] ?></em><span><?php echo $attribute['text'] ?></span>
                           </div>
                       <?php } ?>
                   <?php } ?> --!>

            </div>
            <?php if((int)$product['price'] > 0){ ?>
            <i onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');" class="icon icon-add_to_cart"></i>
            <?php } ?>
          </div>
          <?php } ?>
          <?php } ?>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left" style="display:none;"><?php echo $pagination; ?></div>
        </div>
      </div>
    </div>
  </div>
  <?php if ($loadmore_arrow_status) { ?>
  <a id="arrow_top" style="display:none;" onclick="scroll_to_top();"></a>
  <?php } ?>
  <div id="load_more" style="display:none;">
    <div class="row text-center" style="display:none;">
      <a href="#" class="load_more">Подгрузить ещё</a>
    </div>
  </div>
  <script type="text/javascript"><!--


    $('.last-item').click(function () {
      $('.category_menu li').removeAttr('hidden');
      $(this).remove();
    })

    $('.category_menu li').hover(function () {
      $('.category_menu-sub_menu li').show();
    });
    --></script>
</section>
<style>
  a.load_more {
    display:inline-block; margin:0 auto 20px auto; padding: 0.5em 2em; border: 1px solid #069; border-radius: 5px; text-decoration:none; text-transform:uppercase;
  }
</style>
<style>
  #ajax_loader {
    width: 100%;
    height: 30px;
    margin-top: 15px;
    text-align: center;
    border: none!important;
  }
  #arrow_top {
    background: url("image/catalog/chevron_up.png") no-repeat transparent;
    background-size: cover;
    position: fixed;
    bottom: 50px;
    right: 15px;
    cursor: pointer;
    height: 50px;
    width: 50px;
  }
</style>
<div id="overlay" style="display: none;"></div>
<?php echo $footer; ?>