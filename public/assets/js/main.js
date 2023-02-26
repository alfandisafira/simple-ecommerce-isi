(function($) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	if(localStorage.getItem("items") !== null){
		$('.empty-qty').addClass('qty');
		let items = JSON.parse(localStorage.items);
		let current_qty = 0;
		items.forEach(element => {
			current_qty += element.qty;
		});
		$('.qty').html(current_qty);

		// Checkout Page
		let list_checkout = '';
		let order_total = 0;
		items.forEach(element => {
			let total = element.qty * element.price; 
			list_checkout +=
			`
			<div class="order-products" id="product${element.product_id}">
				<div class="order-col">
					<div><span id="qtyItem${element.product_id}">${element.qty}x</span> ${element.name}</div>
					<div class="rupiah" id="totalItem${element.product_id}">${total}</div>
					<div>
						<button 
							type="button" class="add-item btn btn-default"
							data-product_id ="${element.product_id}"
						>+</button>
						<button 
							type="button" class="reduce-item btn btn-default"
							data-product_id ="${element.product_id}"
						>-</button>
						<button 
							type="button" class="destroy-item btn btn-danger"
							data-product_id ="${element.product_id}"
						>x</button>
					</div>
				</div>
			</div>
			`;
			order_total += total;
		});

		let element_chekout = 
		`
			<div class="order-col">
				<div><strong>PRODUCT</strong></div>
				<div><strong>TOTAL</strong></div>
				<div><strong>ACTION</strong></div>
			</div>
			${list_checkout}
			<div class="order-col">
				<div><strong>TOTAL</strong></div>
				<div><strong class="order-total rupiah">${order_total}</strong></div>
			</div>
			<div class="form-group">
				<input class="input" type="text" id="couponDisc" placeholder="Coupon Discount">
				<input class="input" type="hidden" id="tempDisc">
				<button class="btn btn-danger apply-coupon" value="0">Apply Coupon</button>
			</div>
		`;

		$('.order-summary').html(element_chekout);
		$('.order-summary').after(`<button class="primary-btn order-submit">Place order</button>`);
		if(localStorage.getItem("coupon")){
			let code = localStorage.getItem("coupon");
			$.ajax({	
				url: `/coupon-show/${code}` ,
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					let discount_amount = order_total * (data.discount_amount / 100);
					order_total = order_total - discount_amount;
					$('.order-total').html(rupiah(order_total));
					$('#tempDisc').val(discount_amount);
					$('#couponDisc').val(code);
					$('.apply-coupon').val(1);
				}
			});
		};
	} else {
		$('.order-summary').html(`<h1 style="text-align: center">Your cart is empty.</h1>`);
	}

	const rupiah = (number)=>{
		return new Intl.NumberFormat("id-ID", {
			style: "currency",
			currency: "IDR"
		}).format(number);
	}

	$('.rupiah').each(function(){
		let val = $(this).html();
		$(this).html(rupiah(val));
	})

	// Mobile Nav toggle
	$('.menu-toggle > a').on('click', function (e) {
		e.preventDefault();
		$('#responsive-nav').toggleClass('active');
	})

	/////////////////////////////////////////

	// Tab Change
	$('.section-tab-nav li a').on('click', function () {
		let tabBefore = $('.section-tab-nav li.active').attr('data-toggle');
		$('#'+tabBefore).removeClass('active');
		$('.section-tab-nav li.active').removeClass('active');

		$(this).parent().addClass('active');
		let tabNow = $(this).parent().attr('data-toggle');
		$('#'+tabNow).addClass('active');
	});
	

	// Products Slick
	$('.products-slick').each(function() {
		var $this = $(this),
				$nav = $this.attr('data-nav');

		$this.slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
			infinite: true,
			speed: 300,
			dots: false,
			arrows: true,
			appendArrows: $nav ? $nav : false,
			responsive: [{
	        breakpoint: 991,
	        settings: {
	          slidesToShow: 2,
	          slidesToScroll: 1,
	        }
	      },
	      {
	        breakpoint: 480,
	        settings: {
	          slidesToShow: 1,
	          slidesToScroll: 1,
	        }
	      },
	    ]
		});
	});

	$('.products-widget-slick').each(function() {
		var $this = $(this),
				$nav = $this.attr('data-nav');

		$this.slick({
			infinite: true,
			autoplay: true,
			speed: 300,
			dots: false,
			arrows: true,
			appendArrows: $nav ? $nav : false,
		});
	});

	/////////////////////////////////////////
	
	// Add Product to Local Storage
	$('.add-to-cart-btn').on('click', function(){
		let product_id = $(this).data('product_id');
		let price = $(this).data('price');
		let name = $(this).data('name');

		let item = {
			product_id: product_id,
			name: name,
			qty: 1,
			price: price
		}

		if (localStorage.getItem("items") === null) {
			let items = [item]
			localStorage.setItem("items", JSON.stringify(items));

			$('.empty-qty').addClass('qty');
			$('.qty').html(1);

			console.log(JSON.parse(localStorage.items));
		}
		else if (localStorage.getItem("items") !== null) {
			
			let items = JSON.parse(localStorage.items);
			let add_element = true;

			items.forEach(element => {
				if (element.product_id == product_id) {
					element.qty++;
					add_element = false;
				}
			});

			if (add_element) {
				items.push(item);
			}
			
			localStorage.setItem("items", JSON.stringify(items));
			let qty = parseInt($('.qty').html());
			qty++;
			$('.qty').html(qty);

			console.log(JSON.parse(localStorage.items));
		}
	})

	// Checkout Operations
	$('.add-item').on('click', function(){
		let product_id = $(this).data('product_id');

		let is_applied = parseInt($('.apply-coupon').val());

		let items = JSON.parse(localStorage.items);

		let current_total;

		items.forEach(element => {
			if (element.product_id == product_id) {
				element.qty++;
				if (is_applied) {
					let tempDisc = parseInt($('#tempDisc').val());
					current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) + tempDisc + element.price;
				} else {
					current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) + element.price;
				}
				$('#qtyItem'+element.product_id).html(element.qty+'x');
				$('#totalItem'+element.product_id).html(rupiah(element.qty*element.price));
				$('.order-total').html(rupiah(current_total));
			}
		});

		localStorage.setItem("items", JSON.stringify(items));
		
		if (is_applied) {
			alert('Please fill coupon discount again.');
			localStorage.removeItem("coupon");
			$('.apply-coupon').val(0);
			$('#couponDisc').val('');
		}
	})

	$('.reduce-item').on('click', function(){
		let product_id = $(this).data('product_id');

		let items = JSON.parse(localStorage.items);
		let deleted_index;

		let is_applied = parseInt($('.apply-coupon').val());
		
		let current_total;

		for (let index = 0; index < items.length; index++) {
			if (items[index].product_id == product_id) {
				if (items[index].qty == 1) {
					if (is_applied) {
						let tempDisc = parseInt($('#tempDisc').val());
						current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) + tempDisc - items[index].price;
					} else {
						current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) - items[index].price;
					}
					$('.order-total').html(rupiah(current_total));
					$('#product'+product_id).remove();
					deleted_index = index;

					items.splice(deleted_index, 1);
				} else {
					items[index].qty--;
					if (is_applied) {
						let tempDisc = parseInt($('#tempDisc').val());
						current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) + tempDisc - items[index].price;
					} else {
						current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) - items[index].price;
					}

					$('#qtyItem'+items[index].product_id).html(items[index].qty+'x');
					$('#totalItem'+items[index].product_id).html(rupiah(items[index].qty*items[index].price));
					$('.order-total').html(rupiah(current_total));
				}
			}
		}

		if (items.length == 0) {
			localStorage.removeItem("items");
			$('.order-submit').remove();
			$('.order-col').remove();
			$('.order-summary').html(`<h1 style="text-align: center">Your cart is empty.</h1>`);			
		} else {
			localStorage.setItem("items", JSON.stringify(items));

			if (is_applied) {
				alert('Please fill coupon discount again.');
				localStorage.removeItem("coupon");
				$('.apply-coupon').val(0);
				$('#couponDisc').val('');
			}
		}

	})

	$('.destroy-item').on('click', function(){
		let product_id = $(this).data('product_id');

		let items = JSON.parse(localStorage.items);
		let deleted_index;

		let is_applied = parseInt($('.apply-coupon').val());

		let current_total;

		for (let index = 0; index < items.length; index++) {
			if (items[index].product_id == product_id) {
				if (is_applied) {
					let tempDisc = parseInt($('#tempDisc').val());
					current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) + tempDisc - (items[index].price * items[index].qty);
				} else {
					current_total = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, '')) - (items[index].price * items[index].qty);
				}
				$('.order-total').html(rupiah(current_total));
				$('#product'+product_id).remove();
				deleted_index = index;

				items.splice(deleted_index, 1);
			}
		}

		if (items.length == 0) {
			localStorage.removeItem("items");
			$('.order-submit').remove();
			$('.order-col').remove();
			$('.order-summary').html(`<h1 style="text-align: center">Your cart is empty.</h1>`);			
		} else {
			localStorage.setItem("items", JSON.stringify(items));

			if (is_applied) {
				alert('Please fill coupon discount again.');
				localStorage.removeItem("coupon");
				$('.apply-coupon').val(0);
				$('#couponDisc').val('');
			}
		}
	})

	$('.apply-coupon').on('click', function(){
		let is_applied = parseInt($(this).val());
		let code = $('#couponDisc').val();

		if (is_applied) {
			let current_total_order = parseInt($('.order-total').html().split(',')[0].replace(/[^0-9]/g, ''));
			let discount_amount = parseInt($('#tempDisc').val());
			$('.order-total').html(rupiah(current_total_order + discount_amount))
			// console.log(current_total_order);
			// console.log($('#tempDisc').val());
		}

		if (code !== '') {
			$.ajax({	
				url: `/coupon-show/${code}` ,
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					if(data !== false){
						let current_total_order = $('.order-total').html().split(',')[0].replace(/[^0-9]/g, '');
						let discount_amount = current_total_order * (data.discount_amount / 100)
						$('#tempDisc').val(discount_amount);
						$('.order-total').html(rupiah(parseInt( current_total_order ) - discount_amount));
	
						alert('Coupon successfully applied!');
						$('.apply-coupon').val(1);
						localStorage.setItem("coupon", code);
					} else {
						alert('Apply coupon failed.');
					}
				}
			});
		} else {
			alert('Please fill coupon code');
		}


	})

	// Checkout the order
	$('.order-submit').on('click', function(){
		let items = JSON.parse(localStorage.items);
		let coupon = localStorage.getItem('coupon');
		let full_name = $('#fullName').val();
		let email = $('#email').val();
		let address = $('#address').val();
		let phone_number = $('#phoneNumber').val();

		if (full_name == '' || email == '' || address == '' || phone_number == ''){
			alert('Please fill out the form below')
		} else {
			$.ajax({
				
				url: '/store-checkout',
				data: {
					items: items,
					coupon: coupon,
					full_name: full_name,
					email: email,
					address: address,
					phone_number: phone_number,
				},
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					localStorage.clear();
					window.location.href = '/payment/' + data.snapToken + '/' + data.order_id;
				}
			});
		}

	})


	/////////////////////////////////////////

	// Product Main img Slick
	$('#product-main-img').slick({
		infinite: true,
		speed: 300,
		dots: false,
		arrows: true,
		fade: true,
		asNavFor: '#product-imgs',
  	});

	// Product imgs Slick
	$('#product-imgs').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		arrows: true,
		centerMode: true,
		focusOnSelect: true,
			centerPadding: 0,
			vertical: true,
		asNavFor: '#product-main-img',
			responsive: [{
			breakpoint: 991,
			settings: {
						vertical: false,
						arrows: false,
						dots: true,
			}
		},
		]
	});

	// Product img zoom
	// var zoomMainProduct = document.getElementById('product-main-img');
	// if (zoomMainProduct) {
	// 	$('#product-main-img .product-preview').zoom();
	// }

	/////////////////////////////////////////

	// Input number
	// $('.input-number').each(function() {
	// 	var $this = $(this),
	// 	$input = $this.find('input[type="number"]'),
	// 	up = $this.find('.qty-up'),
	// 	down = $this.find('.qty-down');

	// 	down.on('click', function () {
	// 		var value = parseInt($input.val()) - 1;
	// 		value = value < 1 ? 1 : value;
	// 		$input.val(value);
	// 		$input.change();
	// 		updatePriceSlider($this , value)
	// 	})

	// 	up.on('click', function () {
	// 		var value = parseInt($input.val()) + 1;
	// 		$input.val(value);
	// 		$input.change();
	// 		updatePriceSlider($this , value)
	// 	})
	// });

	// var priceInputMax = document.getElementById('price-max'),
	// 		priceInputMin = document.getElementById('price-min');

	// priceInputMax.addEventListener('change', function(){
	// 	updatePriceSlider($(this).parent() , this.value)
	// });

	// priceInputMin.addEventListener('change', function(){
	// 	updatePriceSlider($(this).parent() , this.value)
	// });

	// function updatePriceSlider(elem , value) {
	// 	if ( elem.hasClass('price-min') ) {
	// 		console.log('min')
	// 		priceSlider.noUiSlider.set([value, null]);
	// 	} else if ( elem.hasClass('price-max')) {
	// 		console.log('max')
	// 		priceSlider.noUiSlider.set([null, value]);
	// 	}
	// }

	// Price Slider
	// var priceSlider = document.getElementById('price-slider');
	// if (priceSlider) {
	// 	noUiSlider.create(priceSlider, {
	// 		start: [1, 999],
	// 		connect: true,
	// 		step: 1,
	// 		range: {
	// 			'min': 1,
	// 			'max': 999
	// 		}
	// 	});

	// 	priceSlider.noUiSlider.on('update', function( values, handle ) {
	// 		var value = values[handle];
	// 		handle ? priceInputMax.value = value : priceInputMin.value = value
	// 	});
	// }

})(jQuery);
