<div class="container fastSearch unset-pad">

	<h2 class="fastSearch__title"><?php echo $heading_title; ?></h2>

	<form method="post" action="<?php echo $siteBase; ?>" id="form__filters" autocomplete="off">
		<!-- Selects form -->
		<div class="fastSearch__form">
			<div id="content"></div>

			<!-- Marks select -->
			<div class="fastSearch__block fastSearch__mark">
				<select name="filter[]" id="marks" class="data-select_id">
					<option value="" selected="">Марка</option>
					<?php foreach ($marks as $mark): ?>
						<option value="<?php echo $mark['keyword'] ?>" id="<?php echo $mark['filter_id'] ?>"><?php echo $mark['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<!-- Marks select END -->

			<!-- Models select -->
			<div class="fastSearch__block fastSearch__model">
				<select name="filter[]" id="models" class="data-select_id">
					<option value="" selected="">Модель</option>
				</select>
			</div>
			<!-- Models select END -->

			<!-- Generation select -->
			<div class="fastSearch__block fastSearch__generation">
				<select name="filter[]" id="generation" class="data-select_id">
					<option value="" selected="">Поколение</option>
				</select>
			</div>
			<!-- Generation select END -->

			<!-- Fast_search select -->
			<div class="fastSearch__block fastSearch__search">
				<select id="filterSearch__categories" data-placeholder="Выберите делать..." class="chosen-select">
					<option value="">Выберите делать...</option>
					<?php foreach ($allCategories as $category): ?>
					<option value="<?php echo $category['href'] ?>"><?php echo $category['name']; ?></option>
					<?php endforeach; ?>
				</select>
				<input type="submit" class="fastSearch__button button-filter" id="filter-search_button" value="Фильтровать">
				<button type="button" class="fastSearch__buttonSearch"></button>
			</div>
			<!-- Fast_search select END -->

		</div>
		<!-- Selects form END -->

		<!-- Button extended_filters -->
		<div id="extended-div" style="cursor: not-allowed; display: inline-block;">
			<a href="javascript:void(0)" id="filter-search_extended" class="fastSearch__more" style="pointer-events: none">Расширенный поиск...</a>
		</div>
		<!-- Button extended_filters END -->

		<!-- begin container -->
		<div class="container unset-pad">

			<!-- begin filters  -->
			<div class="filters filters-main">

				<!-- begin filters-cat-close -->
				<div class="filters-cat-close">
					<button type="button"><i class="fa fa-times"></i></button>
				</div>
				<!-- end filters-cat-close -->

				<div class="filter__blocksWrap">
					<div id="extended_filters-ajax"></div>
				</div>

				<!-- begin filters-main__reset -->
				<div class="filters-main__reset">

					<!-- begin filters-main__links -->
					<div class="filters-main__links" id="filter_status">
					</div>
					<!-- end filters-main__links -->

					<!-- begin filters-main__resetBuuton -->
					<div class="filters-main__resetBuuton">
						<button type="button" id="extended_filters__resetButton">Сбросить фильтр</button>
					</div>
					<!-- end filters-main__resetBuuton -->

				</div>
				<!-- end filters-main__reset -->

				<!-- Count product with accept filters -->
				<div class="filters-main__bottom">

					<!-- begin filters__main__result -->
					<div class="filters-main__result">
						<p>Надено <span class="filters-main__count" id="massSum">0</span> запчастей</p>
					</div>
					<!-- end filters__main__result -->

					<!-- begin filter__button -->
					<div class="filter__button">
						<input type="submit" class="filters-btn" id="filterSearch_button-filter" value="Применить фильтр">
					</div>
					<!-- end filter__button -->

				</div>
				<!-- Count product with accept filters END -->

			</div>
			<!-- end filters -->

		</div>
		<!-- end container -->
	</form>

</div>



<script src="/catalog/view/javascript/filterSearch.js"></script>