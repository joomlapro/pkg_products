jQuery.noConflict();

(function ($) {
	$(function () {
		// Call the mask.
		if ($.fn.setMask) {
			$('.phone').setMask('phone');
			$('.phone-us').setMask('phone-us');
			$('.cpf').setMask('cpf');
			$('.cnpj').setMask('cnpj');
			$('.date').setMask('date');
			$('.date-us').setMask('date-us');
			$('.zip').setMask('cep');
			$('.time').setMask('time');
			$('.cc').setMask('cc');
			$('.integer').setMask('integer');
			$('.integer-limit5').setMask({
			    mask: '999.99',
			    'maxLength': 5,
			    type: 'reverse'
			});
			$('.decimal').setMask('decimal');
			$('.decimal-us').setMask('decimal-us');
			$('.signed-decimal').setMask('decimal-us');
			$('.signed-decimal-us').setMask('decimal-us');
		};
	});
})(jQuery);