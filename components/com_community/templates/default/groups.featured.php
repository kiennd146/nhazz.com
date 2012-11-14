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
<?php if ($groups) { ?>

	<!-- Slider Kit compatibility -->
		<!--[if IE 6]><?php CAssets::attach('assets/featuredslider/sliderkit-ie6.css', 'css'); ?><![endif]-->
		<!--[if IE 7]><?php CAssets::attach('assets/featuredslider/sliderkit-ie7.css', 'css'); ?><![endif]-->
		<!--[if IE 8]><?php CAssets::attach('assets/featuredslider/sliderkit-ie8.css', 'css'); ?><![endif]-->

		<!-- Slider Kit scripts -->
		<?php CAssets::attach('assets/featuredslider/sliderkit/jquery.sliderkit.1.8.js', 'js'); ?>

		<!-- Slider Kit launch -->
		<script type="text/javascript">
			joms.jQuery(window).load(function(){

				<?php if(JRequest::getVar('limitstart')!="" || JRequest::getVar('sort')!="" || JRequest::getVar('categoryid')!=""){?>
					var target_offset = joms.jQuery("#lists").offset();
					var target_top = target_offset.top;
					joms.jQuery('html, body').animate({scrollTop:target_top}, 200);
				<?php } ?>

				jax.call('community' , 'groups,ajaxShowGroupFeatured' , <?php echo $groups[0]->id; ?> );

				joms.jQuery(".featured-group").sliderkit({
					shownavitems:3,
					scroll:<?php echo $config->get('featuredgroupscroll'); ?>,
					// set auto to true to autoscroll
					auto:false,
					mousewheel:true,
					circular:true,
					scrollspeed:500,
					autospeed:10000,
					start:0
				});
				joms.jQuery('.cBoxPad').click(function(){
				    var group_id = joms.jQuery(this).parent().attr('id');
				    group_id = group_id.split("cPhoto");
				    group_id = group_id[1];
				    jax.call('community' , 'groups,ajaxShowGroupFeatured' , group_id );
				});



			});

			function updateGroup(groupId, title, categoryName, likes, avatar, groupDate, groupLink,  groupDesc, membercount, discussion, wallposts, memberCountLink, groupUnfeature ){
			    joms.jQuery('#like-container').html(likes);
			    joms.jQuery('#group-title').html(title);
			    joms.jQuery('.group-date').html(groupDate);
			    joms.jQuery('#community-group-data-category').html(categoryName);
			    joms.jQuery('#group-avatar').attr('src',avatar);
			    groupLink = groupLink.replace(/\&amp;/g,'&');
			    joms.jQuery('.group-link').attr('href',groupLink);
			    joms.jQuery('.group-desc').html(groupDesc);
			    joms.jQuery('.album-actions').html(groupUnfeature);
			    joms.jQuery('#group-membercount').html(membercount);
			    joms.jQuery('#group-discussion').html(discussion);
			    joms.jQuery('#group-wallposts').html(wallposts);
			    memberCountLink = memberCountLink.replace(/\&amp;/g,'&');
			    joms.jQuery('#group-membercount-link').attr('href',memberCountLink);
			}

		</script>
		
<div id="cFeatured">

	<div class="cGroupsMain album">
	    
		<div id="community-group-avatar" class="group-avatar">
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groups[0]->id );?>" class="group-link"><img id="group-avatar" src="<?php echo $groups[0]->getAvatar( 'avatar' ); ?>" border="0" alt="<?php echo $this->escape($groups[0]->name);?>" /></a>
		</div><!--.group-avatar -->
		
		<?php if( $isCommunityAdmin ){?>
		<div class="album-actions">
			<a class="album-action remove-featured" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $groups[0]->id;?>','groups');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?></a>
		</div>
		<?php } ?>

		<div class="group-category">
		    <div class="clabel"><?php echo JText::_('COM_COMMUNITY_EVENTS_CATEGORY'); ?>:</div>
		    <div class="cdata" id="community-group-data-category">
			    <?php echo JText::_( $groups[0]->getCategoryName() ); ?>
		    </div>
		</div><!--.group-category-->


		<!-- group Information -->
		<div class="cGroupInfo">
		    <!-- Title -->
			<div class="cFeaturedTitle">
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $groups[0]->id );?>" class="group-link"><span id="group-title"><?php echo $groups[0]->name; ?></span></a>
			</div>
			
			
			<div class="group-desc">
				<?php echo CStringHelper::truncate(strip_tags($groups[0]->description ), 300); ?>
			</div>
			
			<!-- group Time -->
			<div class="group-created">
				<span><?php echo JText::_('COM_COMMUNITY_GROUPS_CREATE_TIME');?></span> :
				<div class="group-date"> <?php echo JHTML::_('date', $groups[0]->created, JText::_('DATE_FORMAT_LC')); ?></div>
			</div>
			
			
			<!--Actions-->
			<div class="group-actions">
				<span class="jsIcon1 icon-group"><a id="group-membercount-link" href="<?php echo CRoute::_( 'index.php?option=com_community&view=groups&task=viewmembers&groupid=' . $groups[0]->id ); ?>"><span id="group-membercount"><?php echo JText::sprintf((CStringHelper::isPlural($groups[0]->membercount)) ? 'COM_COMMUNITY_GROUPS_MEMBER_COUNT_MANY':'COM_COMMUNITY_GROUPS_MEMBER_COUNT', $groups[0]->membercount);?></span></a></span>
				<span id="group-discussion" class="jsIcon1 icon-discuss"><?php echo JText::sprintf((CStringHelper::isPlural($groups[0]->discusscount)) ? 'COM_COMMUNITY_GROUPS_DISCUSSION_COUNT_MANY' :'COM_COMMUNITY_GROUPS_DISCUSSION_COUNT', $groups[0]->discusscount);?></span>
				<span id="group-wallposts" class="jsIcon1 icon-wall"><?php echo JText::sprintf((CStringHelper::isPlural($groups[0]->wallcount)) ? 'COM_COMMUNITY_GROUPS_WALL_COUNT_MANY' : 'COM_COMMUNITY_GROUPS_WALL_COUNT', $groups[0]->wallcount);?></span>
			</div><!--.groups-actions-->
			
		</div><!--.group-info -->
		
		<!-- group Top: App Like -->
		<div class="jsApLike">
			<div id="like-container">
			</div>
			<div class="clr"></div>
		</div>
		<!-- end: App Like -->
		<div style="clear: left;"></div>
	</div><!--.group-main-->

	<!-- navigation container -->
	<div class="cFeaturedContent">
	    <!--#####SLIDER#####-->
		<div class="cSlider featured-group">
			<div class="cSlider-nav">
				<div class="cSlider-nav-clip">
					<ul class="clrfix">

					    <?php $group_count = 0; $x = 0; foreach($groups as $group) { ?>
				    	<li id="cPhoto<?php echo $group->id; ?>" class="<?php echo $group->id;?>">
						     <div id="<?php echo $group_count; ?>" class="cBoxPad cBoxBorder cBoxBorderLow">

							    <a class="cFeaturedImg" href="javascript:void(0);">
								    <img src="<?php echo $group->getThumbAvatar(); ?>" alt="<?php echo $this->escape($group->name);?>" width="68px" height="68px" />
							    </a>

							    <div class="cFeaturedTitle"><b><?php echo CStringHelper::truncate(strip_tags($group->name),25);?></b></div>

		    					<div class="cFeaturedMeta">

								</div>
								<br class="clr" />

							</div>
				    	</li>
				    	<?php
				    			$group_count++;
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
<?php
}