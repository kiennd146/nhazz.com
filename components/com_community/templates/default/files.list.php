<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @params	groups		An array of events objects.
 */
defined('_JEXEC') or die();
?>

<div class="cModule">
    
    <?php if(!empty($data)) { ?>
    <h3><?php echo JText::_('COM_COMMUNITY_FILES_AVAILABLE')?></h3>
    
    <ul class="cResetList">
            <?php for($i=0;$i<=4;$i++){?>
				<?php if(!empty($data[$i])){?>
					<li id="file_<?php echo $data[$i]->id?>" >
						<?php if($data[$i]->deleteable) {?>
							<span class="cDeleteFile" onClick="joms.file.ajaxDeleteFile('<?php echo $type?>',<?php echo $data[$i]->id?>);return false;">delete</span>
						<?php }?>
						<a href="javascript:void(0);" onClick ="joms.file.ajaxdownloadFile('<?php echo $type ?>','<?php echo $data[$i]->id ?>');" class="jomNameTips" title="<?php echo JText::sprintf('COM_COMMUNITY_PHOTOS_UPLOADED_BY' , $data[$i]->user->getDisplayName() );?>" ><?php echo CStringHelper::truncate(strip_tags($data[$i]->name),40); ?></a>
						<span class="downloaded">&#11015;</span><span class="details"> <?php echo ($data[$i]->hits > 1 ) ? JText::sprintf('COM_COMMUNITY_FILE_HIT_PLURAL',$data[$i]->hits) : JText::sprintf('COM_COMMUNITY_FILE_HIT_SINGULAR',$data[$i]->hits) ?> <?php echo $data[$i]->filesize; ?></span>
					</li>
				<?php }?>
            <?php }?>
    </ul>

    <?php } else { ?>
                <h3><?php echo JText::_('COM_COMMUNITY_FILES_NO_FILE')?></h3>
    <?php }?>
    <div class="app-box-footer">
        <?php if($permission){?>
            <a href="javascript:void(0)" style="float:left" onClick="joms.file.showFileUpload('<?php echo $type; ?>',<?php echo $id;?>)"><?php echo JText::_('COM_COMMUNITY_FILES_UPLOAD');?></a>
        <?php }?>
        <?php if(count($data)>5) { ?>
            <a href="javascript:void(0)" onClick="joms.file.viewMore('<?php echo $type?>',<?php echo $id?>)"><?php echo JText::_('COM_COMMUNITY_MORE'); ?></a>
        <?php }?>
    </div>
    <div class="clr"></div>
</div>