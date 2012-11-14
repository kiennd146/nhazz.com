<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	applications	An array of applications object
 * @param	pagination		JPagination object 
 */
defined('_JEXEC') or die();
?>

<!-- Slider Kit styles -->
		<!-- Slider Kit compatibility -->
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="<?php echo JURI::root(); ?>components/com_community/assets/featuredslider/sliderkit-ie6.css" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php echo JURI::root(); ?>components/com_community/assets/featuredslider/sliderkit-ie7.css" /><![endif]-->
		<!--[if IE 8]><link rel="stylesheet" type="text/css" href="<?php echo JURI::root(); ?>components/com_community/assets/featuredslider/sliderkit-ie8.css" /><![endif]-->

		<!-- Slider Kit scripts -->
		<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_community/assets/featuredslider/sliderkit/jquery.sliderkit.1.8.js"></script>

		<!-- Slider Kit launch -->
<script>
	joms.jQuery(document).ready(function(){

				<?php if(JRequest::getVar('limitstart')!=""){?>
				    var target_offset = joms.jQuery("#lists").offset();
				    var target_top = target_offset.top;
				    joms.jQuery('html, body').animate({scrollTop:target_top}, 200);
				<?php } ?>

		joms.jQuery(".featured-photo").sliderkit({
					    shownavitems:5,
					    scroll:<?php echo $config->get('featuredalbumscroll'); ?>,
					    // set auto to true to autoscroll
					    auto:false,
					    mousewheel:true,
					    circular:true,
					    scrollspeed:200,
					    autospeed:10000,
					    start:0
		});

		joms.jQuery('div.alb_'+0).show();
		jax.call('community' , 'photos,ajaxShowPhotoFeatured' , 0, <?php echo $featuredList[0]->id; ?> );
		
		//store all the featured album div in an array
		var album_item = [];
		joms.jQuery('div.cFeaturedAlbum').each(function(i){
			album_item[i] = this;
		});
		
		//highlight the current selected navi button
		
		<?php if(count($featuredList) > 1) : ?>

		joms.jQuery('.cBoxPad').click(function(){
				var album_id = joms.jQuery(this).parent().attr('id');
				album_id = album_id.split("cPhoto");
				album_id = album_id[1];
				var id = joms.jQuery(this).attr('id');
				jax.call('community' , 'photos,ajaxShowPhotoFeatured' , id, album_id );
				joms.jQuery('div.cFeaturedAlbum').hide();
				joms.jQuery(album_item[id]).show();
				});
		
		
		<?php endif; ?>
	});

	function updateGallery(id, likes, wallCount, photoCommentLink, commentCountText ){
	    joms.jQuery('.like-'+id).html(likes);
	    joms.jQuery('#featured-wall-count-'+id).html(wallCount);
	    joms.jQuery('#comment-count-text-'+id).html(commentCountText);
	    photoCommentLink = photoCommentLink.replace(/\&amp;/g,'&');
	    joms.jQuery('#featured-photo-comment-link-'+id).attr('href',photoCommentLink);
	}
</script>
<?php

	if($featuredList)://display only if there is featured list
