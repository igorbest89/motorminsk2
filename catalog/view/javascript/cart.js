$(document).ready(function() {

	var container = $('.cart-wrap'),
		cartNav = container.find('.cart-nav'),
		cartSteps = cartNav.find('.cart-nav_item'),
		cartStep1 = $('#cart_step_1'),
		cartStep2 = $('#cart_step_2'),
		cartStep3 = $('#cart_step_3'),
		cartView = container.find('.cart-view'),
		cartViewItems = cartView.find('.cart-view_item'),
		cartItem1 = $('#cart_item_1'),
		cartItem2 = $('#cart_item_2'),
		cartItem3 = $('#cart_item_3'),
		prevStep = $('#prev-step'),
		nextStep = $('#next-step'),
		inputs = container.find('input'),
		inputsRadio = container.find('input[type="radio"]'),
		email = $('#inputEmail'),
		emailValue = email.val(),
		popupOverlay = $('#overlay'),
		cartOverlay = $('#overlay-cart'),
		input,
		value,
		boolean,
		_this;

	//delivery price visibility popup
	$('#radioPopup').on('click', function(e) {

		$('#overlay').fadeIn(400, function(){

			$('#popup_data').css('display', 'block');

		});

	});

	//modal cart toggle
	$('.cart a, #modal-cart .bt').on('click', function(e){
		e.preventDefault();
		var count = $(this).find('.cart-count').text();

		if (count > 0) {
			$('#modal-cart').slideToggle(200);
			cartOverlay.fadeToggle(400);
		}

	});
	cartOverlay.on('click', function(e) {

		$('#modal-cart').css('display','none');
		$(this).css('display','none');

	});

	//remove product from cart
	removeProduct('tr', '.cart-view_item a');
	removeProduct('.modal-cart_item', '#modal-cart a');

});

function removeProduct(item, link) {

	$(link).on('click', function(e){
		e.preventDefault();
		
		var _this = $(this);
		var removed = _this.closest(item);

		removed.remove();

	});

}
