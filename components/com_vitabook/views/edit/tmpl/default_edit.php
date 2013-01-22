<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license             GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// no direct access
defined('_JEXEC') or die;

$comments = JPATH_SITE . DS .'components' . DS . 'com_jcomments' . DS . 'jcomments.php';
if (file_exists($comments)) {
    require_once($comments);
}

?>
<script>	
(function($){
	$.fn.gotoComment = function(){
		$('html,body').animate({
			scrollTop: $("#comments-form").offset().top - 200},
		'slow');
	}
	$(document).ready(function() {
		$(".dcsdt_comment_add").click(function(e){
			e.preventDefault();
			$.fn.gotoComment();
		});
		$(".dcs_comment").click(function(e){
			e.preventDefault();
			$.fn.gotoComment();
		});
		$(".dcsdt_small a").click(function(e){
			e.preventDefault();
			var src = $("img", this).attr("src");
			$("ul.dcsdt_small li").removeClass("active");
			$(".dcsdt_large img").attr("src", src);
			$(this).parent().addClass("active");
		});
	});
})(jQuery);

</script>
<?php
function renderMessage($message,$params,$parentState, $categories){ ?>
<?php
	$count = JComments::getCommentsCount($message->id, 'com_vitabook');
	$app = JFactory::getApplication();
	$templateDir = JURI::base() . 'templates/' . $app->getTemplate();
    if(is_object($message))
    { ?>
	
<script>
(function($){
	$(document).ready(function(){
		var m7_count = 0;
		$.fn.createImageForm = function(name, default_value, default_json){
			m7_count += 1;
			var m7_id = name + m7_count.toString();
			if ($(".dcs_images").length >=4) return;
			var	$newImageForm ='<div class="dcs_images">';
				if (default_value != "") {
					$newImageForm+=		'<img src="'+default_value+'" style="width:30px;height:30px;" /><input type="hidden" name="file_uploaded[]" id="' + m7_id + '" value="'+default_json+'" />';
				}
				else {
					$newImageForm+=		'<input type="file" name="file_upload[]" id="' + m7_id + '" />';
				}
				
				$newImageForm+=		'<a class="dcs_remove_img" title="Remove" href="#" ><?php echo JText::_('VITABOOK_LIST_REMOVE') ?></a>&nbsp;';
				$newImageForm+=		'<a class="dcs_add_img" title="Remove" href="#" ><?php echo JText::_('VITABOOK_LIST_ADD') ?></a>';
				$newImageForm+='<div style="clear:both"></div>';
				$newImageForm+='</div>';
		
			$(this).append($($newImageForm));
			
		}

		$("a.dcs_add_img").live("click", function(e){
			e.preventDefault();
			$("#dcs_img_list").createImageForm("dcs_images", "", "");
		});
		
		<?php foreach($message->photos as $photo):?>
		$("#dcs_img_list").createImageForm("dcs_images", "<?php echo $photo->origin?>", '<?php echo $photo->json?>');
		<?php endforeach;?>
			
		$("a.dcs_remove_img").live("click", function(e){
			e.preventDefault();
			m7_count -= 1;
			$(this).parent().remove();
		});
		
		$("button#dcs_form_submit").click(function(e){
			e.preventDefault();
			//$('form#dcs_form_create').submit();
			//return;
			$('form#dcs_form_create').ajaxSubmit({
				beforeSubmit: function() {
					$("#dcs_loading").show();
				},
				success: function(data) {
					$("#dcs_loading").hide();
					var _data = JSON.parse(data);
					if (_data.state == '1') {
						alert("<?php echo JText::_('VITABOOK_LIST_CREATE_SUCCESS') ?>");
						document.location.href=document.location.href;
					}
				}
			});
			
		});
	});
})(jQuery);
</script>
<div id="discuss_form">
	<div class="dcs_form">
		<form id="dcs_form_create" action="<?php echo JRoute::_('index.php?option=com_vitabook'); ?>" method="post" enctype="multipart/form-data">
		
		<select class="borderGrey" name="dcs_category" >
			<?php foreach($categories as $category): ?>
			<option value="<?php echo $category->id?>" <?php echo $message->category->id==$category->id?'selected':'' ?> ><?php echo $category->title?></option>
			<?php endforeach; ?>
		</select>
		<input class="borderGrey" placeholder="<?php echo JText::_('VITABOOK_LIST_HINT_TITLE') ?>" type="text" name="dcs_title" value="<?php echo $message->title; ?>" />
		<textarea class="borderGrey" placeholder="<?php echo JText::_('VITABOOK_LIST_HINT_MESSAGE') ?>" name="dcs_message"><?php echo $message->message; ?></textarea>
		<a class="dcs_attach_img dcs_add_img" href="#"><?php echo JText::_('VITABOOK_LIST_BUTTON_ATTACH') ?></a>
		<p id="dcs_img_list"></p>
		
		<div id="dcs_loading" style="display:none"><img src="<?php echo $templateDir.'/images/loading.gif'; ?>" /></div>
		<button type="button" id="dcs_form_submit" ><?php echo JText::_('VITABOOK_LIST_BUTTON_POST') ?></button>
		<?php 
		// hidden fields
		echo JHtml::_('form.token');
		?>
		<input type="hidden" name="task" value="message.save" />
		<input type="hidden" name="format" value="raw" />
		<input type="hidden" name="dcs_id" value="<?php echo $message->id; ?>" />
		</form>
	</div>
</div>

<?php } 
} 
$categories = $this->categories;
//var_dump($categories); die();
if(!empty($this->messages))
{
    foreach ($this->messages as $message):
        renderMessage($message,$this->params,1, $categories);
    endforeach;
}
?>
