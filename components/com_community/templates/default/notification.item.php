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

<div id="notificationStream">            

<!-- 	<?php if (count ($iRows) > 0 ) { ?>
		<div class="subject"><?php echo JText::_('COM_COMMUNITY_ACTIVITY_UPDATE_NOTIFICATION') . ':'; ?></div>

	<?php }//end if ?> -->

	<?php $clock = JURI::root(). 'components/com_community/assets/clock.gif';?>
	<?php foreach ( $iRows as $row ) : ?>
	<div class="mini-profile" id="noti-request-group-<?php echo $row->id; ?>">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		    <tr>
		        <td class="avatar">
		            <a href="<?php echo $row->url; ?>">
						<img width="26" src="<?php echo $row->actorAvatar; ?>" class="cAvatar" alt="<?php echo $this->escape($row->actorName); ?>"/>
					</a>
				</td>
				<td class="message">
					<div>
					    <span id="notification-msg-<?php echo $row->id; ?>" class="notification-msg-item">
					    	<?php echo CContentHelper::injectTags($row->content,$row->params,true); ?>
					    </span>
					</div>
					<div class="time">
					    <span id="notification-time-<?php echo $row->id; ?>">
					    	<img src="<?php echo $clock; ?>">&nbsp;<?php echo $row->timeDiff; ?>
					    </span>
					</div>
				</td>

			</tr>
		</table>
	</div>
	<?php endforeach; ?>
</div>
<script>
joms.jQuery(".notification-msg-item a").each(function(key,val){
	joms.jQuery(val).attr("target","_blank");
	joms.jQuery(val).click(function(e){
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();	
	});
});
joms.jQuery(".mini-profile").each(function(key,val){
	joms.jQuery(val).click(function(e){
		var link = joms.jQuery(this).find(".avatar a").attr("href");
		if (link.length > 0){
			window.open(link,null);
		}
	});
});
</script>