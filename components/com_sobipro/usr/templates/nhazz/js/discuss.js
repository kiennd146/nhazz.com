(function($) {
	$(document).ready(function(){
		$(".dcs_comment_photos").click(function(){
			var photo_id = $(this).attr("photo_id");
			var target_id = 'discuss_form_photo';
			var $target = $("#" + target_id);
			var logged = $target.attr("logged");

			if (logged == '0') {
			    showThem('login_pop');
			}
			else {
			    showThem(target_id);
			    $('input#dcs_photo_id', $target).val(photo_id);
			}
			
		});
	});
})(jQuery);