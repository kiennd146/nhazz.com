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

<div class="vbContainer">

    <?php if ($this->params->get('show_page_heading')) : ?>
        <h2 style="float:left;"><?php
            $page_heading = $this->params->get('page_heading');
            if(!empty($page_heading)) :
               echo $this->escape($page_heading);
            else :
                echo $this->escape(JFactory::getApplication()->getMenu()->getActive()->title); 
            endif; ?>
        </h2>
    <?php endif; ?>

    <?php
    // Open Vitabook avatar system
    if(($this->params->get('vbAvatar') == 1) && (JFactory::getUser()->get('id') != 0))
    { ?>
        <a class="vbAvatarLink modal" href="<?php echo JRoute::_('index.php?option=com_vitabook&task=avatar.viewform') ?>" rel="{size:{x: 550, y: 250}, handler:'iframe'}">
            <?php echo JText::_('COM_VITABOOK_AVATAR'); ?>
        </a>
        <div class="clr"></div><?php
    } ?>

    <?php
    // Show intro-text if set
    if($this->params->get('introtext'))
    {
        echo '<div class="clr"></div>';
        echo '<div class="vbIntrotext"><p>'.$this->params->get('introtext').'</p></div>';
    }
    ?>

    <?php
    // new message button only if user is allowed to create messages
    if(VitabookHelper::getActions()->get('vitabook.create.new'))
    { ?>
        <div id="vbReplyButton"> 
            <button class="button" onclick="vitabook.fresh();"><?php echo JText::_('COM_VITABOOK_NEW_MESSAGE'); ?></button>
        </div>
        <?php
    }

    ?>
    <div class="clr"></div>

    <?php
    // message-form only if necessary
    if(VitabookHelper::getActions()->get('vitabook.create.new') || VitabookHelper::getActions()->get('vitabook.create.reply') || VitabookHelper::getActions()->get('core.edit') || VitabookHelper::getActions()->get('core.edit.own'))
    { ?>
        <div id="vbFormHolder">
            <div class="vbMessageForm" id="vbMessageForm" >
                <form action="<?php echo JRoute::_('index.php?option=com_vitabook'); ?>" method="post" name="vitabookMessageForm" id="vitabookMessageForm" class="form-validate"><?php //-- this id is necessary for the front/back-end detection of the vitabookupload plugin for tinymce ?>
                    <ul class="vbForm">
                    <?php // form fields, configurable layout on one row, or two rows ?>
                    
                        <li>
                        <table id="vbFormTable">
                            <tr> 
                                <td><?php echo $this->form->getLabel('title'); ?></td>
                                <td><?php echo $this->form->getInput('title'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $this->form->getLabel('category'); ?></td>
                                <td><?php echo $this->form->getInput('category'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $this->form->getLabel('message'); ?></td>
                                <td><?php echo $this->form->getInput('message'); ?></td>
                            </tr>
                            <?php //  echo $this->form->getInput('images'); ?>
                            
                        </table>
                        </li>
                       
                        
                        
                        <li id="vbMessageFormListButton">
                            <?php // Buttons inside form submit the form: therefore cancel button returns false ?>
                            <button type="button" class="button" onclick="vitabook.cancel();return false;"><?php echo JText::_('COM_VITABOOK_CANCEL'); ?></button>
                            <button type="button" class="button" onclick="vitabook.reset();return false;"><?php echo JText::_('COM_VITABOOK_FORM_RESET'); ?></button>
                            <button id="vbMessageFormSubmitButton" type="submit" class="button" onclick="this.disabled = true;$('vbAjaxBusy').show('inline');vitabook.save();return false;"><?php echo JText::_('COM_VITABOOK_FORM_SUBMIT'); ?></button><span id="vbAjaxBusy"><img src="components/com_vitabook/assets/img/spinner.gif" /></span>
                        </li>
                    </ul><?php
                    // hidden fields
                    echo JHtml::_('form.token');
                    echo $this->form->getInput('id');
                    echo $this->form->getInput('parent_id'); ?>
                    <input type="hidden" name="task" value="message.save" />
                    <input type="hidden" name="format" value="raw" />
                </form>

                <div class="clr"></div>
            </div>
        </div><?php
    }

    // show available messages ?>
    <div id="vbMessages"><?php
        echo $this->loadTemplate('messageslegacy'); ?>
    </div><?php

    // add pagination ?>
    <div class="pagination"><?php
        echo $this->pagination->getPagesLinks(); ?>
        <!-- Please do not remove the lines below -->
        <div style="text-align: right;">
            Powered by <a href="http://www.joomvita.com" target="_blank" title="JoomVita">JoomVita</a> <a href="http://www.joomvita.com/vitabook" target="_blank" title="VitaBook">VitaBook</a>
            <!-- VitaBook version 2.0.1 -->
        </div>
    </div>
</div>

<?php // give the $ function back to mootools  ?>
<script>
    if(typeof jQuery!='undefined'){
        jQuery.noConflict();
    }
</script>
