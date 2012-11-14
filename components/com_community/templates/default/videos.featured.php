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
?>

	<!-- Slider Kit compatibility -->
		<!--[if IE 6]><?php CAssets::attach('assets/featuredslider/sliderkit-ie6.css', 'css'); ?><![endif]-->
		<!--[if IE 7]><?php CAssets::attach('assets/featuredslider/sliderkit-ie7.css', 'css'); ?><![endif]-->
		<!--[if IE 8]><?php CAssets::attach('assets/featuredslider/sliderkit-ie8.css', 'css'); ?><![endif]-->

		<!-- Slider Kit scripts -->
		<?php CAssets::attach('assets/featuredslider/sliderkit/jquery.sliderkit.1.8.js', 'js'); ?>

		<!-- Slider Kit launch -->
		<script type="text/javascript">
			joms.jQuery(window).load(function(){

				<?php if(JRequest::getVar('limitstart')!="" || JRequest::getVar('sort')!="" || JRequest::getVar('catid')!=""){?>
				    var target_offset = joms.jQuery("#lists").offset();
				    var target_top = target_offset.top;
				    joms.jQuery('html, body').animate({scrollTop:target_top}, 200);
				<?php } ?>

				jax.call('community' , 'videos,ajaxShowVideoFeatured' , <?php echo $videos[0]->id; ?> );
				joms.jQuery(".featured-video").sliderkit({
					shownavitems:5,
					scroll:<?php echo $config->get('featuredvideoscroll'); ?>,
					// set auto to true to autoscroll
					auto:false,
					mousewheel:true,
					circular:true,
					scrollspeed:500,
					autospeed:10000,
					start:0
				});
				joms.jQuery('.cBoxPad').click(function(){
				    jax.call('community' , 'videos,ajaxShowVideoFeatured' , joms.jQuery(this).attr("id") );
				});

				

			});

			function updatePlayer(embedCode, title, likes, view, wallCount, videoLink, videoCommentLink, creatorName, creatorLink){
			  joms.jQuery('.cPlayer').html(embedCode);
			  joms.jQuery('.ctitle').children().children().html(title);
			  joms.jQuery('#featured-view').html(view);
			  joms.jQuery('#featured-wall-count').html(wallCount);
			  joms.jQuery('#creator-name').html(creatorName);
			  creatorLink = creatorLink.replace(/\&amp;/g,'&');
			  joms.jQuery('#creator-link').attr('href',creatorLink);
			  joms.jQuery('#like-container').html(likes);
			  videoLink = videoLink.replace(/\&amp;/g,'&');
			  joms.jQuery('.ctitle').children().children().attr('href',videoLink);
			  videoCommentLink = videoCommentLink.replace(/\&amp;/g,'&');
			  joms.jQuery('#featured-video-comment-link').attr('href',videoCommentLink);

			  joms.jQuery(".video-player").children('iframe').attr('src',function() {
				return this.src + "?wmode=opaque";
			    });
			}

		</script>


<?php
if ($videos)
{
?>

<div id="cFeatured">
	<div class="cFeaturedContent">
		<!--video player-->
		<div class="cPlayer"></div>

		<!--title, comments, desc etc-->
		<div class="cMeta">
			<ul class="clrfix">
				<li class="ctitle"><h3><a id="featured-video-link" href=""><?php echo $videos[0]->title; ?></a></h3></li>
				<li class="cLike"><div id="like-container"></div></li>
				<li class="cAuthor"><a id="creator-link" href=""><span id="creator-name"></span></a></li>
				<li class="cHits"><span id="featured-view"></span> <?php echo JText::_('COM_COMMUNITY_VIDEOS_HITS') ?></li>
				<li class="cComCount"><a id="featured-video-comment-link" href=""><span id="featured-wall-count"></span> <?php echo JText::_('COM_COMMUNITY_COMMENTS'); ?></a></li>
		    </ul>
		</div>

		<!--slider-->
		<div class="cSlider featured-video">
			<div class="cSlider-nav">
				<div class="cSlider-nav-clip">
					<ul class="clrfix">

					    <?php $x = 0; foreach($videos as $video) { ?>
				    	<li id="cVideo<?php echo $video->id; ?>" style="width: <?php echo $videoThumbWidth; ?>px;">
						     <div id="<?php echo $video->id; ?>" class="cBoxPad cBoxBorder cBoxBorderLow">

							    <a class="cFeaturedImg" href="javascript:void(0);" style="width: <?php echo $videoThumbWidth; ?>px; height:<?php echo $videoThumbHeight; ?>px;">
								    <img src="<?php echo $video->getThumbnail(); ?>" alt="<?php echo $this->escape($video->title);?>" style="width: <?php echo $videoThumbWidth; ?>px; height:<?php echo $videoThumbHeight; ?>px;"/>
								    <span class="cVideoDurationHMS"><?php echo $video->getDurationInHMS(); ?></span>
							    </a>
							    <?php
							    if( $isCommunityAdmin )
							    {
							    ?>
								    <div class="album-actions small">
										<a class="album-action remove-featured" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $video->getId();?>','videos');" href="javascript:void(0);">
										<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>
										</a>
					    			</div>
								<?php } ?>
								    <div class="cFeaturedTitle">
										<?php if ($video->isPending()) {
										    	echo $video->getTitle();
											} else {	?>
						    					<a href="javascript:void(0);"><?php echo $video->getTitle(); ?></a>
										<?php } ?>
					    			</div>



							    <div class="cFeaturedMeta">
										<?php echo JText::_('COM_COMMUNITY_BY') ?>
										<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$video->creator); ?>">
											<?php echo $video->getCreatorName(); ?>
										</a>
				    			</div><!--cVideoDetails-->


							<br class="clr" />

						     </div>
				    </li>
				    <?php
				    } // end foreach
				    ?>
				    </ul>
				</div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-prev"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_PREVIOUS_BUTTON');?>"><span>Previous</span></a></div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-next"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_NEXT_BUTTON');?>"><span>Next</span></a></div>
			</div>
		</div><!--.cSlider-->
	</div><!--.cFeaturedContent-->

	<div class="cFeaturedSlider">
	</div><!--.cFeaturedSlider-->
</div><!--cFeatured-->

    <div class="clr"></div>
<?php
}