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

<div ="notificationStream group">
	
	<?php foreach ( $gRows as $row ) : ?>
	<div class="mini-profile" id="noti-pending-group-<?php echo $row->groupid; ?>">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="avatar">
					<a href="<?php echo $row->url; ?>">
						<img width="26" src="<?php echo $row->groupAvatar; ?>" class="cAvatar" alt="<?php echo $this->escape($row->name); ?>"/>
					</a>
				</td>
				<td class="message">
					<div>
						<span id="msg-pending-<?php echo $row->groupid; ?>" >
					    	<?php echo JText::sprintf('COM_COMMUNITY_GROUPS_INVITED_NOTIFICATION' , $row->invitor->getDisplayName() ,  '<a style="text-decoration:none;" href="'.$row->url.'">'.$row->name.'</a>'); ?>
					    	<br />
						<span id="noti-answer-group-<?php echo $row->groupid; ?>" class="notiAction" >
						    <a class="action" href="javascript:void(0);" onclick="joms.jQuery('#noti-answer-group-<?php echo $row->groupid; ?>').remove(); jax.call('community' , 'notification,ajaxGroupJoinInvitation' , '<?php echo $row->groupid ?>');">
							    <?php echo JText::_('COM_COMMUNITY_EVENTS_ACCEPT'); ?>
						    </a>
						    <a class="action" href="javascript:void(0);" onclick="joms.jQuery('#noti-answer-group-<?php echo $row->groupid; ?>').remove(); jax.call('community','notification,ajaxGroupRejectInvitation', '<?php echo $row->groupid ?>');">
							    <?php echo JText::_('COM_COMMUNITY_EVENTS_REJECT'); ?>
						    </a>
						</span>
					    </span>

						<span id="error-pending-<?php echo $row->groupid; ?>">
					    </span>

					</div>
				</td>

			</tr>
		</table>
	</div>

	<?php endforeach; ?>
</div>