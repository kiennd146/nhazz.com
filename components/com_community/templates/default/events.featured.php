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
		<?php 
			CAssets::attach('assets/featuredslider/sliderkit/jquery.sliderkit.1.8.js', 'js'); 
			CAssets::attach('assets/joms.jomSelect.js', 'js');
		?>

		<!-- Slider Kit launch -->
		<script type="text/javascript">
			joms.jQuery(window).load(function(){

				<?php if(JRequest::getVar('limitstart')!="" || JRequest::getVar('sort')!="" || JRequest::getVar('categoryid')!=""){?>
					var target_offset = joms.jQuery("#lists").offset();
					var target_top = target_offset.top;
					joms.jQuery('html, body').animate({scrollTop:target_top}, 200);
				<?php } ?>
				    
				jax.call('community' , 'events,ajaxShowEventFeatured' , <?php echo $events[0]->id; ?>, '<?php echo $allday; ?>' );
				
				joms.jQuery(".featured-event").sliderkit({
					shownavitems:3,
					scroll:<?php echo $config->get('featuredeventscroll'); ?>,
					// set auto to true to autoscroll
					auto:false,
					mousewheel:true,
					circular:true,
					scrollspeed:500,
					autospeed:10000,
					start:0
				});
				joms.jQuery('.cBoxPad').click(function(){
				    var event_id = joms.jQuery(this).parent().attr('id');
				    event_id = event_id.split("cPhoto");
				    event_id = event_id[1];
				    jax.call('community' , 'events,ajaxShowEventFeatured' , event_id, '<?php echo $allday; ?>' );
				});

				

			});

			function updateEvent(eventId, title, categoryName, likes, avatar, eventDate, location, summary, eventLink,rsvp, eventUnfeature){
			joms.jQuery('#like-container').html(likes);
			joms.jQuery('#event-title').html(title);
			joms.jQuery('#event-date').html(eventDate);
			joms.jQuery('#event-data-location').html(location);
			joms.jQuery('#event-summary').html(summary);
			if(rsvp==""){
			    joms.jQuery('#rsvp-container').html(rsvp);
			} else {
			   joms.jQuery('#rsvp').html(rsvp);
			}
			joms.jQuery('.album-actions').html(eventUnfeature);
			joms.jQuery('#community-event-data-category').html(categoryName);
			joms.jQuery('#event-avatar').attr('src',avatar);
			eventLink = eventLink.replace(/\&amp;/g,'&');
			joms.jQuery('.event-link').attr('href',eventLink);
			}

		</script>





