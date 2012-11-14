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
	<?php if( $announcements ) {?>
	<ul class="cGroups-UpdateListing" >
		<li><h3><?php echo JText::_('COM_COMMUNITY_GROUPS_ANNOUNCEMENT_UPDATE_TITLE'); ?></h3></li>

		<?php foreach($announcements as $announcement){ ?>
		<li>			
			<div class="userInfo">
			<img src="<?php echo $announcement['user_avatar']; ?>" />	
				<div class="userShout">
					<p class="discussionTitle" ><a href="<?php echo $announcement['announcement_link'] ?>" ><?php echo $announcement['title']; ?></a> <span class="arrow">&#9654;</span> <a href="<?php echo $announcement['group_link']; ?>" > <?php echo $announcement['group_name']; ?> </a></p>
					<p class="shout"><?php echo $announcement['message']; ?></p>		
					<p class="status"><?php echo $announcement['user_name'];?>&nbsp;&#8226;&nbsp;<?php echo $announcement['created_interval']; ?></p>
				</div>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>