<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	author		string
 * @param	categories	An array of category objects.
 * @params	groups		An array of group objects.
 * @params	pagination	A JPagination object.
 * @params	isJoined	boolean	determines if the current browser is a member of the group
 * @params	isMine		boolean is this wall entry belong to me ?
 * @params	config		A CConfig object which holds the configurations for Jom Social
 * @params	sortings	A html data that contains the sorting toolbar
 */
defined('_JEXEC') or die();
CFactory::load( 'libraries' , 'messaging' );

if( $featuredList && $showFeaturedList )
{
    
    $firstuser = CFactory::getUser( $featuredList[0] );

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

				<?php if(JRequest::getVar('limitstart')!="" || JRequest::getVar('sort')!=""){?>
					var target_offset = joms.jQuery("#lists").offset();
					var target_top = target_offset.top;
					joms.jQuery('html, body').animate({scrollTop:target_top}, 200);
				<?php } ?>
				    
				jax.call('community' , 'profile,ajaxShowProfileFeatured' , <?php echo $firstuser->id; ?> );

				joms.jQuery(".featured-group").sliderkit({
					shownavitems:3,
					scroll:<?php echo $config->get('featuredmemberscroll'); ?>,
					// set auto to true to autoscroll
					auto:false,
					mousewheel:true,
					circular:true,
					scrollspeed:500,
					autospeed:10000,
					start:0
				});
				joms.jQuery('.cBoxPad').click(function(){
				    var user_id = joms.jQuery(this).parent().attr('id');
				    user_id = user_id.split("cPhoto");
				    user_id = user_id[1];

				     jax.call('community' , 'profile,ajaxShowProfileFeatured' , user_id );
				});
			});
			
			function updateFeaturedProfile(userId, username,  likes, avatar,  userLink, userUnfeature, userStatus, friendList ){
			    joms.jQuery('#like-container').html(likes);
			    joms.jQuery('#group-title').html(username);
			    joms.jQuery('#user-status').html(userStatus);
			    joms.jQuery('#group-avatar').attr('src',avatar);
			    userLink = userLink.replace(/\&amp;/g,'&');
			    joms.jQuery('#group-link').attr('href',userLink);
			    joms.jQuery('.album-actions').html(userUnfeature);
			    joms.jQuery('#friend-list').html(friendList);

			}
		</script>

		<div id="cFeatured">

			<!--.user-main-->
			<div class="cFeaturedFriends">
				<a class="cFeaturedCover" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $firstuser->id );?>" id="group-link">
					<div class="cCoverWrapper">
						<img id="group-avatar" src="<?php echo $firstuser->getAvatar( 'avatar' ); ?>" border="0" />
					</div>
					<span class="cFeaturedOverlay">star</span>
				</a>

				<?php if( $isCommunityAdmin ){?>
				<div class="album-actions">
					<a class="album-action remove-featured" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $firstuser->id;?>','users');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?></a>
				</div>
				<?php } ?>

				<!-- member information Information -->
				<div class="cMemberInfo">
				    <!-- Title -->
					<div class="cFeaturedTitle">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $firstuser->id );?>" class="group-link"><span id="group-title"><?php echo $firstuser->getDisplayName(); ?></span></a>
					</div>
				    <div id="user-status"><?php echo $firstuser->getStatus(); ?></div>
					<div id="friend-list"></div>
					
					<!--Actions-->				

				</div><!--.group-info -->

				<!-- group Top: App Like -->
				<div class="jsApLike">
					<div id="like-container">
					</div>
					<div class="clr"></div>
				</div>
				<!-- end: App Like -->
				<div style="clear: left;"></div>
			</div>
			
			<!-- navigation container -->
			<div class="cFeaturedContent">
			    <!--#####SLIDER#####-->
				<div class="cSlider featured-group">
					<div class="cSlider-nav">
						<div class="cSlider-nav-clip">
							<ul class="clrfix">

							    <?php $x = 0; foreach($featuredList as $id) { $user = CFactory::getUser( $id ); ?>
							<li id="cPhoto<?php echo $user->id; ?>" class="<?php echo $user->id;?>">
								     <div id="<?php echo $x; ?>" class="cBoxPad cBoxBorder cBoxBorderLow">

									    <a class="cFeaturedImg" href="javascript:void(0);">
										    <img class="cAvatar cAvatar-Large" src="<?php echo $user->getThumbAvatar();?>" alt="<?php echo $user->getDisplayName(); ?>"/>
									    </a>

									    <div class="cFeaturedTitle"><b><?php echo CStringHelper::truncate(strip_tags($user->getDisplayName()),25);?></b></div>

									<div class="cFeaturedMeta">
										<a class="cFeatured-icons" onclick="<?php echo CMessaging::getPopup($user->id); ?>" href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_INBOX_SEND_MESSAGE'); ?>"><span class="jsIcon1 icon-write"></span></a>
										<a class="cFeatured-icons" onclick="joms.friends.connect('<?php echo $user->id; ?>')" href="javascript:void(0)" ><span  class="jsIcon1 icon-add"></span></a>
									</div>
									    
									<?php if( $isCommunityAdmin ) { ?>
									<div class="album-actions small" style="display: none">
										<a onclick="joms.featured.remove('<?php echo $user->id;?>','search');" href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" class="album-action remove-featured">
										<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>
									</a>
									</div>
									<?php } ?>
									    
									<br class="clr" />

									</div>
							</li>
							<?php
									$x++;
								} // end foreach
							?>
						    </ul>
						</div>
						<div class="cSlider-btn cSlider-nav-btn cSlider-nav-prev"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_PREVIOUS_BUTTON');?>"><span>Previous</span></a></div>
						<div class="cSlider-btn cSlider-nav-btn cSlider-nav-next"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_NEXT_BUTTON');?>"><span>Next</span></a></div>
					</div>
				</div><!--.cSlider-->
			</div>
	</div><!--#cFeatured-->
	<div class="clr"></div>
