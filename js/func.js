var support = {};

(function () {
	var i = document.createElement('input');
	
	support.placeholder = 'placeholder' in i;
})();

jQuery(function ($) {
	if (!support.placeholder) {
		$('input[placeholder]').each(function () {
			$this = $(this);
			
			$this.focus(function () {
				if ($this.val() == $this.attr('placeholder')) {
					$this.val('');
				}
			}).blur(function () {
				if ($this.val() == '') {
					$this.val($this.attr('placeholder'));
				}
			});
			
			if ($this.val() == '') {
				$this.val($this.attr('placeholder'));
			}
		});
	}
});