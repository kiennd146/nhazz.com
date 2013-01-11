(function($) {
	$(document).ready(function(){
		$(".dcs_comment_photos").click(function(){
			var photo_id = $(this).attr("photo_id");
			showThem('discuss_form_photo');
			$("#discuss_photo_id").val(photo_id);
		});
	});
})(jQuery);