?>
<div id="cFeatured">
	
	<div class="cFeaturedAlbums">
	<?php
		$x = 1;
		$album_count = 0;
		foreach($featuredList as $album){
	?>
	
		<div class="cFeaturedAlbum alb_<?php echo $album_count; ?>" style="display:none;">
			<div class="community-album-details album">
				
				
				<!-- album covers -->
				<a class="cFeaturedCover" href="<?php echo CRoute::_($album->getURI()); ?>">
					<div class="cCoverWrapper"><img src="<?php echo $album->getRawCoverThumbPath();?>" alt="<?php echo $this->escape($album->name); ?>"  data="album_prop_<?php echo rand(0,200).'_'.$album->id;?>"/></div>
					<span class="cFeaturedOverlay"><?php echo JText::_('COM_COMMUNITY_STAR'); ?></span>
				</a>
					
				<!--album-actions-->
				<?php if( $isCommunityAdmin ){?>
				<div class="album-actions">
					<a class="album-action remove-featured" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $album->id;?>','photos');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?></a>
				</div>
				<?php } ?>
				
				<!-- album name/title -->	
				<div class="cFeaturedTitle"><a href="<?php echo CRoute::_($album->getURI()); ?>"><?php echo $this->escape($album->name);?></a></div>
				
				<!-- album meta -->
				<ul class="cFeaturedMeta">
					<li class="cAuthor"><?php echo CFactory::getUser($album->creator)->getDisplayName();?></li>
					<li class="cLastUpdated"><?php echo $album->lastUpdated; ?></li>
					<li class="cComment"><a id="featured-photo-comment-link-<?php echo $album_count; ?>" href=""><span id="featured-wall-count-<?php echo $album_count; ?>">0</span> <span id="comment-count-text-<?php echo $album_count; ?>"><?php echo JText::_('COM_COMMUNITY_COMMENT'); ?></span></a></li>					
					
					<?php if (!empty($album->location)): ?>
						
					<li class="cFeatured-Map">
						<?php echo $album->location;?>
					</li>
					<?php endif ?>
				</ul>
				
				<!-- description for the album -->
				<div class="cFeaturedDesc">
					<?php echo CStringHelper::truncate(strip_tags($album->description),200);?>
				</div>
				
				<!-- Photos from the album -->
				<div id="cPhotoItems" class="photo-list-item">
					<p><strong><?php echo JText::_('COM_COMMUNITY_PHOTOS_IMAGES_FROM_ALBUM');?>:</strong></p>
					<?php 
						$photos = $album->photos;
					
						for($i=0; $i<count($photos); $i++) {
							$row =& $photos[$i];
					?>
					<div class="cPhotoItem" id="photo-<?php echo $i;?>" title="<?php echo $this->escape($row->caption);?>">
						<a href="<?php echo $row->link;?>"><img src="<?php echo $row->getThumbURI();?>" id="photoid-<?php echo $row->id;?>" /></a>
					</div>

					<?php } ?>
					
					<br class="clr" />
				</div>
				
				 <div id="like-container" class="like-<?php echo $album_count; ?>"></div>
				<!-- tagged person -->
				<?php if ($album->tagged): ?>
				<div class="cFeaturedTagged">
					<strong><?php echo JText::_('COM_COMMUNITY_PHOTOS_IN_THIS_ALBUM'); ?></strong>
					<div>
						<?php 
						$totalpeople = sizeof($album->tagged); 
						$count = 1; 
						foreach($album->tagged as $ppl):
							
							//max tagged = 5
							if($count > 5){
								break;
							}
						?>
							<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $ppl->id); ?>">
								<?php 
									echo $ppl->getDisplayName(); 
									if($count < $totalpeople){ echo ","; } 
								?>
							</a>
					   
						<?php 
						$count++;
						endforeach; 
						?>
					</div>
				</div>
				<?php endif; ?>				
				
				<br class="clr" \>
			</div>	
		</div>
	<!--.cFeaturedAlbum-->
	<?php
			$x++;
			$album_count++;
		} // end foreach
		
	?>
	</div>
	<div class="clr"></div>
	
	<!-- navigation container -->
	<div class="cFeaturedContent">
	    <!--#####SLIDER#####-->
		<div class="cSlider featured-photo">
			<div class="cSlider-nav">
				<div class="cSlider-nav-clip">
					<ul class="clrfix">

					    <?php $album_count = 0; $x = 0; foreach($featuredList as $album) { ?>
				    	<li id="cPhoto<?php echo $album->id; ?>" class="<?php echo $album->id;?>">
						     <div id="<?php echo $album_count; ?>" class="cBoxPad cBoxBorder cBoxBorderLow">

							    <a class="cFeaturedImg" href="javascript:void(0);">
								    <img src="<?php echo $album->getCoverThumbPath(); ?>" alt="<?php echo $this->escape($album->name);?>" />
							    </a>

							 <?php if( $isCommunityAdmin ){?>
							    <div class="album-actions">
								    <a class="album-action remove-featured" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $album->id;?>','photos');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?></a>
							    </div>
							    <?php } ?>
							    <div class="cFeaturedTitle"><a href="<?php echo CRoute::_($album->getURI()); ?>"><?php echo $this->escape($album->name);?></a></div>
							  
		    					<div class="cFeaturedMeta">
									<?php echo JText::_('COM_COMMUNITY_BY').' '.CFactory::getUser($album->creator)->getDisplayName();?><br>
								</div>	
								<br class="clr" />

							</div>
				    	</li>
				    	<?php 
				    			$album_count++;
				    		} // end foreach
				    	?>
				    </ul>
				</div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-prev"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_PREVIOUS_BUTTON');?>"><span>Previous</span></a></div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-next"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_NEXT_BUTTON');?>"><span>Next</span></a></div>
			</div>
		</div><!--.cSlider-->
	</div>
</div>
<?php endif; ?>
<!-- end #cFeatured -->

	
<div class="ctitle"><?php echo JText::_('COM_COMMUNITY_PHOTOS_PHOTO_ALBUMS');?></div>