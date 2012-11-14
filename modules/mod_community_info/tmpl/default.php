<?php
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet($baseurl . 'modules/mod_community_info/includes/mod_community_info.css');
$searchUserId = JRequest::getVar('search_user_id');
$activeUser = JRequest::getVar('userid');
$userLogin = & JFactory::getUser();
if (isset($searchUserId) && $searchUserId != "") {
    $user = & CFactory::getUser($searchUserId);
} elseif (isset($searchUserId) && $searchUserId == "") {
    return;
} elseif (isset($activeUser) && $activeUser != "") {
    $user = & CFactory::getUser($activeUser);
} elseif ($userLogin->get('guest')) {
    return;
} else {
    //$user = & CFactory::getActiveProfile();
    $user = CFactory::getRequestUser();
}
//var_dump($user);die();
$projectLists = modCommunityInfoHelper::getUserProject($user->id);
$SID = $params->get('sectionId');
$FieldNid = $params->get('fieldID');
$searchFor = JRequest::getVar('searchFor');
$maxLengthDisplay = $params->get('profileMaxChar');
$fieldDisplay = $params->get('profieFieldId');
$data = new stdClass();
$model = CFactory::getModel('profile');
$data->profile = $model->getEditableProfile($user->id, $user->getProfileType());
$profile = getProfileHTML($data->profile);
if(count($profile['fields']['Basic Information']) == 0) $noData = true;
CFactory::load('libraries', 'profile');
CFactory::load('libraries', 'privacy');
$my = JFactory::getUser();
$isMine = ($my->get('id') == $user->get('id')) ? true : false;
function getProfileHTML(&$profile) {
    //$tmpl = new CTemplate();
    //$profileModel = CFactory::getModel('profile');
    //$my = CFactory::getUser();
    $config = CFactory::getConfig();
    //$userid = JRequest::getVar('userid', $my->id);
    //$user = CFactory::getUser($userid);
    $profileField = & $profile['fields'];
    CFactory::load('helpers', 'linkgenerator');
    CFactory::load('helpers', 'validate');
    CFactory::load('helpers', 'owner');
    $isAdmin = COwnerHelper::isCommunityAdmin();
    // Allow search only on profile with type text and not empty
    foreach ($profileField as $key => $val) {

	foreach ($profileField[$key] as $pKey => $pVal) {
	    $field = & $profileField[$key][$pKey];
	    //check for admin only fields
	    if (!$isAdmin && $field['visible'] == 2) {
		unset($profileField[$key][$pKey]);
	    } else {
		// Remove this info if we don't want empty field displayed
		if (!$config->get('showemptyfield') && ( empty($field['value']) && $field['value'] != "0")) {
		    unset($profileField[$key][$pKey]);
		} else {
		    if ((!empty($field['value']) || $field['value'] == "0" ) && $field['searchable']) {
			switch ($field['type']) {
			    case 'birthdate':
				$params = new CParameter($field['params']);
				$format = $params->get('display');

				if ($format == 'age') {
				    $field['name'] = JText::_('COM_COMMUNITY_AGE');
				}

				break;
			    case 'text':
				if (CValidateHelper::email($field['value'])) {
				    $profileField[$key][$pKey]['value'] = CLinkGeneratorHelper::getEmailURL($field['value']);
				} else if (CValidateHelper::url($field['value'])) {
				    $profileField[$key][$pKey]['value'] = CLinkGeneratorHelper::getHyperLink($field['value']);
				} else if (!CValidateHelper::phone($field['value']) && !empty($field['fieldcode'])) {
				    $profileField[$key][$pKey]['searchLink'] = CRoute::_('index.php?option=com_community&view=search&task=field&' . $field['fieldcode'] . '=' . urlencode($field['value']));
				}
				break;
			    case 'select':
			    case 'singleselect':
			    case 'radio':
			    case 'checkbox':
				$profileField[$key][$pKey]['searchLink'] = array();
				$checkboxArray = explode(',', $field['value']);
				foreach ($checkboxArray as $item) {
				    if (!empty($item))
					$profileField[$key][$pKey]['searchLink'][$item] = CRoute::_('index.php?option=com_community&view=search&task=field&' . $field['fieldcode'] . '=' . urlencode($item) . '&type=' . $field['type']);
				}
				break;
			    case 'country':
				$profileField[$key][$pKey]['searchLink'] = CRoute::_('index.php?option=com_community&view=search&task=field&' . $field['fieldcode'] . '=' . urlencode($field['value']));
				break;
			    default:
				break;
			}
		    }
		}
	    }
	}
    }
    return $profile;
}
?>
<div class="community-info">
    <div class="profile-avatar" onMouseOver="" onmouseout="">
	<a class="" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->get('id')); ?>" title="<?php echo $user->get('name'); ?>">
        <img src="<?php echo $user->getAvatar(); ?>" alt="" />
	</a>
    </div>
