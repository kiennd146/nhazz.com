<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	$discussions	An array of discussions object
 * @param	$groupId		The group id
 * @param	$total			The number of total discussions 
 */
defined('_JEXEC') or die();
?>
	<?php if( $discussions ) {?>
	<ul class="cGroups-UpdateListing" >
		<li><h3><?php echo JText::_('COM_COMMUNITY_GROUPS_PARTICIPATED_DISCUSSION_UPDATE'); ?></h3></li>

		<?php foreach($discussions as $discussion){ ?>
		<li>			
			<div class="userInfo">
			<img src="<?php echo CFactory::getUser($discussion['post_by'])->getThumbAvatar(); ?>" />	
				<div class="userShout">
					<p class="discussionTitle" ><a href="<?php echo $discussion['discussion_link'] ?>" ><?php echo $discussion['title']; ?></a> <span class="arrow">&#9654;</span> <a href="<?php echo $discussion['group_link']; ?>" > <?php echo $discussion['group_name']; ?> </a></p>
					<p class="shout"><?php echo substr($discussion['comment'],0,250); if(strlen($discussion['comment']) > 250){echo ' ...';} ?></p>		
					<p class="status"><?php echo $discussion['created_by']; ?>&nbsp;&#8226;&nbsp;<?php echo $discussion['created_interval']; ?>
				</p>
				</div>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>