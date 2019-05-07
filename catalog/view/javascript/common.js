
function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {


	//slick slider init
	// $('.slider').slick({
	// 	dots: true,
	// 	autoplay: false,
	// 	autoplaySpeed: 3000,
	// 	speed: 700,
	// 	arrows: false
	// });

	$('.slider_recomend').slick({
		dots: false,
		autoplay: false,
		speed: 700,
		infinite: false,
		slidesToShow: 5,
		slidesToScroll: 1
	});

	$('.listing--several_img').slick({
		dots: true,
		autoplay: false,
		speed: 700,
		infinite: false,
		arrows: false
	});



	$('.icon-info_close').click(function () {
		$('.info-wrap.aditional').hide();
	});
	$('.icon-info').click(function () {
		$('.info-wrap').show();
	});
	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').val($(this).attr('name'));

		$('#form-language').submit();
	});



	$('.srch__field').keyup(function(e){


		let keywords = $(e.target).val();
		if(keywords ===''){

			$('.srch__result').empty();
			$('.js_keyword').empty();
		} else {
			if(keywords.length >= 1){

				$.ajax({
					url: $('base').attr('href') + 'index.php?route=extension/module/autosearch/elasticSearch&keyword=' + keywords,
					dataType: 'json',
					beforeSend: function (xhr) {
						$('#load-search').show();
					},
					success: function (result) {
						console.log(result);
						var count_product = 0;
						var count_category = 0;
						var products = {};
						var categories = {};
						$.each(result,function(index,value){
							if(value.tag == 'product'){
								products[index] = value;
								count_product++;
							}else if(value.tag == 'category'){
								categories[index] = value;
								count_category++;
							}
						});
							if(count_product == 0 && count_category == 0){
								$('.go_to_search').hide();
							}else {
								$('.go_to_search').show();
							}

						$('.js_keyword').text('Результаты по запросу "' + keywords + '"');
						$('.js_elastic_total_product').text(count_product);
						$('.js_elastic_total_category').text(count_category);
						$('.all_result').show();
						$('.dropdown_search').removeClass('scroll');
						$('.dropdown_search').show();
						$('.js_result_custom').show();
						$('.srch__result').empty();
						$('.srch__result_cat').empty();

						let ul = $('.srch__result');

                        var count_res_cat = 0;
                        $.each(categories,function(index,value){

                            if (count_res_cat < 6) {
                                let li = document.createElement('li');
                                li.className = 'srch__link';

                                let a = document.createElement('a');
                                a.setAttribute('href', value.href);
                                a.text = value.name;
                                li.append(a);
                                ul.append(li);
                            }
                            count_res_cat++;
                        });

						var count_res_prod = 0;
						$.each(products,function(index,value){
							if (count_res_prod < 6) {
								let li = document.createElement('li');
								li.className = 'srch__link';

								let a = document.createElement('a');
								a.setAttribute('href', value.href);
								a.text = value.name;
								li.append(a);
								ul.append(li);
							}
							count_res_prod++;
						});





					}
				});
			}

		}

	});
	$('.srch__field_2').keyup(function(e){

		let keywords = $(e.target).val();
		if(keywords ===''){

			$('.srch__result').empty();
			$('.js_keyword').empty();
		} else {
			if(keywords.length >= 1){

				$.ajax({
					url: $('base').attr('href') + 'index.php?route=extension/module/autosearch/elasticSearch&keyword=' + keywords,
					dataType: 'json',
					beforeSend: function (xhr) {
						$('#load-search').show();
					},
					success: function (result) {
						console.log(result);
						var count_product = 0;
						var count_category = 0;
						var products = {};
						var categories = {};
						$.each(result,function(index,value){
							if(value.tag == 'product'){
								products[index] = value;
								count_product++;
							}else if(value.tag == 'category'){
								categories[index] = value;
								count_category++;
							}
						});
						if(count_product == 0 && count_category == 0){
							$('.go_to_search').hide();
						}else {
							$('.go_to_search').show();
						}

						$('.js_keyword').text('Результаты по запросу "' + keywords + '"');
						$('.js_elastic_total_product').text(count_product);
						$('.js_elastic_total_category').text(count_category);
						$('.all_result').show();
						$('.js_result_custom').show();
						$('.srch__result_2').empty();


						let ul = $('.srch__result_2');

						var count_res_cat = 0;
						$.each(categories,function(index,value){

							if (count_res_cat < 6) {
								let li = document.createElement('li');
								li.className = 'srch__link';

								let a = document.createElement('a');
								a.setAttribute('href', value.href);
								a.text = value.name;
								li.append(a);
								ul.append(li);
							}
							count_res_cat++;
						});

						var count_res_prod = 0;
						$.each(products,function(index,value){
							if (count_res_prod < 6) {
								let li = document.createElement('li');
								li.className = 'srch__link';

								let a = document.createElement('a');
								a.setAttribute('href', value.href);
								a.text = value.name;
								li.append(a);
								ul.append(li);
							}
							count_res_prod++;
						});
					}
				});
			}

		}

	});

	var cl = console.log;

	$('.srch__field').focus(function () {
		$('#overlay').show();

	});
	$('.srch__field_2').focus(function () {
		$('#overlay').show();

	});
	// $('.srch__field').blur(function () {
	// 	$('#overlay').hide();
	// 	$('.dropdown_search').fadeOut();
	// 	// $('.srch__field').val('')
	// });

	$('.js_close').click(function () {
		$('.dropdown_search').hide();
		$('.srch__field').val('')
		$('.srch__result').empty();
		$('.srch__field').val('')
		$('#overlay').hide();
	});
	$('.search-wrap .icon-delete').click(function () {
		$('.srch__field_2').val('')
		$('.srch__result_2').empty();
		$('#overlay').hide();
	});


	$('.go_to_search').click(function () {
		$('#custom_search_button').trigger('click');

		});

	$('.go_to_search_cat').click(function () {

		let keywords = $('.srch__field').val();
		if(keywords ===''){

			$('.srch__result').empty();
			$('.js_keyword').empty();
		} else {
			if(keywords.length >= 2){
				$.ajax({
					url: $('base').attr('href') + 'index.php?route=extension/module/autosearch/elasticSearch&keyword=' + keywords,
					dataType: 'json',
					success: function (result) {
						console.log(result);
						var count_product = 0;
						var count_category = 0;
						var products = {};
						var categories = {};
						$.each(result,function(index,value){
							if(value.tag == 'product'){
								products[index] = value;
								count_product++;
							}else if(value.tag == 'category'){
								categories[index] = value;
								count_category++;
							}
						});

						$('.go_to_search').hide();

						$('.js_elastic_total_category').text(count_category);
						$('.dropdown_search').show();
						$('.dropdown_search').addClass('scroll');
						$('.js_result_custom').show();
						$('.all_result').hide();
						$('.srch__result').empty();
						$('.srch__result_cat').empty();
						$('.del').remove();
						let ul_c = $('.srch__result_cat');

						if(count_category != 0){
							$( "<h2 class='del'>" + count_category + " Категорий</h1>").insertBefore( ul_c );
						}

						$.each(categories,function(index,value){

								let li = document.createElement('li');
								li.className = 'srch__link';

								let a = document.createElement('a');
								a.setAttribute('href', value.href);
								a.text = value.name;
								li.append(a);
								ul_c.append(li);

						});

					}
				});
			}

		}
	});




	/* Search */
	$('.srch input[name=\'search\']').parent().parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('.srch input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location  = url;


	});

	$('.srch input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('.srch input[name=\'search\']').parent().parent().find('button').trigger('click');
		}
	});


	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('header #search input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header #search input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 10) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
		$('#content .product-grid > .clearfix').remove();

		$('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
		$('#grid-view').removeClass('active');
		$('#list-view').addClass('active');

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
		// What a shame bootstrap does not take into account dynamically loaded columns
		var cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		$('#list-view').removeClass('active');
		$('#grid-view').addClass('active');

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
		$('#list-view').addClass('active');
	} else {
		$('#grid-view').trigger('click');
		$('#grid-view').addClass('active');
	}

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});
});

