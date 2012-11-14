window.addEvent('domready', function() {
	document.formvalidator.setHandler('name1',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});
