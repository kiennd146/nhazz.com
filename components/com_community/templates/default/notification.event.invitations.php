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

<div id="notificationStream event">
	<?php foreach ( $rows as $row ) : ?>
	<div class="mini-profile" id="noti-pending-<?php echo $row->id; ?>">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		    <tr>
		        <td class="avatar">
		            <a href="<?php echo $row->url; ?>">
						<img width="26" src="<?php echo $row->eventAvatar; ?>" class="cAvatar" alt="<?php echo $this->escape($row->title); ?>"/>
					</a>
				</td>	
				<td class="message">
				<span id="msg-pending-<?php echo $row->id; ?>">
					<?php echo JText::sprintf('COM_COMMUNITY_EVENTS_INVITED_NOTIFICATION' , $row->invitor->getDisplayName() ,  '<a style="text-decoration:none;" href="'.$row->url.'">'.$row->title.'</a>'); ?>
					<br />
					<span id="noti-answer-event-<?php echo $row->id; ?>" class="notiAction">	
						<a class="action" style="text-indent:0;margin-right:5px;" href="javascript:void(0);" onclick="joms.jQuery('#noti-answer-event-<?php echo $row->id; ?>').remove(); jax.call('community' , 'notification,ajaxJoinInvitation' , '<?php echo $row->id; ?>', '<?php echo $row->eventid ?>');">
							    <?php echo JText::_('COM_COMMUNITY_EVENTS_ACCEPT'); ?>
						</a>
						<a class="action" style="text-indent: 0;" href="javascript:void(0);" onclick="joms.jQuery('#noti-answer-event-<?php echo $row->id; ?>').remove(); jax.call('community','notification,ajaxRejectInvitation','<?php echo $row->id; ?>', '<?php echo $row->eventid ?>');">
							    <?php echo JText::_('COM_COMMUNITY_EVENTS_REJECT'); ?>
						</a>
					</span>
				</span>
				</td>



						<span id="error-pending-<?php echo $row->id; ?>">
					    </span>					
				</td>
			</tr>
		</table>
	</div>
	    
	<?php endforeach; ?>
</div>