var live_search_mobile = {
	selector: '.main-menu-wrapper input[name=\'search\']',
}

$(document).ready(function() {
	var html = '';
	html += '<div class="live-search-mobile">';
	html += '<table class="table products">';
	html += '</table>';
	html += '<div class="result-text-mobile"></div>';
	html += '</div>';

	let base_path = window.location.origin;
    let api = base_path + '/api/v1/trazi?term=';
    let search_all_path = base_path + '/trazi/sve?term=';

	//$(live_search_mobile.selector).parent().closest('div').after(html);
	$(live_search_mobile.selector).after(html);

	$(live_search_mobile.selector).autocomplete({
		'source': function(request, response) {
			var filter_name = $(live_search_mobile.selector).val();
			if (filter_name.length == 0) {
				$('.live-search-mobile').css('display','none');
			}
			else {
				$('.result-text-mobile').html();
				var html = '';
				html +=	'<tr><td style="text-align:center;padding:20px 10px 10px"><span class="basel-spinner"></span></td></tr>';
				$('.live-search-mobile .table.products').html(html);
                $('.live-search-mobile').css('display','block');
                $('.live-search-mobile').css('z-index',1999999);
				$.ajax({
					url: api + filter_name,
					dataType: 'json',
					type: 'get',
					success: function(result,json) {
                        console.log(result)
						var products = result.products;
						$('.live-search-mobile .table.products tr').remove();
						$('.result-text-mobile').html('');
						if (!$.isEmptyObject(products)) {
							var show_image = true;
							var show_price = true;
							$('.result-text-mobile').html('<a href="'+search_all_path+filter_name+'" class="view-all-results">'+result.text_view_all+'('+result.total+')</a>');

							$.each(products, function(index,product) {
								var html = '';

								html += '<tr onclick="location.href=\'' + product.url + '\'">';
								if(product.image){
									html += '<td class="image"><img alt="' + product.name + '" src="' + product.image + '"></td>';
									html += '<td class="main">';
								} else {
									html += '<td colspan="2" class="main">';
								}
								html += '<a class="product-name main-font">' + product.name + '</a>';

								if(show_price){
									if (product.special) {
										html += '<div class="price"><span class="price-old">' + product.price + '</span><span class="price">' + product.special + '</span></div>';
									} else {
										html += '<div class="price"><span class="price">' + product.price + '</span></div>';
									}
								}
								html += '</td>';
								html += '</tr>';
								$('.live-search-mobile .table.products').append(html);
							});
						} else {
							var html = '';
							html +=	result.text_no_result;

							$('.result-text-mobile').html(html);
						}
						$('.live-search-mobile').css('display','block');
						return false;
					}
				});
			}
		},
		'select': function(product) {
			$(live_search_mobile.selector).val(product.name);
		}
	});

	$(document).bind( "mouseup touchend", function(e){
	  var container = $('.live-search-mobile');
	  if (!container.is(e.target) && container.has(e.target).length === 0)
	  {
		container.hide();
	  }
	});
});
