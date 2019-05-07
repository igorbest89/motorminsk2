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

	//delivery price visibility
	$('.tabs_control_item').on('click', function(e) {
		var input1 = $(inputsRadio[0]),
			input2 = $(inputsRadio[1]);


		if ( inputsRadio.is(':visible') ) {

			$('.delivery_price').css('display', 'block');

			inputsRadio.on('change', function(e) {

				if ( input1.is(':checked') ) {

					$('.delivery_price strong b').text('12.00');

				} else if ( input2.is(':checked') ) {

					$('.delivery_price strong b').text('25.00');

				}

			});

		} else {

			$('.delivery_price').css('display', 'none');
		
		}

	});

	//cart step
	nextStep.on('click', function(e) {
		e.preventDefault();

		if ( cartStep1.hasClass('active') && !cartStep2.hasClass('active') ) {
			cartStep2.addClass('active');
			cartItem1.removeClass('active');
			cartItem2.addClass('active');
			prevStep.find('span').text('Назад в корзину');
			nextStep.find('span').text('К оплате');
		} else if ( cartStep2.hasClass('active') ) {
			boolean = secondStepValidation();
			
			if (boolean) {
				cartStep3.addClass('active');
				cartItem2.removeClass('active');
				cartItem3.addClass('active');
				prevStep.find('span').text('Назад в доставке');
				nextStep.find('span').text('Оформить');
			}

			function secondStepValidation() {
				var arr = [];

				inputs.each(function(index) {
						_this = $(this),
						value = _this.val();

					if ( _this.is(':visible') ) {

						if ( value == '' ) {

							arr.push(value);
							fieldValidation(_this);

						}

					}

				});

				if ( arr.length > 0 || email.hasClass('error') ) {

					boolean = false;

				} else {

					boolean = true;

				}

				return boolean;
			}

		}

	});

	prevStep.on('click', function(e) {
		e.preventDefault();

		if ( cartStep3.hasClass('active') ) {
			cartStep3.removeClass('active');
			cartItem2.addClass('active');
			cartItem3.removeClass('active');
			prevStep.find('span').text('Назад в корзину');
			nextStep.find('span').text('К оплате');
		}
		else if ( cartStep2.hasClass('active') && !cartStep3.hasClass('active') ) {
			cartStep2.removeClass('active');
			cartItem1.addClass('active');
			cartItem2.removeClass('active');
			prevStep.find('span').text('Назад в магазин');
			nextStep.find('span').text('Доставка');
		}

	});

	//modal cart toggle
	$('#modal-cart-link, #modal-cart .bt').on('click', function(e){
		e.preventDefault();

		$('#modal-cart').slideToggle(200);

	});

	//remove product from cart
	removeProduct('tr', '.cart-view_item a');
	removeProduct('.modal-cart_item', '#modal-cart a');

});

//cart sum
cartSumFunc();
console.log();

$('#cart-list .quantity').on('change', function(){});

//cart sum function
function cartSumFunc() {
	var container = $('#cart-list tbody'),
		rows = container.find('tr'),
		price,
		quantity,
		result,
		sum = 0,
		sumValue,
		totalSum = 0,
		clientSale = 0,
		promoSale = 0,
		resultSum = 0;

	function rowsSumFunc() {
		rows.each(function(index) {
			var row = $(this),
				quantity = row.find('.quantity').val()*1,
				price = parseFloat( row.find('.price').text() ),
				result = parseFloat( row.find('.result').text() ),
				resultTxt = row.find('.result');

			sum = parseFloat( (price * quantity).toFixed(2) );
			sumValue = resultTxt.text(sum);
			totalSum += sum;

			return totalSum;
			
		});

		return totalSum;

	}

	rowsSumFunc();

	clientSale = totalSum*10/100;
	resultSum = totalSum - clientSale - promoSale;

	$('#totalSum').text(totalSum.toFixed(2));
	$('#clientSale').text(clientSale.toFixed(2));
	$('#resultSum').text(resultSum.toFixed(2));

	return resultSum;

}

function sumСalculation(argument) {
	// body...
}

function removeProduct(item, link) {

	$(link).on('click', function(e){
		e.preventDefault();
		
		var _this = $(this);
		var removed = _this.closest(item);

		removed.remove();

	});

}