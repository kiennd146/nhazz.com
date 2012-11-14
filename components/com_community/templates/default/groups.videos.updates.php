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

<?php if( $videos ){ ?>
<div class="cModule">
	<!-- top title -->
	<div><h3><span><?php echo JText::_('COM_COMMUNITY_GROUPS_VIDEO_UPDATES'); ?></span></h3></div>	
		<ul id="cGroups-VideoUpdates">	
		<?php	foreach($videos as $video){ ?>
		<li>
			<!-- thumbnail for videos -->
			<div class="videoThumb">
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=video&groupid='.$video->getId().'&videoid='.$video->getId());?>">
			<img src="<?php echo $video->getThumbnail(); ?>" />
			</a>
			<p class="videoCounter"><?php echo JText::sprintf('COM_COMMUNITY_VIDEOS_HITS_COUNT', $video->getHits()) ?></p>
			</div>	
			<p class="videoDetails"><?php echo $video->getTitle(); ?><br /> 
				<?php echo $video->getDurationInHMS(); ?> <br />
			</p>
		</li> 
		<?php } ?>
		</ul>
</div>
<?php } ?>
