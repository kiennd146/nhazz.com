<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// no direct access
defined('_JEXEC') or die;
/*
JHTML::stylesheet('vitabookLegacy.css', 'components/com_vitabook/assets/');
if($this->params->get('rounded_corners') == 1) {
    JHTML::stylesheet('vitabook_rounded.css', 'components/com_vitabook/assets/');
}

// include JS libraries

JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.modal');

JHTML::script('vitabook_Legacy.js', 'components/com_vitabook/assets/');

// populate dynamic javascript variables
JFactory::getDocument()->addScriptDeclaration("
    window.addEvent('domready', function(){     // initialize variables
        vitabook.url_base = '".JRoute::_('index.php?option=com_vitabook')."';
        vitabook.token = '".JUtility::getToken()."';
    });
");

// configurable css settings
// Get values from parameters

$introtext_color = $this->params->get('vb_introtext_color');
$introtext_background = $this->params->get('vb_introtext_background');
$introtext_border = $this->params->get('vb_introtext_border');
$text_color = $this->params->get('vb_text_color');
$header_background = $this->params->get('vb_header_background');
$message_background = $this->params->get('vb_message_background');
$border_color = $this->params->get('vb_border_color');
// define and override css
$style = '
    .vbContainer{color:#'.$text_color.';}
    .vbMessageHeader{background:#'.$header_background.';}
    .vbMessageMessage{background:#'.$message_background.';}
    .vbMessageHeader, .vbMessageMessage, .vbMessageForm, .vbLoadMoreMessages{border-color:#'.$border_color.';}
    .vbMessageUnpublished{background:#ffe7d7; border-color:#ffb39b;}
    .vbActiveParent{background:#e0eab9; border-color:#90b203;}
    .vbActiveParent img, .vbMessageUnpublished img{filter:alpha(opacity=40); opacity:0.4;}
    img.vbPublishControl{filter:alpha(opacity=100);	opacity:1;}
    #vbMessageForm ul{list-style-type:none;list-style:none;}
    #vbMessageForm ul li, #vbMessageForm ul > li, .vbForm li, ul.vbForm li{list-style-type:none;list-style:none;background:none;background-image:none;}
    .vbIntrotext{color:#'.$introtext_color.';background:#'.$introtext_background.';border-color:#'.$introtext_border.';}
    ';
// add to html head
JFactory::getDocument()->addStyleDeclaration( $style );

// some loose JS ?>
<script>
    window.addEvent('domready', function() { // validation handler for name
        document.formvalidator.setHandler('user',
            function (value) {
                regex = /[<|>|\"|'|%|;|(|)|&]/i;
                return !regex.test(value);
        });
    });
</script>

<script>
    vitabook.validate = function(){
        var isValid = true;

        // prepend site with http:// if no scheme is present
        if($('jform_site') && $('jform_site').value != '' && !$('jform_site').value.test('https?://','i'))
        {
            $('jform_site').value = 'http://'+$('jform_site').value;
        }

        if (!document.formvalidator.isValid($('vitabookMessageForm')))
        {
            isValid = false;
        }
    return isValid;
    }
</script>
*/
?>

<script>
(function($){
	$(document).ready(function(){
	
		var m7_count = 0;
		$.fn.createImageForm = function(name, default_value){
			m7_count += 1;
			var m7_id = name + m7_count.toString();
			if ($(".dcs_images").length >=4) return;
			var	$newImageForm ='<div class="dcs_images">';
				$newImageForm+=		'<input type="file" name="file_upload[]" id="' + m7_id + '" />';
				$newImageForm+=		'<a class="dcs_remove_img" title="Remove" href="#" >Remove</a>&nbsp;';
				$newImageForm+=		'<a class="dcs_add_img" title="Remove" href="#" >Add</a>';
				$newImageForm+='<div style="clear:both"></div>';
				$newImageForm+='</div>';
		
			$(this).append($($newImageForm));
			
		}

		$("a.dcs_add_img").live("click", function(e){
			e.preventDefault();
			$("#dcs_img_list").createImageForm("dcs_images", "");
		});
		
		$("a.dcs_remove_img").live("click", function(e){
			e.preventDefault();
			m7_count -= 1;
			$(this).parent().remove();
		});

		$("#dcs_form_submit").click(function(e){
			e.preventDefault();
			//$('form#dcs_form_create').submit();
			<?php if ($this->loggedin): ?>
			//alert("test");
			$('form#dcs_form_create').ajaxSubmit({
				beforeSubmit: function() {
					//$('#results').html('Submitting...');
					//alert("test");
				},
				success: function(data) {
					var _data = JSON.parse(data);
					if (_data.state == '1') {
						alert("Create discussion successfully");
						document.location.href=document.location.href;
					}
				}
			});
			
			<?php else: ?>
			showThem('login_pop');
			<?php endif ?>
			
		});
	});
})(jQuery);
</script>
<div id="discuss_form">
	<h2>What are you going on?</h2>
	<p>Get help for your project, share your finds and show off your Before and After</p>
	<div class="dcs_form">
		<form id="dcs_form_create" action="<?php echo JRoute::_('index.php?option=com_vitabook'); ?>" method="post" enctype="multipart/form-data">
		
		<select class="borderGrey" name="dcs_category" >
			<?php foreach($this->categories as $category): ?>
			<option value="<?php echo $category->id?>"><?php echo $category->title?></option>
			<?php endforeach; ?>
		</select>
		<input class="borderGrey" placeholder="Example title: need help for my kitchen" type="text" name="dcs_title" />
		<textarea class="borderGrey" placeholder="Tell us the details here" name="dcs_message"></textarea>
		<a class="dcs_attach_img dcs_add_img" href="#">Attach Images</a>
		<p id="dcs_img_list"></p>
		<button type="button" id="dcs_form_submit" >Post</button>
    <?php 
    // hidden fields
    echo JHtml::_('form.token');
    //echo $this->form->getInput('id');
    //echo $this->form->getInput('parent_id'); ?>
    <input type="hidden" name="task" value="message.save" />
    <input type="hidden" name="format" value="raw" />
		</form>
	</div>
</div>

<?php echo $this->loadTemplate('messageslegacy'); ?>

<div class="pagination"><?php
	echo $this->pagination->getPagesLinks(); ?>
</div>
