<?php

(defined('_VALID_MOS') OR defined('_JEXEC')) or die;

/*
*
* Comments form template
*
*/
class jtt_tpl_form extends JoomlaTuneTemplate
{
	function render() 
	{
		if ($this->getVar('comments-form-message', 0) == 1) {
			$this->getMessage( $this->getVar('comments-form-message-text') );
			return;
		}
		
		if ($this->getVar('comments-form-link', 0) == 1) {
			$this->getCommentsFormLink();
			return;
		}

		$this->getCommentsFormFull();
	}

	/*
	 *
	 * Displays full comments form (with smiles, bbcodes and other stuff)
	 * 
	 */
	function getCommentsFormFull()
	{
		$object_id = $this->getVar('comment-object_id');
		$object_group = $this->getVar('comment-object_group');

		$htmlBeforeForm = $this->getVar('comments-form-html-before');
		$htmlAfterForm = $this->getVar('comments-form-html-after');
?>
<h4><?php echo JText::_('FORM_HEADER'); ?></h4>
<?php
		if ($this->getVar( 'comments-form-policy', 0) == 1) {
?>
<div class="comments-policy"><?php echo $this->getVar( 'comments-policy' ); ?></div>
<?php
		}
?>
<?php echo $htmlBeforeForm; ?>
<a id="addcomments" href="#addcomments"></a>
<!--form id="comments-form" name="comments-form" action="javascript:void(null);"-->
<form id="comments-form" name="comments-form" action="index.php?option=com_jcomments&tmpl=component&jtxf=JCommentsUploadImage" enctype="multipart/form-data" method="post">
<?php
		if ($this->getVar( 'comments-form-user-name', 1) == 1) {
			$text = ($this->getVar('comments-form-user-name-required', 1) == 0) ? JText::_('FORM_NAME') : JText::_('FORM_NAME_REQUIRED');
?>
<p>
	<span>
        <label for="comments-form-name"><?php echo $text; ?></label>
		<input placeholder="<?php echo $text; ?>" id="comments-form-name" type="text" name="name" value="" maxlength="<?php echo $this->getVar('comment-name-maxlength');?>" size="22" tabindex="1" />		
	</span>
</p>
<?php
		}
		if ($this->getVar( 'comments-form-user-email', 1) == 1) {
			$text = ($this->getVar('comments-form-email-required', 1) == 0) ? JText::_('FORM_EMAIL') : JText::_('FORM_EMAIL_REQUIRED');
?>
<p>
	<span>
        <label for="comments-form-email"><?php echo $text; ?></label>
		<input placeholder="<?php echo $text; ?>" id="comments-form-email" type="text" name="email" value="" size="22" tabindex="2" />
	</span>
</p>
<?php
		}
		if ($this->getVar('comments-form-user-homepage', 0) == 1) {
			$text = ($this->getVar('comments-form-homepage-required', 1) == 0) ? JText::_('FORM_HOMEPAGE') : JText::_('FORM_HOMEPAGE_REQUIRED');
?>
<p>
	<span>
        <label for="comments-form-homepage"><?php echo $text; ?></label>
		<input placeholder="<?php echo $text; ?>" id="comments-form-homepage" type="text" name="homepage" value="" size="22" tabindex="3" />
	</span>
</p>
<?php
		}
		if ($this->getVar('comments-form-title', 0) == 1) {
			$text = ($this->getVar('comments-form-title-required', 1) == 0) ? JText::_('FORM_TITLE') : JText::_('FORM_TITLE_REQUIRED');
?>
<p>
	<span>
        <label for="comments-form-title"><?php echo $text; ?></label>
		<input placeholder="<?php echo $text; ?>" id="comments-form-title" type="text" name="title" value="" size="22" tabindex="4" />
	</span>
</p>
<?php
		}
?>
<p>
	<span>
		<?php
		require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
		$user = CFactory::getUser();
		$avatar = $user->getAvatar();
		$image = '<img class="userBoxAvatar" src="'. $avatar .'" alt="" border="0"/>';
		
		$user2 =& JFactory::getUser();
		if (!$user2->guest) {
		    echo $image;
		}
		?>
		
		<!--input placeholder="<?php //echo "Nội dung bình luận"; ?>" type="text"  id="comments-form-comment" name="comment" /-->
		<textarea placeholder="<?php echo "Nội dung bình luận"; ?>" id="comments-form-comment" name="comment"></textarea>
          
		<?php if (!$user2->guest) { ?>
		<a class="dcs_attach_img dcs_add_img" href="#"><?php echo JText::_('VITABOOK_LIST_BUTTON_ATTACH') ?></a>
		<p id="dcs_img_list"></p>
		<?php } ?>				
		<input type="hidden" id="dcs_comment_id" name="dcs_comment_id" />
				
	</span>
</p>
<?php if ($this->getVar('comments-form-subscribe', 0) == 1) { ?>
<p>
	<span>
        <label for="comments-form-subscribe"><?php echo JText::_('FORM_SUBSCRIBE'); ?></label>
		<input class="checkbox" id="comments-form-subscribe" type="checkbox" name="subscribe" value="1" tabindex="5" />		
	</span>
</p>
<?php } ?>

<?php 
		if ($this->getVar('comments-form-captcha', 0) == 1) {
			$html = $this->getVar('comments-form-captcha-html');
			if ($html != '') {
				echo $html;
			} else {
				$link = JCommentsFactory::getLink('captcha');
?>
<p>
	<span>
		<img class="captcha" onclick="jcomments.clear('captcha');" id="comments-form-captcha-image" src="<?php echo $link; ?>" width="121" height="60" alt="<?php echo JText::_('FORM_CAPTCHA'); ?>" /><br />
		<span class="captcha" onclick="jcomments.clear('captcha');"><?php echo JText::_('FORM_CAPTCHA_REFRESH'); ?></span><br />
		<input class="captcha" id="comments-form-captcha" type="text" name="captcha_refid" value="" size="5" tabindex="6" /><br />
	</span>
</p>
<?php
			}
		}
?>

<div id="comments-form-buttons">
	<div class="btn" id="comments-form-send"><div><a href="#" tabindex="7" onclick="jcomments.saveComment();return false;" title="<?php echo JText::_('FORM_SEND_HINT'); ?>"><?php echo JText::_('FORM_SEND'); ?></a></div></div>
	<div class="btn" id="comments-form-cancel" style="display:none;"><div><a href="#" tabindex="8" onclick="return false;" title="<?php echo JText::_('FORM_CANCEL'); ?>"><?php echo JText::_('FORM_CANCEL'); ?></a></div></div>
	<div style="clear:both;"></div>
</div>
<div>
	<input type="hidden" name="object_id" value="<?php echo $object_id; ?>" />
	<input type="hidden" name="object_group" value="<?php echo $object_group; ?>" />
</div>
</form>  
<script language="javascript" type="text/javascript">
//jQuery.noConflict();
	/*
    function Submit(e) {
    	
        var isEnter = window.event == null ? 
                      e.keyCode == 13 : 
                      window.event.keyCode == 13;
        if(isEnter)
             jcomments.saveComment();return false;
    }
    */
   
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
		
		$("a.dcs_remove_img").live("click", function(e){
			e.preventDefault();
			m7_count -= 1;
			$(this).parent().remove();
		});
		
	});
})(jQuery);

