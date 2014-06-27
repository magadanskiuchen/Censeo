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
	
	if (censeo_support && !censeo_support.input_date) {
		$('input.censeo-field-date').each(function () {
			$(this).datepicker({
				dateFormat: $(this).data('format'),
				firstDay: $(this).data('weekStartsOn'),
				changeMonth: true,
				changeYear: true,
				
				dayNames: [ ci18n.sunday, ci18n.monday, ci18n.tuesday, ci18n.wednesday, ci18n.thursday, ci18n.friday, ci18n.saturday ],
				dayNamesMin: [ ci18n.su, ci18n.mo, ci18n.tu, ci18n.we, ci18n.th, ci18n.fr, ci18n.sa ],
				dayNamesShort: [ ci18n.sun, ci18n.mon, ci18n.tue, ci18n.wed, ci18n.thu, ci18n.fri, ci18n.sat ],
				
				monthNames: [ ci18n.january, ci18n.february, ci18n.march, ci18n.april, ci18n.may, ci18n.june, ci18n.july, ci18n.august, ci18n.september, ci18n.october, ci18n.november, ci18n.december ],
				monthNamesShort: [ ci18n.jan, ci18n.feb, ci18n.mar, ci18n.apr, ci18n.may, ci18n.jun, ci18n.jul, ci18n.aug, ci18n.sep, ci18n.oct, ci18n.nov, ci18n.dec ]
			});
		});
	}
	
	if (censeo_support && !censeo_support.input_time && $.timepicker) {
		$('input.censeo-field-time:not(.censeo-field-datetime)').each(function () {
			$(this).timepicker({
				showPeriodLabels: false,
				timeFormat: 'HH:mm',
				
				hourText: ci18n.hourText,
				minuteText: ci18n.minuteText,
				secondText: ci18n.secondText,
				millisecText: ci18n.millisecText,
				microsecText: ci18n.microsecText,
				timezoneText: ci18n.timezoneText,
				amNames: [ci18n.AM, ci18n.A],
				pmNames: [ci18n.PM, ci18n.P],
				closeText: ci18n.closeText,
				currentText: ci18n.currentText,
				timeOnlyTitle: ci18n.timeOnlyTitle,
				timeText: ci18n.timeText
			});
		});
	}
	
	var nativeDatetimeLocalSupportBuggy = true; // TODO: check if native sypport for datetime field has been fixed
	if (nativeDatetimeLocalSupportBuggy || censeo_support && !censeo_support.input_datetime_local && $.datetimepicker) {
		$('input.censeo-field-datetime').each(function () {
			$(this).datetimepicker({
				showPeriodLabels: false,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm',
				
				hourText: ci18n.hourText,
				minuteText: ci18n.minuteText,
				secondText: ci18n.secondText,
				millisecText: ci18n.millisecText,
				microsecText: ci18n.microsecText,
				timezoneText: ci18n.timezoneText,
				amNames: [ci18n.AM, ci18n.A],
				pmNames: [ci18n.PM, ci18n.P],
				closeText: ci18n.closeText,
				currentText: ci18n.currentText,
				timeOnlyTitle: ci18n.timeOnlyTitle,
				timeText: ci18n.timeText
			});
		});
	}
	
	function clearTransparency(e) {
		$(e.target).closest('.censeo-field-row').find('input:checked').removeAttr('checked');
	}
	
	$('input.censeo-field-color').each(function () {
		var $this = $(this);
		var $allowTransparency = $this.next().find('input');
		
		if ($allowTransparency.is(':checked') && $this.data('defaultColor')) {
			$this.val($this.data('defaultColor'));
		}
		
		if (censeo_support && !censeo_support.input_color && $.wpColorPicker) {
			$(this).wpColorPicker({
				change: clearTransparency
			});
		} else {
			$this.change(clearTransparency);
		}
	});
	
	function updateLocation(e, map, latLngFields, marker) {
		map.panTo(e.latLng);
		
		if (typeof(marker) !== 'undefined') {
			marker.setPosition(e.latLng);
		}
		
		latLngFields.lat.val(e.latLng.lat());
		latLngFields.lng.val(e.latLng.lng());
	}
	
	$('.censeo-location-map-container').each(function () {
		var $this = $(this);
		var $lat = $this.siblings('input[id$="lat"]');
		var $lng = $this.siblings('input[id$="lng"]');
		
		var location = new google.maps.LatLng($lat.val(), $lng.val());
		var map = new google.maps.Map(this, { center: location, mapTypeId: google.maps.MapTypeId.ROADMAP, disableDoubleClickZoom: true, zoom: 8 });
		var marker = new google.maps.Marker({ draggable: true, map: map, position: location });
		
		google.maps.event.addListener(marker, 'dragend', function (e) { updateLocation(e, map, { lat: $lat, lng: $lng }); });
		google.maps.event.addListener(map, 'dblclick', function (e) { updateLocation(e, map, { lat: $lat, lng: $lng }, marker); });
	});
	
	var censeo_media_types = {};
	
	$('.censeo-field-file').each(function () {
		var $this = $(this);
		
		$this.click(function (e) {
			e.preventDefault();
			
			var buttonLabel = $this.data('buttonLabel');
			var windowLabel = $this.data('windowLabel');
			var fileType = $this.data('fileType');
			
			if (typeof(censeo_media_types[fileType]) == 'undefined') {
				censeo_media_types[fileType] = wp.media.frames.censeo_file = wp.media({
					title: windowLabel,
					button: { text: buttonLabel },
					library: { type: fileType },
					multiple: false
				});
				
				var field = censeo_media_types[$this.data('fileType')];
				field.on('select', function () {
					var attachment = field.state().get('selection').first().toJSON();
					
					$this.siblings('[name*="url"]').val(attachment['url']);
					$this.siblings('[name*="attachment_id"]').val(attachment['id']);
					
					if (attachment.type == 'image') {
						var previewUrl = attachment['url'];
					} else {
						var previewUrl = attachment['icon'];
					}
					
					$this.siblings('img').attr('src', previewUrl);
				});
			} else {
				var field = censeo_media_types[fileType];
			}
			
			field.open();
		});
	});
});