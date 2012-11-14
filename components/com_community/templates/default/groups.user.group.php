<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @params	categories Array	An array of categories
 */
defined('_JEXEC') or die();
?>

<div class="cModule">
	<h3><span><?php echo JText::_('COM_COMMUNITY_GROUPS_MY_GROUPS'); ?></span></h3>
<?php if($usergroups) { ?>
	<ul id="cGroups-MyGroup">
	<?php foreach($usergroups as $grp): ?>
		<!-- thumbnail -->
		<li>
			<a href="<?php echo $grp['group_url'] ?>"><img class="jomNameTips" src="<?php echo $grp['avatar']; ?>" title="<?php echo $grp['group_name']; ?>" /></a>
		</li>
	<?php endforeach;?>
	</ul>

	<!-- click to go to my groups -->
	<div>
		<a href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=mygroups'); ?>"><?php echo JText::_('COM_COMMUNITY_GROUPS_MY_GROUPS'); ?></a>
	</div>
<?php
	} 
?>
</div>