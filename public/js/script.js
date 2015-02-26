$(document).ready(function() {
	
	//Slider
	$("#slider").carousel({
		interval: 5000,
		pause: "hover"
	});
	
	$("#testi").carousel({
		interval: 4000
	});
	
	$("#itemsingle").carousel({
		interval: false
	});

	$(".weight").on('click', function(){
		$(".weight").removeClass('active');
		$(this).addClass('active');
		$('.price').text($(this).data('price')+' грн.');
	});

	$(".addOrder").on('click', function(){
		$(this).addClass('disabled');
		$('.loader').show();

		$('.orderCount').text(parseInt($('.orderCount').text(), 10) + 1);
		$('.orderTotal').text(parseInt($('.orderTotal').text(), 10) + parseInt($('.price').text(), 10));

		var weight  = 0,
				product = $(this).data('product'),
				self    = this;

		$(".weight").each(function(){
			if($(this).hasClass('active')){
				weight = $(this).data('id');
				return;
			}
		});

		$.ajax({
			url			:	'/order/add',
			method	: 'POST',
			data    : { weight: weight, product: product },
			dataType: 'JSON',
			success: function(){
				$(self).removeClass('disabled');
				$('.loader').hide();
				$('.successAddOrder').show();
				_.delay(function(){
					$('.successAddOrder').fadeOut(1000);
				}, 3000);
			},
			error: function(){
				$(self).removeClass('disabled');
				$('.loader').hide();
				$('.errorAddOrder').show();
				_.delay(function(){
					$('.errorAddOrder').hide();
				}, 3000);
			}
		});
	});

	$('.decreaseOrder').on('click', function(){
		$(this).addClass('disabled');

		var self			= this,
				value			= $(this).next().val(),
				newVal		= value - 1,
				price			= $(this).parent().parent().data('price'),
				weight		= $(this).parent().parent().data('weight'),
				product		= $(this).parent().parent().data('product'),
				priceElm	= $(this).parent().parent().find('.priceOrder');

		if(newVal < 1){
			return;
		}

		$(this).next().val(newVal);
		priceElm.text(price * newVal);
		recalcTotalPrice();
		$('.orderCount').text(parseInt($('.orderCount').text(), 10) - 1);
		$('.orderTotal').text(parseInt($('.orderTotal').text(), 10) - parseInt(price, 10));

		$.ajax({
			url			:	'/order/decrease',
			method	: 'POST',
			data    : { weight: weight, product: product },
			dataType: 'JSON',
			success: function(){
				$(self).removeClass('disabled');
			},
			error: function(){
				$(self).removeClass('disabled');
				$(self).next().val(value);
			}
		});

	});

	$('.increaseOrder').on('click', function(){
		$(this).addClass('disabled');

		var self		= this,
				value		= $(this).prev().val(),
				newVal	= parseInt(value, 10) + 1,
				price   = $(this).parent().parent().data('price'),
				weight	= $(this).parent().parent().data('weight'),
				product		= $(this).parent().parent().data('product'),
				priceElm	= $(this).parent().parent().find('.priceOrder');

		$(this).prev().val(newVal);
		priceElm.text(price * newVal);
		recalcTotalPrice();

		$('.orderCount').text(parseInt($('.orderCount').text(), 10) + 1);
		$('.orderTotal').text(parseInt($('.orderTotal').text(), 10) + parseInt(price, 10));

		$.ajax({
			url			:	'/order/increase',
			method	: 'POST',
			data    : { weight: weight, product: product },
			dataType: 'JSON',
			success: function(){
				$(self).removeClass('disabled');
			},
			error: function(){
				$(self).removeClass('disabled');
				$(self).next().val(value);
			}
		});
	});

	$('.removeOrder').on('click', function(){
		$(this).addClass('disabled');

		$(this).addClass('disabled');

		var self		= this,
				count		= $(this).parent().parent().find('.countOrderInput').val(),
				price   = $(this).parent().parent().data('price'),
				weight	= $(this).parent().parent().data('weight'),
				product	= $(this).parent().parent().data('product');

		$(this).parent().parent().hide();
		recalcTotalPrice();
		$('.orderCount').text(parseInt($('.orderCount').text(), 10) - count);
		$('.orderTotal').text(parseInt($('.orderTotal').text(), 10) - parseInt(price, 10) * count);

		if($('.orderItem').not(':hidden').length < 1){
			$('.confirm').hide();
			$('.emprtyOrder').show();
		}
		$.ajax({
			url			:	'/order/remove',
			method	: 'POST',
			data    : { weight: weight, product: product },
			dataType: 'JSON',
			success: function(){
				$(self).removeClass('disabled');
			},
			error: function(){
				$(self).removeClass('disabled');
				$(self).parent().parent().show();
			}
		});
	});

	$('.countOrderInput').on('keydown', function(e){
		e.preventDefault();
		return;
	});

	$('.clearOrder').on('click', function(){
		$(this).addClass('disabled');
		$('.confirm').hide();
		$('.emprtyOrder').show();

		$('.orderCount').text('0');
		$('.orderTotal').text('0');
		$.ajax({
			url			:	'/order/clear',
			method	: 'POST',
			dataType: 'JSON'
		});
	});

	function recalcTotalPrice()
	{
		var total = 0;
		$('.orderItem').not(':hidden').each(function(){
			var price  = parseInt($(this).data('price'), 10),
					count  = parseInt($(this).find('.countOrderInput').val(), 10),
					result = price * count;

			total += result;
		});

		$('.totalOrder').text(total+' грн.');
	}
});
