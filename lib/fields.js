jQuery(function ($) {
	$('form.censeo-form').submit(function (e) {
		var errors = [];
		
		if (censeo_support && !censeo_support.input_number) {
			$('input.censeo-field-number').each(function () {
				var $this = $(this);
				var val = $this.val();
				
				if (isNaN(val)) {
					errors.push($this.data('errorNan'));
				}
				
				if (val < $this.attr('min')) {
					errors.push($this.data('errorMin'));
				}
				
				if (val > $this.attr('max')) {
					errors.push($this.data('errorMax'));
				}
			});
		}
		
		if (errors.length != 0) {
			e.preventDefault();
			alert(errors.join("\n"));
		}
	});
});