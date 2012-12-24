jQuery.noConflict();

(function ($) {
	$(function () {
		// maskedInput
		if ($.fn.mask) {
			$(".date").mask("99-99-9999");
			$(".date2").mask("9999-99-99");
			$(".phone").mask("(99) 9999-9999");
			$(".cnpj").mask("99.999.999/9999-99");
			$(".cpf").mask("999.999.999-99");
			$(".zip").mask("99.999-999");
		}

		// maskMoney
		if ($.fn.maskMoney) {
			$(".money").maskMoney({
				symbol : "R$",
				decimal : ",",
				thousands : "."
			})
		}
	});
})(jQuery);
