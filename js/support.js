var censeo_support = (typeof(censeo_support) != 'undefined') ? censeo_support : {};

(function () {
	var i = document.createElement('input');
	
	if (!censeo_support.hasOwnProperty('placeholder')) {
		censeo_support.placeholder = 'placeholder' in i;
	}
	
	if (!censeo_support.hasOwnProperty('input_number')) {
		i.setAttribute('type', 'number');
		censeo_support.input_number = i.type === 'number';
	}
})();
