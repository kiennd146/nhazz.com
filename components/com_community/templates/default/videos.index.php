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

<div class="cIndex">
	<!--Featured Listing-->
	<?php echo $featuredHTML; ?><!--call video.featured.php -->

	<!--Normal Listing + Sidebar-->
	<div class="cLayout">
	
		<div class="cSidebar clrfix">
			<!--Videos Category-->
		    <div class="cModule clrfix">
			    <h3><span><?php echo JText::_('COM_COMMUNITY_VIDEOS_CATEGORY');?></span></h3>
				<ul class="cResetList cCategories">
				    <li>
					    <?php if( $category->parent == COMMUNITY_NO_PARENT && $category->id == COMMUNITY_NO_PARENT ){ ?>
						    <a href="<?php echo CRoute::_($allVideosUrl);?>"><?php echo JText::_( 'COM_COMMUNITY_VIDEOS_ALL_DESC' ); ?></a>
					    <?php }else{ ?>
						    <?php
							    $catid = '';
							    if( $category->parent != 0) {
								    $catid = '&catid=' . $category->parent;
							    }
						    ?>
						    <a href="<?php echo CRoute::_('index.php?option=com_community&view=videos' . $catid ); ?>"><?php echo JText::_('COM_COMMUNITY_BACK_TO_PARENT'); ?></a>
					    <?php }  ?>
				    </li>

					<?php if( $categories ): ?>
					    <?php foreach( $categories as $row ): ?>
					    <li>
							    <a href="<?php echo CRoute::_($catVideoUrl . $row->id ); ?>">
									    <?php echo JText::_($this->escape($row->name)); ?>
							    </a> <span class="cCount"><?php echo empty($row->count) ? '' : $row->count; ?></span>
					    </li>
					    <?php endforeach; ?>

					<?php else: ?>
					    <li><?php echo JText::_('COM_COMMUNITY_GROUPS_CATEGORY_NOITEM'); ?></li>
					<?php endif; ?>

				</ul>
				<div class="clr"></div>
		    </div>

			<?php if (count($featuredVideoUsers)>1) { ?>
			<div class="cModule video-full">
				<h3><span><?php echo JText::_('COM_COMMUNITY_VIDEOS_FEATURED_USERS');?></span></h3>
				<div class="other-videos-container">
					<ul>
						<?php
						$featuredUser = array();
					
						foreach($featuredVideoUsers as $featuredVideo) {
						    
						if(!in_array($featuredVideo->creator, $featuredUser)) {
						?>
					<li>
						  
							    <div class="cVideoThumbs">
							    <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$featuredVideo->creator); ?>" >
								   <img class="small-avatar" src="<?php echo CFactory::getUser($featuredVideo->creator)->getThumbAvatar(); ?>" width="50" height="50" />
							    </a>
							    </div>
							    <div class="video-meta">
							    <div class="video-name">
										<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$featuredVideo->creator); ?>">
											<?php echo $featuredVideo->getCreatorName();  ?>
										</a>
							    </div>
							    <div><a href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=myvideos&userid='.$featuredVideo->creator); ?>"><?php echo $this->view('videos')->getUserTotalVideos($featuredVideo->creator); ?> <?php if($this->view('videos')->getUserTotalVideos($featuredVideo->creator)){ echo JText::_('COM_COMMUNITY_SEARCH_VIDEOS_TITLE'); } else { echo JText::_('COM_COMMUNITY_SINGULAR_VIDEO'); } ?></a></div>
							    </div>
							    <div class="clr"></div>

						     
					</li>
					<?php	     
						    $featuredUser[] = $featuredVideo->creator;
						}
					} //end foreach ?>
				</ul>				
			</div>
			</div>
			<?php } ?>
			
			
		</div><!--.cSidebar-->

		<div class="cMain jsApLf mvLf jsItms">
			<?php echo $sortings; ?>

			<div class="cVideoIndex">
				<?php echo $videosHTML; ?><!--call video.list.php -->
			</div>
		</div><!--.cMain-->
	</div><!--.cLayout-->
	


</div><!--.index-->