// Cart add remove functions
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {

				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					//$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                        $('#cart-mobile').html('<span id="cart-total-mobile"><i class="fa fa-shopping-cart"></i> <span id="count-mobile">' + json['total'] + '</span></span>');
                        $('#count-mobile').html(json['total']);
                        $('.cart-count').html(json['total']);
						console.log("update cart");
					}, 100);

					$('.cart-count').html(json['total']);

				//	$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#modal-cart').load('index.php?route=common/cart/info .test', function () {



						$('#modal-cart .bt').on('click', function(e){
							e.preventDefault();

							$('#modal-cart').slideToggle(200);
						});
					});


				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#modal-cart').load('index.php?route=common/cart/info .test', function () {

						$('#modal-cart .bt').on('click', function(e){
							e.preventDefault();

							$('#modal-cart').slideToggle(200);
						});
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {

		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {

				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('.cart-count').html(json['total']);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#modal-cart').load('index.php?route=common/cart/info .test', function () {




						$('#modal-cart .bt').on('click', function(e){
							e.preventDefault();

							$('#modal-cart').slideToggle(200);
						});
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

function urlGeneration(category, mark, model, generation, inputs)
{
	let url = '';

	// Adding `category` to url.
	if (category !== '') {
		url += 'catalog/' + category;
	}

	// Adding `mark` to url.
	if (mark !== '') {
		url += ((category !== '') ? '/' + mark : 'zapchasti/' + mark);
	}

	// Adding `model` to url.
	if (model !== '') {
		url += '/' + model;
	}

	// Adding `generation` to url.
	if (generation !== '' ) {
		url += '/' + generation;
	}

	// Adding `inputs` to url.
	if (!jQuery.isEmptyObject(inputs)) {
		url += '&filter=' + inputs.join(',');
	}


	return url;
}




// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);