function dcsSetupPhotos(str_photos){
	var photos = JSON.parse(str_photos);
	
	jQuery("#dcs_img_list").empty();
	
	for (i =0;i<photos.length;i++) {
	    jQuery("#dcs_img_list").createImageForm("dcs_images", photos[i]['origin'], photos[i]['json']);
	}
	
} 

</script>
<script type="text/javascript">
<!--
function JCommentsInitializeForm()
{
	var jcEditor = new JCommentsEditor('comments-form-comment', true);
<?php
		if ($this->getVar('comments-form-bbcode', 0) == 1) {
			$bbcodes = array( 'b'=> array(0 => JText::_('FORM_BBCODE_B'), 1 => JText::_('BBCODE_HINT_ENTER_TEXT'))
					, 'i'=> array(0 => JText::_('FORM_BBCODE_I'), 1 => JText::_('BBCODE_HINT_ENTER_TEXT'))
					, 'u'=> array(0 => JText::_('FORM_BBCODE_U'), 1 => JText::_('BBCODE_HINT_ENTER_TEXT'))
					, 's'=> array(0 => JText::_('FORM_BBCODE_S'), 1 => JText::_('BBCODE_HINT_ENTER_TEXT'))
					, 'img'=> array(0 => JText::_('FORM_BBCODE_IMG'), 1 => JText::_('BBCODE_HINT_ENTER_FULL_URL_TO_THE_IMAGE'))
					, 'url'=> array(0 => JText::_('FORM_BBCODE_URL'), 1 => JText::_('BBCODE_HINT_ENTER_FULL_URL'))
					, 'hide'=> array(0 => JText::_('FORM_BBCODE_HIDE'), 1 => JText::_('BBCODE_HINT_ENTER_TEXT_TO_HIDE_IT_FROM_UNREGISTERED'))
					, 'quote'=> array(0 => JText::_('FORM_BBCODE_QUOTE'), 1 => JText::_('BBCODE_HINT_ENTER_TEXT_TO_QUOTE'))
					, 'list'=> array(0 => JText::_('FORM_BBCODE_LIST'), 1 => JText::_('BBCODE_HINT_ENTER_LIST_ITEM_TEXT'))
					);

			foreach($bbcodes as $k=>$v) {
				if ($this->getVar('comments-form-bbcode-' . $k , 0) == 1) {
					$title = trim(JCommentsText::jsEscape($v[0]));
					$text = trim(JCommentsText::jsEscape($v[1]));
?>
	//jcEditor.addButton('<?php echo $k; ?>','<?php echo $title; ?>','<?php echo $text; ?>');
<?php
				}
			}
		}

		$customBBCodes = $this->getVar('comments-form-custombbcodes');
		if (count($customBBCodes)) {
			foreach($customBBCodes as $code) {
				if ($code->button_enabled) {
					$k = 'custombbcode' . $code->id;
					$title = trim(JCommentsText::jsEscape($code->button_title));
					$text = empty($code->button_prompt) ? JText::_('BBCODE_HINT_ENTER_TEXT') : JText::_($code->button_prompt);
					$open_tag = $code->button_open_tag;
					$close_tag = $code->button_close_tag;
					$icon = $code->button_image;
					$css = $code->button_css;
?>
	jcEditor.addButton('<?php echo $k; ?>','<?php echo $title; ?>','<?php echo $text; ?>','<?php echo $open_tag; ?>','<?php echo $close_tag; ?>','<?php echo $css; ?>','<?php echo $icon; ?>');
<?php
				}
			}
		}

		$smiles = $this->getVar( 'comment-form-smiles' );

		if (isset($smiles)) {
			if (is_array($smiles)&&count($smiles) > 0) {
?>
	jcEditor.initSmiles('<?php echo $this->getVar( "smilesurl" ); ?>');
<?php
				foreach ($smiles as $code => $icon) {
					$code = trim(JCommentsText::jsEscape($code));
					$icon = trim(JCommentsText::jsEscape($icon));
?>
	jcEditor.addSmile('<?php echo $code; ?>','<?php echo $icon; ?>');
<?php
				}
			}
		}
		if ($this->getVar( 'comments-form-showlength-counter', 0) == 1) {
?>
	jcEditor.addCounter(<?php echo $this->getVar('comment-maxlength'); ?>, '<?php echo JText::_('FORM_CHARSLEFT_PREFIX'); ?>', '<?php echo JText::_('FORM_CHARSLEFT_SUFFIX'); ?>', 'counter');
<?php
		}
?>
	jcomments.setForm(new JCommentsForm('comments-form', jcEditor));
}

