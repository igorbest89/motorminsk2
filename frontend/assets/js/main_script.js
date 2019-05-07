$(document).ready(function() {
	var timerId;
	var timerId2;
	var menuItemsHidden = $('#mainPgCat').children('li');
	var menuItems = $('.category_menu, .category_menu  ul').children('li');
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
	$('input, select').styler({
		selectSmartPositioning: false,
	});

	//slick slider init
	$('.slider').slick({
		dots: true,
		autoplay: false,
		autoplaySpeed: 3000,
		speed: 700,
		arrows: false
	});

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

	// all cat view
	$('#allCatOpen').on('click', function(e) {
		e.preventDefault();
		
		popupOverlay.fadeToggle(400);
		$('#allCat').fadeToggle(400);
		$(this).toggleClass('active');

	});
	popupOverlay.on('click', function(e) {

		$('#allCat').css('display','none');
		$('#allCatOpen').removeClass('active');

	});

	//custom scrollbar
	$('#allCat .category_menu, .modal-cart .modal-cart_body, .ready_order .order_body, .filter-item .jq-selectbox__dropdown ul, .banner .jq-selectbox__dropdown ul').mCustomScrollbar();

	//listing view
	$('#listing_string').on('click', function(e) {
		var _this  = $(this);

		_this.addClass('active').siblings().removeClass('active');

	});

	$('#listing_card').on('click', function(e) {
		var _this  = $(this);

		_this.addClass('active').siblings().removeClass('active');

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
				
				clonedSubItem.offset({top:itemPos-353, left:358});
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

	//select text of checked radio button
	inputTypeRadio.each(function(index) {
		var _this = $(this);
		var checked = _this.is(':checked');

		if ( checked ) {
			_this.closest('p').find('span').css({color: '#0A1F44'});
		} else {
			_this.closest('p').find('span').css({color: '#8a94a6'});
		}

	});

	$('.cart input[type="radio"]').on('click', function(e){
		var _this = $(this);
		var container = _this.closest('p');
		var siblings = container.siblings();

		container.find('span').css({color: '#0A1F44'});
		siblings.find('span').css({color: '#8a94a6'});

	});

	//cart side progressbar
	$('#cart_item_2 input').on('click focus', function(e){
		var _this = $(this);
		var tab1 = $('#cart_item_2 .tabs_item').eq(0);
		var tab2 = $('#cart_item_2 .tabs_item').eq(1);

		if ( tab1.is(':visible') ) {

			tab1.find('.progress_bar span').addClass('done');

		} else if ( tab2.is(':visible') ) {
			var test = _this.closest('.tabs_item');
			var test2 = _this.closest('.add_info');

			if ( test.length !=0 ) {
				tab2.find('.progress_bar span').eq(1).addClass('done');
				tab2.find('.progress_bar span').eq(2).addClass('done');
			} else if ( test2.length !=0 ) {
				tab2.find('.progress_bar span').addClass('done');
			}

		}

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