<?php if ($events) { ?>
<div id="cFeatured">

	<div class="cEventMain album">
	
		<div id="rsvp-container" class="event-rvsp">
			<div id="community-event-rsvp" class="cModule">
				<h3><?php echo JText::_('COM_COMMUNITY_EVENTS_YOUR_RSVP'); ?></h3>
				<p><?php echo JText::_('COM_COMMUNITY_EVENTS_ATTENDING_QUESTION'); ?></p>
				<select onchange="joms.events.submitRSVP(<?php echo $events[0]->id;?>,this)">
				    <?php if($events[0]->getMemberStatus($my->id)==0) { ?><option class="noResponse" selected="selected"><?php echo JText::_('COM_COMMUNITY_GROUPS_INVITATION_RESPONSE')?></option> <?php }?>
				    <option class="attend" <?php if($events[0]->getMemberStatus($my->id) == COMMUNITY_EVENT_STATUS_ATTEND){echo "selected='selected'"; }?> value="<?php echo COMMUNITY_EVENT_STATUS_ATTEND; ?>"><?php echo JText::_('COM_COMMUNITY_EVENTS_RSVP_ATTEND')?></option>
				    <option class="notAttend" <?php if($events[0]->getMemberStatus($my->id) >= COMMUNITY_EVENT_STATUS_WONTATTEND ){echo "selected='selected'"; }?> value="<?php echo COMMUNITY_EVENT_STATUS_WONTATTEND; ?>"><?php echo JText::_('COM_COMMUNITY_EVENTS_RSVP_NOT_ATTEND')?></option>
				</select>
			</div>
		</div><!--.rvsp-->
		
		<div id="community-event-avatar" class="event-avatar">
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $events[0]->id );?>" class="event-link"><img id="event-avatar" src="<?php echo $events[0]->getAvatar( 'avatar' ); ?>" border="0" alt="<?php echo $this->escape($events[0]->title);?>" /></a>
		</div><!--.event-vatar -->
		<?php if( $isCommunityAdmin ){?>
		<div class="album-actions">
			<a class="album-action remove-featured" title="<?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?>" onclick="joms.featured.remove('<?php echo $events[0]->id;?>','events');" href="javascript:void(0);"><?php echo JText::_('COM_COMMUNITY_REMOVE_FEATURED'); ?></a>
		</div>
		<?php } ?>
		<div class="event-category">
		    <div class="clabel"><?php echo JText::_('COM_COMMUNITY_EVENTS_CATEGORY'); ?>:</div>
		    <div class="cdata" id="community-event-data-category">
			    <?php echo JText::_( $events[0]->getCategoryName() ); ?>
		    </div>
		</div><!--.event-category-->

		<!-- Event Information -->
		<div class="cEventInfo">
		    <!-- Title -->
			<div class="cFeaturedTitle">
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid=' . $events[0]->id );?>" class="event-link"><span id="event-title"><?php echo $events[0]->title; ?></span></a>
			</div>
			
			<!-- Event Time -->
			<div class="event-created">
				<span><?php echo JText::_('COM_COMMUNITY_EVENTS_TIME')?></span>
				<div id="event-date"></div>
			</div>
			
	    	<!-- Location info -->
			<div class="event-location">
				<span><?php echo JText::_('COM_COMMUNITY_EVENTS_LOCATION');?></span>
				<span id="event-data-location">
				    <a href="http://maps.google.com/?q=<?php echo urlencode($events[0]->location); ?>" target="_blank">
					<?php echo $events[0]->location; ?>
				</a>
				</span>
			</div>

			<!--Event Summary-->
			<div class="event-summary">
				<span><?php echo JText::_('COM_COMMUNITY_EVENTS_VIEW_SUMMARY');?></span>
				<div id="event-summary"></div>
			</div>
		</div><!--.event-info -->
		
		<!-- Event Top: App Like -->
		<div class="jsApLike">
			<div id="like-container">
				
			</div>
			<div class="clr"></div>
		</div>
		<!-- end: App Like -->
		<div style="clear: left;"></div>
	</div><!--.event-main-->

	<!-- navigation container -->
	<div class="cFeaturedContent">
	    <!--#####SLIDER#####-->
		<div class="cSlider featured-event">
			<div class="cSlider-nav">
				<div class="cSlider-nav-clip">
					<ul class="clrfix">

					    <?php $event_count = 0; $x = 0; foreach($events as $event) { ?>
				    	<li id="cPhoto<?php echo $event->id; ?>" class="clrfix <?php echo $event->id;?>">
						     <div id="<?php echo $event_count; ?>" class="cBoxPad">
								
								<div class="cEventDate">
									<?php echo substr($event->startdate,8,2); ?>
									<span><?php echo substr(CTimeHelper::getFormattedTime($event->startdate, JText::_('COM_COMMUNITY_EVENTS_TIME_FORMAT_12HR')),0,3); ?></span>
								</div>
								
								<div class="cEventMeta">
							    	<div class="cFeaturedTitle"><?php echo CStringHelper::truncate(strip_tags($event->title),12);?></div>
							    	<div class="cEventLocation"><?php echo $event->location; ?></div>
								</div>
							</div>
				    	</li>
				    	<?php
				    			$event_count++;
				    		} // end foreach
				    	?>
				    </ul>
				</div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-prev"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_PREVIOUS_BUTTON');?>"><span>Previous</span></a></div>
				<div class="cSlider-btn cSlider-nav-btn cSlider-nav-next"><a href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_NEXT_BUTTON');?>"><span>Next</span></a></div>
			</div>
		</div><!--.cSlider-->
	</div>

	<script type="text/javascript">
      joms.jQuery(function(){
        joms.jQuery("select").jomSelect();
      });
	</script>

</div><!--#cFeatured-->
<?php
}