<?php //echo $profileLike;    ?>
    <div class="profile-menu-box">
        <h1>Hồ sơ của <?php echo $user->get('name'); ?></h1>
        <div class="community-info-profile">
	    <?php
	    //echo $html;
	    ?>
	    <div class="cModule">
		<h3>Thông tin cơ bản</h3>
		<?php if ($isMine): ?>
    		<a class="edit-this" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit'); ?>" title="<?php echo JText::_('COM_COMMUNITY_PROFILE_EDIT'); ?>">[ <?php echo JText::_('COM_COMMUNITY_PROFILE_EDIT'); ?> ]</a>
		<?php endif; ?>
		<?php
		foreach ($profile['fields'] as $groupName => $items):
		    $hasData = false;
		    ob_start();
		    ?>
    		<div class="cProfile-About">
			<?php if ($groupName != 'ungrouped'): ?>
			    <h4><?php echo JText::_($groupName); ?></h4>
			<?php endif; ?>
    		    <dl class="profile-right-info">
			    <?php foreach ($items as $item): ?>
			    
				<?php
				if(!in_array($item['id'], $fieldDisplay)) {
				    continue;
				}
				if (CPrivacy::isAccessAllowed($my->id, $profile['id'], 'custom', $item['access'])) {
				    // There is some displayable data here
				    $hasData = true;
				    ?>
	    			<dt><?php echo JText::_($item['name']); ?></dt>
				    <?php if (!empty($item['searchLink']) && is_array($item['searchLink'])): ?>
					<dd>
					    <?php foreach ($item['searchLink'] as $linkKey => $linkValue): ?>
						<?php $item['value'] = $linkKey; ?>
		    			    <a href="<?php echo $linkValue; ?>"><?php echo CProfileLibrary::getFieldData($item) ?></a><br />
					    <?php endforeach; ?>

					</dd>
				    <?php else: ?>
					<dd class='box-max'>
					    
					    <?php if (!empty($item['searchLink'])) : ?>
		    			    <a href="<?php echo $item['searchLink']; ?>"> 
					    <?php endif; ?>
					    <?php 
					    $string = CProfileLibrary::getFieldData($item);
					    $countString = mb_strlen($string);
					    if($countString > $maxLengthDisplay){
						echo "<div class='minimum{$item['id']}'>";
						echo modCommunityInfoHelper::substring($string, $maxLengthDisplay); 
						echo "</div>";
						echo "<div class='hidden{$item['id']}' style='display:none'>";
						echo $string;
						echo "</div>";
						echo "<div class='box-minimum'><a href='#' onclick='return false;' class='zoom' target='minimum{$item['id']}' show='hidden{$item['id']}'>Xem thêm</a><div>";
					    } else {
						echo $string; 
					    }
					    
					    ?>
					    <?php if (!empty($item['searchLink'])) : ?>
		    			    </a>
					    <?php endif; ?>
					</dd>
				    <?php endif; ?>
				    <?php
				}
				?>
			    <?php endforeach; ?>
    		    </dl>
    		</div>
		    <?php
		    $html = ob_get_contents();
		    ob_end_clean();
		    // We would only display the profile data in the group if there is actually some
		    // data to be displayed
		    if ($hasData):
			echo $html;
			$noData = false;
		    endif;
		endforeach;
		if ($noData)
		    echo ($isMine) ? JText::_('COM_COMMUNITY_PROFILES_SHARE_ABOUT_YOURSELF') : JText::_('COM_COMMUNITY_PROFILES_NO_INFORMATION_SHARE');
		?>
	    </div>
	    <h4>Dự án của tôi</h4>
	    <ul class="projectList">
		<?php
		$mainframe = & JFactory::getApplication();
		foreach ($projectLists as $value):
		    $link = JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for=' . $value->id . '&' . $FieldNid . '&sid=' . $SID . '&spsearchphrase=exact&search_user_id=' . $value->owner), ucfirst($value->name));
		    $active = ($searchFor == $value->id) ? " class='active' " : "";
		    echo "<li $active>" . $link . "</li>";
		endforeach;
		?>

	    </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('a.zoom').live("click",function() {
	var current = jQuery(this);
	var target = current.attr('target');
	var show = current.attr('show');
	//jQuery("div.minimum").css("height", "auto");
	jQuery('div.' + target).css('display','none');
	jQuery('div.' + show).css('display','block');
	jQuery('div.' + show).slideDown('slow', function() {
	    // Animation complete.
	    current.removeClass('zoom');
	    current.addClass('hide');
	    current.html('Thu nhỏ');
	});
     });
     
     jQuery('a.hide').live("click",function() {
	var current = jQuery(this);
	var target = current.attr('target');
	var show = current.attr('show');
	//jQuery("div.minimum").css("height", "auto");
	jQuery('div.' + show).slideUp('fast', function() {
	    // Animation complete.
	    jQuery('div.' + show).css('display','none');
	    jQuery('div.' + target).css('display','block');
	    current.removeClass('hide');
	    current.addClass('zoom');
	    current.html('Xem thêm');
	});
     });
});
</script>