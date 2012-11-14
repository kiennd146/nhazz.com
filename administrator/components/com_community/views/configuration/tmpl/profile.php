<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MULTIPROFILES' ); ?></legend>
	<a href="http://www.jomsocial.com/support/docs/item/867-multiple-profile-explained.html" target="_blank"><?php echo JText::_('COM_COMMUNITY_DOC'); ?></a>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MULTIPROFILES_ENABLE' ); ?>::<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MULTIPROFILES_ENABLE_TIPS'); ?>">
						<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MULTIPROFILES_ENABLE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'profile_multiprofile' , null , $this->config->get('profile_multiprofile') , JText::_('COM_COMMUNITY_YES_OPTION') , JText::_('COM_COMMUNITY_NO_OPTION') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MULTIPROFILES_LOCK' ); ?>::<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MULTIPROFILES_LOCK_TIPS'); ?>">
						<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MULTIPROFILES_LOCK' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'profile_multiprofile_lock' , null , $this->config->get('profile_multiprofile_lock') , JText::_('COM_COMMUNITY_YES_OPTION') , JText::_('COM_COMMUNITY_NO_OPTION') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>