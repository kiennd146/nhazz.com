<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.framework');
?>
<div class="container-fluid">
    <div class="row-fluid">
        <h1><?php echo $this->avatar->name; ?></h1>
    </div>
    <div class="row-fluid">
        <div class="span4" style="float:left;">
            <fieldset>
                <legend><?php echo JText::_('COM_VITABOOK_AVATAR_CURRENT'); ?></legend>
                <img src="<?php echo $this->avatar->url; ?>" alt="" />
                <div class="clearfix"></div><br />
                <button class="btn button" onclick="location='<?php echo JRoute::_('index.php?option=com_vitabook&task=avatar.delete&id='.$this->avatar->id); ?>';"><?php echo JText::_('COM_VITABOOK_AVATAR_DELETE'); ?></button>
            </fieldset>
        </div>
        <div class="span8" style="float:right;">
            <fieldset>
                <legend><?php echo JText::_('COM_VITABOOK_AVATAR_UPLOAD'); ?></legend>
                <form action="<?php echo JRoute::_('index.php?option=com_vitabook&task=avatar.upload'); ?>" method="post" enctype="multipart/form-data" name="UploadForm">
                    <?php /*<input type="file" id="image" name="image" accept="image/*" />*/?>
                    <input style="display: none;" type="file" id="image" name="image" accept="image/*" onchange="$('avatar').value = $(this).value;" />
                    <div class="input-append">
                        <input id="avatar" class="input-medium" type="text" readonly="readonly" onclick="$('image').click();" />
                        <a class="btn" onclick="$('image').click();">Browse</a>
                    </div>
                    <input type="hidden" name="jid" value="<?php echo $this->avatar->id; ?>" />
                </form>
                <div class="clearfix"></div>
                <button class="btn btn-primary button" onclick="if(document.getElementById('image').value != '')document.UploadForm.submit();"><?php echo JText::_('COM_VITABOOK_AVATAR_UPLOAD'); ?></button>
            </fieldset>
        </div>
    </div>
</div>
