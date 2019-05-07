$(document).ready(function() {
	var timerId;
	var timerId2;
	var menuItemsHidden = $('#mainPgCat').children('li');
	var menuItems = $('.category_menu, .category_menu > ul').children('li');
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

	// all cat view
	$('.all_category a').on('click', function(e) {
		e.preventDefault();
		
		popupOverlay.fadeToggle(400);
		$('#allCat').fadeToggle(400);

	});
	popupOverlay.on('click', function(e) {

		$('#allCat').css('display','none');

	});

	//custom scrollbar
	// $('#mobileAllCat .category_menu, #allCat .category_menu, .modal-cart .modal-cart_body, .ready_order .order_body').mCustomScrollbar();

	//listing view
	$('#listing_string').on('click', function(e) {
		var _this  = $(this);
		var parent = _this.closest('.listing');

		_this.addClass('active').siblings().removeClass('active');

		parent.find('.listing-item').addClass('listing-item--string');
	});

	$('#listing_card').on('click', function(e) {
		var _this  = $(this);
		var parent = _this.closest('.listing');

		_this.addClass('active').siblings().removeClass('active');

		parent.find('.listing-item').removeClass('listing-item--string');
	});

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
	$('#mobile-fixed-menu .close').on('click', function(e) {
		e.preventDefault();

		var _this = $(this);
		var container = _this.closest('.tabs');
		var content = container.find('.tabs_list');
		var navItem = container.find('.tabs_control_item');

		content.hide();

		navItem.removeClass('active');

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

		var popupTxt = $(this).closest('.contact-item').find('h3 span').text();
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

    });

	close.click( function(e) {
        e.preventDefault();
        modal.css('display', 'none');
        popupOverlay.fadeOut(400);
    });

	//show hidden category
	menuItemsHidden.each(function(i,elem) {
		if ( i >= 18 ) {

			$(this).hide();

			if ( $(this).hasClass('last-item') ) {
				$(this).show();
				lastItem.addClass('item-hidden');
			}

		}
	});

	lastItem.on('click', function(e) {
		e.preventDefault();
		var _this = $(this);

		menuItems.each(function(i,elem) {

			if ( i >= 18 ) {

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

			if ( $this.closest('#allCat').length != 0 ) {
				var clonedSubItem = $this.find(menuSubItem).clone(true);
				var itemPos = $this.offset().top;

			 	if ( $this.closest('.category_menu-sub_menu').length == 0 ) {
					var container = $this.closest('.category_menu');
						clonedSub = container.children('.category_menu-sub_menu');

					clonedSub.remove();

				}
				
				clonedSubItem.offset({top:itemPos-426, left:326});
				$this.closest('.category_menu').prepend(clonedSubItem);
				clonedSubItem.css(
					{
						'display':'block',
					}
				);

			} else {

				$this.siblings()
					.find(menuSubItem)
					.css('display','none');

				$this.find(menuSubItem).css('display','block');

			}

		}

	}).on('mouseleave', function(e){

		clearTimeout(timerId);

	});

	menuItems.on('mouseleave', function(e){
		var $this = $(this);
		clearTimeout(timerId2);

		timerId2 = setTimeout(hideMenu, 300);

		function hideMenu() {

			if ( $this.closest('#allCat').length != 0 ) {

				var container = $this.closest('.category_menu');
					clonedSub = container.children('.category_menu-sub_menu');

				clonedSub.remove();

			} else {

				$this.siblings()
					.find(menuSubItem)
					.css('display','none');

				$this.find(menuSubItem).css('display','none');

			}

		}

	}).on('mouseenter', function(e){

		clearTimeout(timerId2);

	});

	//category match search
	$('#match-search').on('focus', function(e){
		var _this = $(this),
			parent = _this.closest('.first'),
			child = parent.find('i');

		child.hide();

	});

	$('#match-search').on('blur', function(e){
		var _this = $(this),
			parent = _this.closest('.first'),
			child = parent.find('i');

		child.show();

	});

	$('#match-search').on('keyup', function(e){
		e.preventDefault();
		var _this = $(this),
			val = _this.val();

			menuTxtItem.each(function(index) {
				var currItem = $(this);

				if ( currItem.closest('.category_menu-sub_menu').length == 0 ) {
					
					var txt = currItem.text();
					var replacedTxt;
					var arr = [];
					arr.push(txt);
					var matchTxt = arr.join(' ');
					var result = matchTxt.match( new RegExp(val,'i') );
					var markedLetter = '<span class="marked">' + val + '</span>';
					var newstr = txt.replace(result, markedLetter);

					if ( val == result ) {
						currItem.closest(menuItems).css('display', 'block');
						hiddenItem.css('display', 'none');
					}
					else if ( result == null ) {
						currItem.closest(menuItems).css('display', 'none');
						if (!menuItems.is(':visible') && result == null) {
							hiddenItem.css('display', 'block');
						}
					}

					currItem.html(newstr);

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