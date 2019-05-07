$(document).ready(function() {
	var timerId;
	var timerId2;
	var menuItemsHidden = $('#mainPgCat').children('li');
	var menuItems = $('.cat-item ul, .cat ul').children('li');
	var menuTxtItem = menuItems.children('a');
	var lastItem = $('.category_menu, .category_menu > ul').children('.last-item');
	var hiddenItem = $('.category_menu, .category_menu > ul').children('#not');
	var lastItemLink = lastItem.children('a');
	var lastItemIcon = lastItem.children('i');
	var menuSubItem = menuItems.find('.category_menu-sub_menu');
	var inputTypeRadio = $('.cart input[type="radio"]');
	var contactFormBtn = $('.contact-form_wrap form button');
	var close = $('.close_popup, #overlay, .cancel_btn');
	var modal = $('.popup');
	var popupOverlay = $('#overlay');
	var openPopupBtn = $('.popup_open');
	var wLocation = window.location;
	var mainLocation = 'http://bereg.intent-solutions.com/';

	//form styler init
	$('input, select').styler();

	//slick slider init
	$('.slider').slick({
		dots: true,
		autoplay: false,
		autoplaySpeed: 3000,
		speed: 700,
		arrows: false
	});

	//mobile slider in product
	if($(window).width() < 992) {

		$('.slider-mobile').slick({
			dots: false,
			autoplay: false,
			autoplaySpeed: 3000,
			speed: 700,
			arrows: false,
			slidesToShow: 4,
			infinite: true,
			slidesToScroll: 1,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1
					}
				}
			]
		});

	}

	//custom scrollbar
	$('#mobileAllCat .category_menu, #allCat .category_menu .custom_layer_cart, .modal-cart .modal-cart_body, .ready_order .order_body').mCustomScrollbar();

	// all cat view
	$('body[class*="product"] .all_category').on('click', function(e) {
		e.preventDefault();
		
		popupOverlay.fadeToggle(400);
		$('#allCat').fadeToggle(400);
		$(this).css('z-index','99');

	});

	$('.modal .modal-header .close').on('click', function(e) {
		popupOverlay.fadeOut(400);
	});
	// $('.modal .popup_bottom .bt').on('click', function(e) {
	// 	e.preventDefault();
	// 	popupOverlay.fadeOut(400);
	// 	$('#successModal').css('display','none').removeClass('in');
	// });
	$('.add_to_cart-wrap').on('click', function(e) {
		popupOverlay.fadeIn(400);
	});

	popupOverlay.on('click', function(e) {
		console.log("overlay");
		$('#allCat').css('display','none');
		$('.search_wrap .dropdown_search').css('display','none');
		$('.search_wrap .srch__field').val(' ');
		$('.search_wrap .srch__field').closest('.top-banner-form').css('z-index','10');
		$('body[class*="product"] .all_category').css('z-index','10');
		$('#mobile-fixed-menu .tabs_list').fadeOut(500);
		$('#cartModal').hide();
		// $('#cartModal').css('display','none').removeClass('in');
		// $('#successModal').css('display','none').removeClass('in');

	});

	$('.search_wrap .srch__field').on('focus', function(e) {
		
		$(this).closest('.top-banner-form').css('z-index','99');

	});

	if(localStorage.getItem('display')==null)
	{
        localStorage.setItem('display', 'list');
	}

	//listing view
	$('#listing_string').on('click', function(e) {
		var _this  = $(this);
		var parent = _this.closest('.listing');

		_this.addClass('active').siblings().removeClass('active');

		parent.find('.listing-item').addClass('listing-item--string');
		parent.find('.listing-item').removeClass('product-grid');
		localStorage.setItem('display', 'list');
	});

	$('#listing_card').on('click', function(e) {
		var _this  = $(this);
		var parent = _this.closest('.listing');

		_this.addClass('active').siblings().removeClass('active');

		parent.find('.listing-item').removeClass('listing-item--string');
		parent.find('.listing-item').addClass('product-grid');
		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#listing_string').trigger('click');
		$('#listing_string').addClass('active');
	} else {
		$('#listing_card').trigger('click');
		$('#listing_card').addClass('active');
	}

	//Tabs mobile
	if($(window).width() < 768) {

		$('#mobile-fixed-menu .tabs_control_link').on('click', function(e) {
			e.preventDefault();

			var container = $(this).closest('#mobile-fixed-menu');
			var item = $(this).closest('.tabs_control_item');
			var itemActive = container.find('.tabs_control_item.active');
			var contentWrap = $(this).closest('.tabs').find('.tabs_list');
			var contentItem = $(this).closest('.tabs').find('.tabs_item');
			var curIndex = item.index();
			var newIndex = itemActive.index();

			clearTimeout(timerId);
			timerId = setTimeout(showMobMenu, 200);

			function showMobMenu(e) {

				contentItem.eq(curIndex)
					.addClass('active')
					.siblings()
					.removeClass('active');

				item
					.addClass('active')
					.siblings()
					.removeClass('active');

				if ( curIndex == newIndex ) {

					if ( curIndex == 0 ) {

						contentWrap.fadeOut(300);
						popupOverlay.fadeOut(400);

					} else {

						contentWrap.fadeToggle(300);
						popupOverlay.fadeToggle(400);

					}

				} else {

					if ( curIndex == 0 ) {

						contentWrap.fadeOut(300);
						popupOverlay.fadeOut(400);

					} else {

						contentWrap.fadeIn(300);
						popupOverlay.fadeIn(400);

					}

				}

			}

			if ( wLocation != mainLocation && curIndex == 0 ) {

				window.location.href = mainLocation;

			}

		});

		$('#mobile-fixed-menu .close').on('click', function(e) {
			e.preventDefault();

			var _this = $(this);
			var container = _this.closest('.tabs');
			var content = container.find('.tabs_list');
			var navItem = container.find('.tabs_control_item');

			content.fadeOut(500);

			navItem.removeClass('active');
			popupOverlay.fadeOut(400);

		});

	}

	//Tabs
	$('.tabs_control_link').on('click', function(e) {
		e.preventDefault();

		var item = $(this).closest('.tabs_control_item');
		var contentItem = $(this).closest('.tabs').find('.tabs_item');
		var itemPosition = item.index();

		contentItem.eq(itemPosition)
			.addClass('active active-anim')
			.siblings()
			.removeClass('active active-anim');

		item
			.addClass('active')
			.siblings()
			.removeClass('active');

	});

	//Contact toggle
	$('.contact-item h3').on('click', function(e) {
		e.preventDefault();

		var _this = $(this);
		var item  = _this.closest('.contact-item');
		var inner = _this.next('.contact-item_inner, .jobs-item_inner');

		item.toggleClass('active');
		inner.slideToggle(500);

	});

	//Popup
	openPopupBtn.on('click', function(e) {
		e.preventDefault();
		$('#ajax_loader').remove();
		$('#ajax_error').remove();

		var popupTxt = $(this).closest('.contact-item').find('h3 span').text();
		var locationId = $(this).attr('data-id');
		var type = $(this).attr('data-type');
		console.log(type)
		var div = $(this).attr('href');
		var currentPopup = $(this).closest('.popup');

		if(!!currentPopup && currentPopup.css('display') == 'block') {
			currentPopup.css('display', 'none');
		}

		var div = $(this).attr('href');
		popupOverlay.fadeIn(400,
			function(){
				$(div)
					.css('display', 'block');
		});

		modal.find('p').text(popupTxt);
		modal.find('.hidden_id').val(locationId);
		modal.find('.hidden_type').val(type);
    });

	close.click( function(e) {
		$('.dropdown_search').fadeOut();
        e.preventDefault();
        modal.css('display', 'none');
        popupOverlay.fadeOut(400);
    });

	$('#send_email_btn').on('click', function (e) {
		e.preventDefault();
		var email_form = $('#form_email').serialize();
		var email = $('#form_email').find('.send_email').val();

		if(isEmail(email)){
			var request = $.ajax({
				type: 'post',
				url: 'index.php?route=information/contact/ajaxPost',
				dataType: 'json',
				data: email_form
			});
			request.done(function (response) {
				$('.danger').hide();
				window.location.href = response;
			})
		}else{
			$('.danger').show()
		}

	})


	$('#send_sms_btn').on('click', function (e) {
		$('#ajax_loader').remove();
		$('#ajax_error').remove();
		e.preventDefault();
		var sms_form = $('#form_phone').serialize();
		if($('.send_sms').val() !== ''){
			var request = $.ajax({
				type: "POST",
				url: 'index.php?route=information/contact/ajaxPost',
				dataType: 'json',
				data: sms_form,
				beforeSend: function () {
					$('#send_sms_btn').after('<div id="ajax_loader"><img src="/image/catalog/ajax-loader-horizontal.gif" /></div>')
				},
			});
			request.done(function (response) {
				if(response.error === 0 ){
					$('#ajax_loader').remove();
					window.location.href = response.href;
				}else{
					$('#send_sms_btn').after('<p id="ajax_error" style="color: rgba(159,0,0,0.73)">отправка не удалась</p>')
					setTimeout(function () {
						$('.close_popup').trigger('click')
					},2000)
				}

			})
		}
	})





	function isEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}



	//show hidden category
	menuItemsHidden.each(function(i,elem) {
		if ( i >= 20 ) {

			$(this).hide();

			if ( $(this).hasClass('last-item') ) {
				$(this).show();
				lastItem.addClass('item-hidden');
			}

		}
	});

	lastItem.on('click', function(e) {
		e.preventDefault();
		// console.log($(this))
		var _this = $(this);

		menuItemsHidden.each(function(i,elem) {

			if ( i >= 20 ) {
				if ( _this.hasClass('item-hidden') ) {
					$(this).show();
					
					if ( $(this).hasClass('item-hidden') ) {

						_this.removeClass('item-hidden');
						lastItemLink.text('Скрыть');
						lastItemIcon.css('transform', 'rotate(180deg)');

					}

				}
				else {
					$(this).hide();

					if ( $(this).hasClass('last-item') ) {

						_this.addClass('item-hidden').show();
						lastItemLink.text('Показать все категории');
						lastItemIcon.css('transform', 'rotate(360deg)');

					}
					
				}

			}

		});

	});

	//open dropdown menu with interval
	menuItems.on('mouseenter', function(e) {
		var $this = $(this);
		clearTimeout(timerId);

		timerId = setTimeout(showMenu, 200);

		function showMenu() {

			if($(window).width() >= 992) {

				if ( $this.closest('#allCat').length != 0 ) {

					var clonedSubItem = $this.find(menuSubItem).first().clone(true);
					var innerLi = clonedSubItem.find('li');
					var menuPos = $('#allCat').offset().top;
					var itemPos = $this.offset().top;

				 	if ( $this.closest('.category_menu-sub_menu').length == 0 ) {
						var container = $this.closest('.category_menu');
						var	clonedSub = container.children('.category_menu-sub_menu');

						clonedSub.remove();

					}
					
					clonedSubItem.offset({top:itemPos-461, left:326});
					$this.closest('.category_menu').prepend(clonedSubItem);

					clonedSubItem.css(
						{
							'display':'block',
						}
					);

					innerLi.on('mouseenter', function(e) {

						$(this).find('>.category_menu-sub_menu').css(
							{
								'display':'block',
								'top': '0',
								'left': '326px',
							}
						);

					})
					.on('mouseleave', function(e){

						$(this).find('>.category_menu-sub_menu').css(
							{'display':'none'});

					});

				} else {

				$this.siblings()
					.find(menuSubItem)
					.css('display','none');

				$this.children(menuSubItem).css('display','block');

				}
			}

		}

	});

	menuItems.on('mouseleave', function(e){
		var $this = $(this);
		clearTimeout(timerId);

		timerId = setTimeout(hideMenu, 300);

		function hideMenu() {

			if($(window).width() >= 992) {

				if ( $this.closest('#allCat').length != 0 ) {
					var container = $this.closest('.category_menu');
					var	clonedSub = container.find('.category_menu-sub_menu').first();
					var	clonedSubLi = clonedSub.find('li');

					clonedSub.remove();

				} else {

				$this.siblings()
					.find(menuSubItem)
					.css('display','none');

				$this.find(menuSubItem).css('display','none');

				}
			}

		}

	});

	if($(window).width() < 992) {

		menuItems.find('i').on('click', function(e) {
			$(this).toggleClass('rotate');
			$(this).closest('li').children('.category_menu-sub_menu').slideToggle().toggleClass('rotate');
		});

	}

	// //category match search
	// $('#match-search').on('focus', function(e){
	// 	var _this = $(this),
	// 		parent = _this.closest('.first'),
	// 		child = parent.find('i');
	//
	// 	child.hide();
	//
	// });

	$('#match-search').on('blur', function(e){
	 // $('#append_ul').find('li').remove();
		$('#match-search').val('');

	});


	$('#match-search').on('keyup', function(e){

		$('#append_ul').find('li').remove();

		e.preventDefault();
		var _this = $(this),
			val = _this.val();

			menuTxtItem.each(function(index) {
				var currItem = $(this);

				if ( currItem.closest('.sub-cat').length == 0 ) {
					var txt = currItem.text();
					var replacedTxt;
					var arr = [];
					arr.push(txt);
					var matchTxt = arr.join(' ');
					var result = matchTxt.match( new RegExp(val,'i') );
					console.log(result);
					var markedLetter = '<span class="marked">' + val + '</span>';

					var newstr = txt.replace(result, markedLetter);
					currItem.html(newstr);
					if ( Array.isArray(result)) {
						let temp = currItem.closest(menuItems).clone();
						// temp.html(newstr);
						temp.appendTo('#append_ul');

					}
				}
			});
	});





	$('#match-search_manufacturers').on('keyup', function(e){

		$('#append_ul').find('li').remove();

		e.preventDefault();
		var _this = $(this),
			val = _this.val();
		if(val === ''){
            $('#append_ul').find('li').remove();
        }

		var menuTxtItemMarks = $('.brand_list-item, .brand_list-item ul li').children('a');
		menuTxtItemMarks.each(function(index) {
			    var currItem = $(this);
				var txt = currItem.text();
				var arr = [];
				arr.push(txt);
				var matchTxt = arr.join(' ');
				var result = matchTxt.match(new RegExp(val, 'i'));
				var markedLetter = '<span class="marked">' + val + '</span>';
				var newstr = txt.replace(result, markedLetter);

				if (Array.isArray(result)) {
					let temp = currItem.clone();
					temp.html(newstr);
					var li = $('<li/>');
					temp.appendTo(li)
					li.appendTo('#append_ul');
				}
		});
	});

















	//availability limit
	$('#productLimit').on('change', function(e){
		var _this = $(this);
		var val = parseInt(_this.val());
		var available = $('#productAvailability').text();

		if ( val >= available) {
			return _this.val(available);
		} else if ( val <= 1) {
			return _this.val(1);
		}

	});

	//select text of checked radio button
	inputTypeRadio.each(function(index) {
		var _this = $(this);
		var checked = _this.is(':checked');

		if ( checked ) {
			_this.closest('p').find('span').css({fontWeight: 500});
		}

	});

	$('.cart input[type="radio"]').on('click', function(e){
		var _this = $(this);
		var container = _this.closest('p');
		var siblings = container.siblings();

		container.find('span').css({fontWeight: 500});
		siblings.find('span').css({fontWeight: 300});

	});

	// password visibility
	$('.login .icon-visible').on('click', function(e){
		var _this = $(this);
		var container = _this.closest('label');
		var input = container.find('input');
		var attr = input.attr('type');

		if (attr == 'password') {

			input.attr('type', 'text');
			_this.addClass('icon-visible-2');

		} else {

			input.attr('type', 'password');
			_this.removeClass('icon-visible-2');

		}

	});

	// input validation
	$('.cart form input').on('blur', function(e){
		var _this = $(this);
		
		fieldValidation(_this);

	});

	// contact form validation
	$('.contact-form_wrap form input').on('blur', function(e){
		var _this = $(this);
		
		fieldValidation(_this);

	});

	contactFormBtn.on('click', function(e){
		var container = contactFormBtn.closest('.contact-form_wrap'),
			inputs = container.find('input[type=text]'),
			email = $('#inputEmail'),
			res = contactFormValidation();

		function contactFormValidation() {
			var arr = [];

			inputs.each(function(index) {
				_this = $(this),
				value = _this.val();

				if ( value == '' ) {

					arr.push(value);
					fieldValidation(_this);

				}

			});

			if ( arr.length > 0 || email.hasClass('error') ) {

				boolean = false;

			} else {

				boolean = true;

			}

			return boolean;
		}

		if (res) {

			contactFormBtn.submit();

		} else {

			return false;

		}

	});

});