<?php
	if ($this->getVar('comments-form-ajax', 0) == 1) {
?>
setTimeout(JCommentsInitializeForm, 100);
<?php
	} else {
?>
if (window.addEventListener) {window.addEventListener('load',JCommentsInitializeForm,false);}
else if (document.addEventListener){document.addEventListener('load',JCommentsInitializeForm,false);}
else if (window.attachEvent){window.attachEvent('onload',JCommentsInitializeForm);}
else {if (typeof window.onload=='function'){var oldload=window.onload;window.onload=function(){oldload();JCommentsInitializeForm();}} else window.onload=JCommentsInitializeForm;} 
<?php
	}
?>
//-->
</script>
<?php echo $htmlAfterForm; ?>
<?php
	}

	/*
	 *
	 * Displays link to show comments form
	 *
	 */
	function getCommentsFormLink()
	{
		$object_id = $this->getVar('comment-object_id');
		$object_group = $this->getVar('comment-object_group');
?>
<div id="comments-form-link">
<a id="addcomments" class="showform" href="#addcomments" onclick="jcomments.showForm(<?php echo $object_id; ?>,'<?php echo $object_group; ?>', 'comments-form-link'); return false;"><?php echo JText::_('FORM_HEADER'); ?></a>
</div>
<?php
	}

	/*
	 *
	 * Displays service message
	 *
	 */
	function getMessage( $text )
	{
		if ($text != '') {
?>
<a id="addcomments" href="#addcomments"></a>
<p class="message"><?php echo $text; ?></p>
<?php
		}
	}
}
?>