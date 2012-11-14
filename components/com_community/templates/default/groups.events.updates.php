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
<?php if( $events ){ ?>
<div class="cModule">
	<div><h3><span><?php echo JText::_('COM_COMMUNITY_EVENTS_UPCOMING'); ?></span></h3></div>
	<ul id="cGroups-UpcomingEvents" >
		<?php foreach($events as $event){ ?>
		<li>
			<img src="<?php echo ($event['avatar'] != '' ) ? $event['thumb'] :'components/com_community/assets/event_thumb.png'; ?>"/>
			<div class="eventDetails" >
			<a class="eventName" href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid='.$event['id']); ?>">
				<?php echo $event['title']; ?>
			</a><br />		
			<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=viewguest&eventid=' . $event['id'] . '&type='.COMMUNITY_EVENT_STATUS_ATTEND);?>"><?php echo JText::sprintf((cIsPlural($event['confirmedcount'])) ? 'COM_COMMUNITY_EVENTS_MANY_GUEST_COUNT':'COM_COMMUNITY_EVENTS_GUEST_COUNT', $event['confirmedcount']);?></a>
			</div>
		</li>		 
		<?php } ?>
	</ul>
</div>
<?php } ?>
