<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * 
 */
defined('_JEXEC') or die();
CAssets::attach('assets/easytabs/jquery.easytabs.min.js', 'js');
?>

<script language="javascript">
joms.jQuery(function() {

descHeight = joms.jQuery(".video-description").css('height');
descHeight = descHeight.split("px");
descHeight = parseInt(descHeight[0]);

if(descHeight>120){
    joms.jQuery(".video-description").css('height','120px').css('overflow','hidden');
} else {
    joms.jQuery(".joms-newsfeed-more").hide();
}

joms.jQuery(".more-text").click(function()
{

if(joms.jQuery(".video-description").css('overflow')=="hidden"){
    joms.jQuery(".video-description").css('height','auto').css('overflow','auto');
    joms.jQuery(".more-text").html('<?php echo JText::_("COM_COMMUNITY_HIDE_ACTIVITY") ?>');
} else {
    joms.jQuery(".video-description").css('height','120px').css('overflow','hidden');
    joms.jQuery(".more-text").html('<?php echo JText::_("COM_COMMUNITY_MORE") ?>');
}

});

joms.jQuery(".video-player").children('iframe').attr('src',function() {
	return this.src + "?wmode=opaque";
});

});
</script>


<div class="video-full" id="<?php echo "video-" . $video->getId() ?>">


	<div class="action-button">
		<span class="report"><?php echo $reportHTML;?></span>
		<span class="bookmarks"><?php echo $bookmarksHTML;?></span>
	</div><!--action-button-->

	<!--VIDEO PLAYER-->
    <div class="video-player">
			<?php echo $video->getPlayerHTML(); ?>
    </div>
    <!--end: VIDEO PLAYER-->
	
	<div class="cLayout">
		<div class="cSidebar">
			<div class="hits">
				<strong>
				<?php
				if(CStringHelper::isPlural($video->getHits())) {
					echo JText::sprintf('COM_COMMUNITY_VIDEOS_HITS_COUNT', $video->getHits());
				} else {
					echo JText::sprintf('COM_COMMUNITY_VIDEOS_HITS_COUNT_SINGULAR', $video->getHits());
				}
				?>
				</strong>
			</div>

			<div id="like-container">
				<?php echo $likesHTML; ?>
			</div><!--like-container-->


			<div class="clr"></div>
			
			<?php if (count($otherVideos)>1) { ?>
				<div class="ctitle"><h3><?php echo JText::_('COM_COMMUNITY_VIDEOS_OTHER');?></h3></div>
				<div class="other-videos-container">
					<ul>
						<?php 
						$displayCount = 0;
						foreach($otherVideos as $others) {
							$videoInfo =& JTable::getInstance( 'Video' , 'CTable' );
							$videoInfo->load($others->id);

							if ($others->id != $video->id)
							{
								$displayCount++;
							}
							else
							{
								continue;
							}
						?>
					<li>
						<div class="cVideoThumbs">
							<a href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=video&videoid=' . $others->id . '&groupid=' . $others->groupid. '&userid=' . $others->creator); ?>">
								<img class="small-avatar" src="<?php echo $videoInfo->getThumbnail(); ?>" alt="<?php echo $this->escape($others->title);?>" data="video_prop_<?php echo rand(0,200).'_'.$others->id;?>" width="50" height="50"/>
							</a>
						</div><!--.album-thumbs-->
						<div class="video-meta">
							<div class="video-name"><a href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=video&videoid=' . $others->id . '&groupid=' . $others->groupid. '&userid=' . $others->creator); ?>"><?php echo $this->escape($others->title); ?></a></div>
							<div class="video-count">
								<strong><?php echo $others->hits; ?></strong> <?php echo JText::_('COM_COMMUNITY_VIDEOS_HITS') ?>
							</div>
						</div>
						<div class="clr"></div>

						

					</li>
					<?php if ($displayCount == 5) {
							break;
						}
					} //end foreach ?>
				</ul>				
			</div>
			<?php } //end if ?>
		</div><!--cSidebar-->
		
		<div class="cMain">

			
			<div class="cVideoDetails">
				<?php echo JText::_('COM_COMMUNITY_VIDEOS_UPLOADED_BY');?> <strong><?php echo $user->getDisplayName(); ?></strong>. <?php echo JText::_('COM_COMMUNITY_VIDEOS_CREATED') ?> <strong><?php echo JHTML::_('date', $video->created, JText::_('DATE_FORMAT_LC3')); ?></strong>. 
				<?php if (!empty($video->location) && $videoMapsDefault==1):?>
					<?php echo JText::_('COM_COMMUNITY_VIDEOS_LOCATION') ?> <a class="album-map-link" onclick="joms.jQuery('#video-map').toggle();" title="<?php echo JText::_('COM_COMMUNITY_VIEW_LOCATION_TIPS');?>" href="javascript: void(0)"><?php echo $video->location; ?></a><br />
				<?php endif; ?>
			<div class="videoTextTags"><?php echo JText::_('COM_COMMUNITY_VIDEOS_IN_THIS_VIDEO'); ?> </div>
				<div class="video-tagging">
				<a id="addtagging" href="javascript:void(0);" onclick="joms.friends.showForm('', 'videos,inviteUsers','<?php echo $video->getId()?>','1','joms.videos.selectVideoTagFriends(<?php echo $video->getId()?>)');" >&#10132; <?php echo JText::_('COM_COMMUNITY_TAG_THIS_VIDEO');?></a>
			</div>
			</div><!--video details-->

			<div id="video-map" style="display: none;">
				<?php echo $zoomableMap;?>
			</div>

			<div class="cRow">
				<div class="ctitle"><h2><?php echo JText::_('COM_COMMUNITY_VIDEOS_PROFILE_VIDEO_DESCRIPTION'); ?></h2></div>
				<div class="video-description"><?php echo nl2br($video->getDescription()); ?></div>
				<div class="joms-newsfeed-more"><a href="javascript:void(0)" class="more-text"><?php echo JText::_("COM_COMMUNITY_MORE"); ?></a></div>
			</div>
			<!--<div class="cVideoSummary" style="margin-left: <?php echo $video->getWidth(); ?>px">	-->
			<a name="comments"></a>
			<div class="ctitle"><?php echo JText::_('COM_COMMUNITY_COMMENTS') ?></div>
			<div class="video-wall">
				<?php if(!empty($wallForm)){?>
					<div id="wallForm"><?php echo $wallForm; ?></div>
				<?php } ?>
				<div id="wallContent"><?php echo $wallContent; ?></div>
			</div>
		</div><!--cMain-->

	</div><!--cLayout-->
</div>

<script type="text/javascript">
	var video_tags = [
						<?php foreach($video->tagged as $tagItem){ ?>
						{
							id:     <?php echo $tagItem->id;?>,
							videoId: <?php echo $video->id; ?>,
							userId: <?php echo $tagItem->userid;?>,
							displayName: '<?php echo addslashes($tagItem->user->getDisplayName()); ?>',
							profileUrl: '<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$tagItem->userid, false);?>',
							canRemove: <?php echo $tagItem->canRemoveTag;?>
						}
						<?php $end = end($video->tagged); if($end->id != $tagItem->id) echo ',';?>
						<?php } ?>
					];
	joms.jQuery(document).ready(function(){
		joms.videos.addVideoTextTag(video_tags);
	});
</script>