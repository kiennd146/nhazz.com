<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 */
defined('_JEXEC') or die();
?>
<?php if( $albums ){ ?>
<div class="cModule">
	<!-- top title -->
	<div>
		<h3><span><?php echo JText::_('COM_COMMUNITY_GROUPS_LATEST_ALBUM_UPDATE_TITLE'); ?></span></h3>
	</div>
		<ul id="cGroups-AlbumUpdates" >
	<?php foreach($albums as $album){ ?>
		<li title="blah blah">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=album&albumid='.$album['album_id'].'&groupid='.$album['groupid']); ?> "><img class="jomNameTips" src="<?php echo $album['album_thumb']; ?>" title=" <?php echo $album['album_name']; ?> " />
			</a>
			<div class="albumDetails" >
				<?php echo JText::_('COM_COMMUNITY_PHOTOS_ALBUM_IN'); ?>
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=album&albumid='.$album['album_id']); ?>"> 	
					<?php echo $album['group_name']; ?>
				</a> 				
				<br/>
			<?php echo JText::sprintf('COM_COMMUNITY_UPLOADED_BY', CRoute::_('index.php?option=com_community&view=profile&userid='.$album['creator_id']), $album['creator_name']); ?>
			</div>
		</li>
	<?php } ?>
		</ul>
</div>
<?php } ?>