// simple validation email
function validateEmail(email) {
	var re = /\S+@\S+\.\S+/;
	return re.test(email);
}

// field validation
function fieldValidation(_this) {
	var _this,
		value = _this.val(),
		label = _this.closest('label'),
		statusWarning = _this.siblings('.status--warning'),
		addStatusWarning = '<span class="status status--warning">\
							<i class="icon icon-inp_warning"></i>\
							<span>Поле не заполнено</span>\
						</span>',
		addStatusError = '<span class="status status--error">\
							<i class="icon icon-inp_error"></i>\
							<span>Неверно указан E-mail</span>\
						</span>',
		email = label.find('#inputEmail'),
		emailValue = email.val(),
		statusError = email.siblings('.status--error');

	//warning empty fields
	if (value == '') {

		_this.addClass('warning');
		label.append(addStatusWarning);
		statusWarning.remove();

	} else {

		_this.removeClass('warning');
		statusWarning.remove();

	}

	// email error
	if ( value != '' && emailValue != undefined && !validateEmail(emailValue) ) {

		_this.addClass('error');
		label.append(addStatusError);
		statusError.remove();

	} else {

		_this.removeClass('error');
		statusError.remove();

	}
}


// var pagination_exist = true; // оставить пагинацию и добавить кнопку
// var button_more = true; // наличие кнопки "загрузить ещё"
// var top_offset = 100; // высота отступа от верха окна, запускающего arrow_top
// var window_height = 0; // высота окна
// var product_block_offset = 0; // отступ от верха окна блока, содержащего контейнеры
//
// var product_block = ''; // определяет div, содержащий товары
// var pages_count = 0; // счетчик массива ссылок пагинации
// var pages = []; // массив для ссылок пагинации
// var waiting = false;
//
// function getNextProductPage(pages, pages_count) {
//
// 	console.log(pages)
// 	console.log(pages_count)
//
// 	if (waiting) return;
// 	if (pages_count >= pages.length) return;
// 	waiting = true;
// 	$(product_block).parent().after('<div id="ajax_loader"><img src="/image/catalog/ajax-loader-horizontal.gif" /></div>');
// 	$.ajax({
// 		url:pages[pages_count],
// 		type:"GET",
// 		data:'',
// 		success:function (data) {
// 			$data = $(data);
// 			$('#ajax_loader').remove();
// 			if ($data) {
// 				if ($data.find('.product-list').length > 0)    {
// 					$(product_block).parent().append($data.find('.product-list').parent().html());
// 					if (product_block == '.product-list') {
// 						if ($('#listing_string').hasClass('active')) {
// 							$('#listing_string').trigger('click')
// 						} else {
// 							$('.product-list').removeClass('listing-item--string')
// 							$('#listing_cart').trigger('click')
// 						}
//
// 					};
// 				} else {
// 					$(product_block).parent().append($data.find('.product-grid').parent().html());
// 					if (product_block == '.product-grid') {$('#listing_card').trigger('click')};
// 				}
// 				if (pagination_exist) {
// 					$('.pagination').html($data.find('.pagination'));
// 				}
// 				// $('script').each(function(){eval($(this).text())});
// 			}
// 			waiting = false;
//
// 		}
// 	});
// 	if (pages_count+1 >= pages.length) {$('.load_more').hide();};
// }
//
// function scroll_to_top() {
// 	$('html, body').animate({
// 		scrollTop: 0
// 	}, 300, function() {
// 		$('.arrow_top').remove();
// 	});
// }
//
// function getProductBlock() {
// 	if ($('#listing_string').hasClass('active')) {
// 		product_block = '.product-list';
// 	} else {
// 		product_block = '.product-grid';
//
// 	}
// 	return product_block;
// }
//
// $(document).ready(function(){
// 	window_height = $(window).height();
// 	product_block = getProductBlock();
// 	var button_more_block = $('#load_more').html(); //
// 	var arrow_top = $('#arrow_top'); //
// 	if ($(product_block).length > 0) {
// 		product_block_offset = $(product_block).offset().top;
// 		var href = $('.pagination_ul').find('li:last a').attr('href');
//
// 		$('.pagination_ul').each(function(){
// 			if (href) {
// 				TotalPages = href.substring(href.indexOf("page=")+5);
// 				First_index = $(this).find('li.active a').html();
// 				i = parseInt(First_index) + 1;
// 				while (i <= TotalPages) {
// 					pages.push(href.substring(0,href.indexOf("page=")+5) + i);
// 					i++;
// 				}
// 			}
// 		});
//
// 		$(window).scroll(function(){
// 			if (arrow_top) {
// 				if ($(document).scrollTop() > top_offset) {
// 					$('#arrow_top').show();
// 				} else {
// 					$('#arrow_top').hide();
// 				}
// 			}
// 		});
//
// 		if (button_more && href) {
// 			$('.pagination').parent().parent().before(button_more_block);
// 			if (!pagination_exist) {
// 				$('.pagination_ul').parent().parent().remove();
// 			} else {
// 				$('.pagination_ul').parent().parent().find('.col-sm-6.text-right').remove();
// 			}
// 			$('.load_more').click( function(event) {
// 				event.preventDefault();
// 				getNextProductPage(pages, pages_count);
// 				pages_count++;
// 			});
//
//
// 			$(window).on('scroll', function(){
// 				var pageTopOffset = $(window).scrollTop();
// 				var documentHeight  = $('body').height();
// 				if((pageTopOffset > documentHeight - 1000) && !waiting){
// 					if (pages_count <= pages.length){
// 						$('.load_more').trigger('click');
// 					}
//
// 				}
// 			});
//
// 		} else if (href) {
// 			$('.pagination_ul').parent().parent().hide();
// 			$(window).scroll(function(){
// 				product_block = getProductBlock();
// 				product_block_height = $(product_block).parent().height();
// 				if (pages.length > 0) {
// 					if((product_block_offset+product_block_height-window_height)<($(this).scrollTop())){
// 						getNextProductPage(pages, pages_count);
// 						pages_count++;
// 					}
// 				}
// 			});
// 		}
// 	}
//
// });


