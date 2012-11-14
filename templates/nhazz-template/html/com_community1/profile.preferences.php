<?php
/**
 * @package	JomSocial
 * @subpackage Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
?>

<div class="cPreferences">
<form method="post" action="<?php echo CRoute::getURI();?>" name="saveProfile">
<div class="cProfile-PrefNav">
	<ul>
	<li><a href="#generalPref"><?php echo JText::_('COM_COMMUNITY_PROFILE_PREFERENCES_GENERAL'); ?></a></li>
	<li><a href="#privacyPref"><?php echo JText::_('COM_COMMUNITY_PROFILE_PREFERENCES_PRIVACY'); ?></a></li>
	<li><a href="#emailPref"><?php echo JText::_('COM_COMMUNITY_PROFILE_PREFERENCES_EMAIL'); ?></a></li>
	<li><a href="#blocklistPref"><?php echo JText::_('COM_COMMUNITY_PROFILE_PREFERENCES_BLOCKLIST'); ?></a></li>
	</ul>
	<div class="saveButton" ><input type="submit" class="button" value="<?php echo JText::_('COM_COMMUNITY_SAVE_BUTTON'); ?>" /></div>
</div>
<div class="cProfile-PrefContainer">

	<div id="generalPref">
		<div class="ctitle"><h2><?php echo JText::_('COM_COMMUNITY_EDIT_PREFERENCES'); ?></h2></div>
		<table class="formtable" cellspacing="1" cellpadding="0">
		<?php echo $beforeFormDisplay;?>
		<tr>
		<td class="key" style="width: 180px;">
			<label for="activityLimit">
			<?php echo JText::_('COM_COMMUNITY_PREFERENCES_ACTIVITY_LIMIT'); ?>
			</label>
		</td>
		<td class="value">
            <input type="text" id="activityLimit" class="jomNameTips inputbox" title="<?php echo JText::_('COM_COMMUNITY_PREFERENCES_ACTIVITY_LIMIT_DESC');?>" name="activityLimit" value="<?php echo $params->get('activityLimit', 20 );?>" size="5" maxlength="3" />
		</td>
		</tr>
		<tr>
		<td class="key" style="width: 180px;">
			<label for="profileLikes">
			<?php echo JText::_('COM_COMMUNITY_PROFILE_LIKE_ENABLE'); ?>
			</label>
		</td>
		<td class="value">
            <input type="checkbox" class="title jomNameTips" title="<?php echo JText::_('COM_COMMUNITY_PROFILE_LIKE_ENABLE_DESC');?>" value="1" id="profileLikes-yes" name="profileLikes" <?php if($params->get('profileLikes', 1) == 1)  { ?>checked="checked" <?php } ?>/>
        
	    <!--input type="radio" value="0" id="profileLikes-no" name="profileLikes" <?php if($params->get('profileLikes') == '0') { ?>checked="checked" <?php } ?>/>
	    <label for="profileLikes-no" class="lblradio"><?php echo JText::_('COM_COMMUNITY_NO'); ?></label -->
		</td>
		</tr>
		<tr>
		<td class="key" style="width: 180px;">
			<label class="label"><?php echo JText::_('COM_COMMUNITY_PRIVACY_PROFILE_FIELD');?></label>
		</td>
		<td class="privacyc"><?php echo CPrivacy::getHTML( 'privacyProfileView' , $params->get( 'privacyProfileView' ) , COMMUNITY_PRIVACY_BUTTON_LARGE , array( 'public' => true , 'members' => true , 'friends' => true , 'self' => false ) ); ?></td>
		<td></td>
		</tr>
		</table>

		<?php if( $jConfig->getValue('sef') ){ ?>
		<div class="ctitle"><h2><?php echo JText::_('COM_COMMUNITY_YOUR_PROFILE_URL'); ?></h2></div>
		<div class="cRow" style="padding: 5px 0 0;">
		<?php echo JText::sprintf('COM_COMMUNITY_YOUR_CURRENT_PROFILE_URL' , $prefixURL );?>
		</div>
		<?php }?>
	</div>

	<div id="privacyPref">
	<!-- friends privacy -->
		<div class="ctitle"><h2><?php echo JText::_('COM_COMMUNITY_EDIT_YOUR_PRIVACY'); ?></h2></div>
		<table class="formtable" cellspacing="1" cellpadding="0">
		<tr>
		<td class="key" style="width: 80px;padding-right: 20px;">
		<label class="label"><?php echo JText::_('COM_COMMUNITY_PRIVACY_FRIENDS_FIELD'); ?></label>
		</td>
		<td class="privacy"><?php echo CPrivacy::getHTML( 'privacyFriendsView' , $params->get( 'privacyFriendsView' ) , COMMUNITY_PRIVACY_BUTTON_LARGE ); ?></td>
		<td></td>
		</tr>
	<!-- photos privacy -->
		<?php if($config->get('enablephotos')): ?>
		<tr>
		<td class="key" style="width: 80px;padding-right:20px;">
		<label class="label"><?php echo JText::_('COM_COMMUNITY_PRIVACY_PHOTOS_FIELD'); ?></label>
		</td>
		<td class="privacy"><?php echo CPrivacy::getHTML( 'privacyPhotoView' , $params->get( 'privacyPhotoView' ) , COMMUNITY_PRIVACY_BUTTON_LARGE ); ?></td>
		<td class="value"><input type="checkbox" name="resetPrivacyPhotoView" /> <?php echo JText::_('COM_COMMUNITY_PHOTOS_PRIVACY_APPLY_TO_ALL'); ?></td>
		</tr>
		<?php endif;?>
		<!-- videos privacy -->
		<?php if($config->get('enablevideos')): ?>
		<tr>
		<td class="key" style="width: 80px;padding-right:20px;">
			<label class="label"><?php echo JText::_('COM_COMMUNITY_PRIVACY_VIDEOS_FIELD'); ?></label>
		</td>
		<td class="privacy"><?php echo CPrivacy::getHTML( 'privacyVideoView' , $params->get( 'privacyVideoView' ) , COMMUNITY_PRIVACY_BUTTON_LARGE ); ?></td>
		<td class="value"><input type="checkbox" name="resetPrivacyVideoView" /> <?php echo JText::_('COM_COMMUNITY_VIDEOS_PRIVACY_RESET_ALL'); ?></td>
		</tr>
		<?php endif; ?>
		<?php if( $config->get( 'enablegroups' ) ){ ?>
		<!-- groups privacy -->
		<tr>
		<td class="key" style="width: 80px;padding-right:20px;">
			<label class="label"><?php echo JText::_('COM_COMMUNITY_PRIVACY_GROUPS_FIELD'); ?></label>
		</td>
		<td class="privacy"><?php echo CPrivacy::getHTML( 'privacyGroupsView' , $params->get( 'privacyGroupsView' ) , COMMUNITY_PRIVACY_BUTTON_LARGE ); ?></td>
		<td></td>
		</tr>
		<?php } ?>
		</table>
	</div>

	<div id="emailPref">
		<div class="ctitle"><h2><?php echo JText::_('COM_COMMUNITY_EDIT_EMAIL_PRIVACY'); ?></h2></div>
				<table class="formtable emailPref" cellspacing="1" cellpadding="0">
		<?php
		if( $config->get('privacy_search_email') == 1 ) {
		?>
		<tr>
		<td class="key" style="width: 200px;">
			<input type="hidden" name="search_email" value="0" />
			<input type="checkbox" value="1" id="email-email-yes" name="search_email" <?php if($my->get('_search_email') == 1) { ?>checked="checked" <?php } ?>/>
		</td>
		<td class="value">
			<label for="search_email"><?php echo JText::_('COM_COMMUNITY_PRIVACY_EMAIL'); ?></label>
		</td>
		</tr>
		<?php } ?>
		<!-- Start New email preference -->
		<tr class="tableHeader">
		<td class="value">
			<label></label>    
		</td>
		<td class="key" style="width: 30px; text-align:center;">
			<label for="notification-header"><?php echo JText::_('COM_COMMUNITY_PRIVACY_EMAIL_LABEL');?></label>    
		</td>
		<td class="key" style="width: 30px; text-align:center;">
			<label for="notification-header"><?php echo JText::_('COM_COMMUNITY_PRIVACY_NOTIFICATION_LABEL');?></label>  
		</td>
		</tr>
		<?php
			$isadmin = COwnerHelper::isCommunityAdmin();
			foreach($notificationTypes->getTypes() as $group){
			if ($notificationTypes->isAdminOnlyGroup($group->description) && !$isadmin) {
			continue;
			}
		?>
		<tr class="section">
		<td class="key" style="text-align:left;"><h3 style="padding:0;margin:10px 0;"><?php echo JText::_($group->description); ?></h3>  
		</td>
		<td class="key" style="width: 30px; text-align:center;"> 
		<input type="checkbox" onclick="toggleChecked('email<?php echo JText::_($group->description); ?>',this.checked)" >
		</td>
		<td class="key" style="width: 30px; text-align:center;">
		<input type="checkbox" onclick="toggleChecked('global<?php echo JText::_($group->description); ?>',this.checked)" >
		</td>
		<?php foreach($group->child as $id => $type){
			if($type->adminOnly && !$isadmin) continue;
			$emailId  = $notificationTypes->convertEmailId($id);
			$emailset = $params->get($emailId,$config->get($emailId));
			$notifId  = $notificationTypes->convertNotifId($id);
			$notifset = $params->get($notifId,$config->get($notifId));
		?>
		<tr>
		<td class="value">
			<label for="<?php echo $id; ?>"><?php echo JText::_($type->description); ?></label>    
		</td>
		<td class="key" style="width: 30px; text-align:center;">
			<input type="hidden" name="<?php echo $emailId; ?>" value="0" />
			<input id="<?php echo $emailId; ?>" type="checkbox" name="<?php echo $emailId; ?>" value="1" <?php if( $emailset == 1) echo 'checked="checked"'; ?> class="email<?php echo JText::_($group->description); ?>" />
		</td>
		<td class="key" style="width: 30px; text-align:center;">
			<input type="hidden" name="<?php echo $notifId; ?>" value="0" />
			<input id="<?php echo $notifId; ?>" type="checkbox" name="<?php echo $notifId; ?>" value="1" <?php if( $notifset == 1) echo 'checked="checked"'; ?> class="global<?php echo JText::_($group->description); ?>" />
		</td>
		</tr>
		<?php		
			}
		}
		?>
		</tr>
		<!-- End New email preference -->
		<?php echo $afterFormDisplay;?>
		</table>
	</div>

	<div id="blocklistPref">
		<div id="community-banlists-news-items" class="app-box" style="width: 100%; float: left;margin-top: 0px;">
		<div class="ctitle"><h2><?php echo JText::_('COM_COMMUNITY_MY_BLOCKED_LIST');?></h2></div>
		<ul id="friends-list">
		<?php
			foreach( $blocklists as $row )
			{
				$user	= CFactory::getUser( $row->blocked_userid );
		?>
		<li id="friend-<?php echo $user->id;?>" class="friend-list">
			<span><img width="45" height="45" src="<?php echo $user->getThumbAvatar();?>" alt="" /></span>
			<span class="friend-name">
				<?php echo $user->getDisplayName(); ?>
				<a class="remove" href="javascript:void(0);" onclick="joms.users.unBlockUser('<?php echo $row->blocked_userid;  ?>','privacy');">
		 	    <?php echo JText::_('COM_COMMUNITY_BLOCK'); ?>
				</a>
			</span>
		</li>
		<?php
			}
		?>
		</ul>
		</div>
	</div>
</div>
</form>
</div>

<script type="text/javascript">
function toggleChecked(className,status) {
	joms.jQuery("."+className).each( function() {
	joms.jQuery(this).attr("checked",status);
	})
}

joms.jQuery( document ).ready( function(){
  	joms.privacy.init();
    
    var tabContainers = joms.jQuery('.cProfile-PrefContainer > div');
    
    joms.jQuery('.cProfile-PrefNav ul li a').click(function () {
        tabContainers.hide().filter(this.hash).fadeIn(500);
        joms.jQuery('.cProfile-PrefNav ul li a').removeClass('selected');
        joms.jQuery(this).addClass('selected');
        
        return false;
    }).filter(':first').click();

});
</script>
