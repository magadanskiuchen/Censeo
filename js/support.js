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
	
	if (!censeo_support.hasOwnProperty('input_date')) {
		i.setAttribute('type', 'date');
		censeo_support.input_date = i.type === 'date';
	}
	
	if (!censeo_support.hasOwnProperty('input_time')) {
		i.setAttribute('type', 'time');
		censeo_support.input_time = i.type === 'time';
	}
	
	if (!censeo_support.hasOwnProperty('input_datetime_local')) {
		i.setAttribute('type', 'datetime-local');
		censeo_support.input_datetime_local = i.type === 'datetime-local';
	}
	
	if (!censeo_support.hasOwnProperty('input_color')) {
		i.setAttribute('type', 'color');
		censeo_support.input_color = i.type === 'color';
	}
})();