<?php
}
?>
<?php echo $sortings; ?>
<?php if( !empty( $data ) ) { ?>
	<a id="lists" name="listing"></a>
	<?php foreach( $data as $row ) : ?>
		<?php $displayname = $row->user->getDisplayName(); ?>
		<?php if(!empty($row->user->id) && !empty($displayname)) : ?>
		<div class="mini-profile">
			<div class="mini-profile-avatar">
				<a href="<?php echo $row->profileLink; ?>"><img class="cAvatar cAvatar-Large" src="<?php echo $row->user->getThumbAvatar(); ?>" alt="<?php echo $row->user->getDisplayName(); ?>" /></a>
			</div>
			<div class="mini-profile-details">
				<h3 class="name">
					<a href="<?php echo $row->profileLink; ?>"><strong><?php echo $row->user->getDisplayName(); ?></strong></a>
				</h3>
				<div class="mini-profile-details-status"><?php echo $row->user->getStatus() ;?></div>
				<div class="mini-profile-details-action">
					<div class="jsLft">
					    <span class="jsIcon1 icon-group">
					    	<?php echo JText::sprintf( (CStringHelper::isPlural($row->friendsCount)) ? 'COM_COMMUNITY_FRIENDS_COUNT_MANY' : 'COM_COMMUNITY_FRIENDS_COUNT', $row->friendsCount);?>
					    </span>

				    <?php if( $config->get('enablepm') && $my->id != $row->user->id ): ?>
				        <span class="jsIcon1 icon-write">
				            <a onclick="<?php echo CMessaging::getPopup($row->user->id); ?>" href="javascript:void(0);">
				            <?php echo JText::_('COM_COMMUNITY_INBOX_SEND_MESSAGE'); ?>
				            </a>
				        </span>
			        <?php endif; ?>

					<?php if($row->addFriend) { ?>
					    <span class="jsIcon1 icon-add-friend">
							<?php if(isset($row->isMyFriend) && $row->isMyFriend==1){ ?>
							    <a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADDED_AS_FRIEND'); ?></span></a>
							<?php } else { ?>
							    <a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADD_AS_FRIEND'); ?></span></a>
							<?php } ?>
						</span>
					<?php } else { ?>
					    <?php if(($my->id != $row->user->id) && ($my->id !== 0)){ ?>
					     <span class="jsIcon1 icon-add-friend">
					     <a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADDED_AS_FRIEND'); ?></span></a>
					     </span>
					    <?php }elseif($my->id == 0){ ?>
                                            <span class="jsIcon1 icon-add-friend">
					     <a href="javascript:void(0)" onclick="joms.friends.connect('<?php echo $row->user->id;?>')"><span><?php echo JText::_('COM_COMMUNITY_PROFILE_ADD_AS_FRIEND'); ?></span></a>
					     </span>
					<?php }} ?>
					</div>
					<?php
					if( $isCommunityAdmin )
					{
						if( !in_array($row->user->id, $featuredList) )
						{
					?>
					<div class="jsRgt">
						<span class="jsIcon1 icon-addfeatured" id="featured-<?php echo $row->user->id;?>">
				            <a onclick="joms.featured.add('<?php echo $row->user->id;?>','search');" href="javascript:void(0);">
				            <?php echo JText::_('COM_COMMUNITY_MAKE_FEATURED'); ?>
				            </a>
				        </span>
					</div>
					<?php
						}
					}
					?>
				</div>

				<?php if($row->user->isOnline()): ?>
				<span class="icon-online-overlay">
			    	<?php echo JText::_('COM_COMMUNITY_ONLINE'); ?>
			    </span>
			    <?php endif; ?>


			</div>
			<div class="clr"></div>
		</div>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php echo (isset($pagination)) ? '<div class="pagination-container">'.$pagination->getPagesLinks().'</div>' : ''; ?>
<?php
	}
	else
	{
?>
		<div class="advance-not-found"><?php echo JText::_('COM_COMMUNITY_SEARCH_NO_RESULT');?></div>
<?php
	}
?>
