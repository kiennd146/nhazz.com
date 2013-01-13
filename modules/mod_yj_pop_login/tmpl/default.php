<?php
/*======================================================================*\
|| #################################################################### ||
|| # Copyright �2006-2009 Youjoomla LLC. All Rights Reserved.           ||
|| # ----------------     JOOMLA TEMPLATES CLUB      ----------- #      ||
|| # @license http://www.gnu.org/copyleft/gpl.html GNU/GPL            # ||
|| #################################################################### ||
\*======================================================================*/
defined('_JEXEC') or die('Restricted access'); 
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_yj_pop_login/css/stylesheet.css');
?>
<script type="text/javascript">
window.addEvent('domready', function() {
		
		$("discuss_form_photo").setStyles({
			left: (window.getScrollLeft() + (window.getWidth() - 445)/2)+'px'

		}); 
                
});

(function($){
	$(document).ready(function(){	
		$("#dcs_photo_form_submit").click(function(e){
			e.preventDefault();
			<?php if($type == 'logout') : ?>
			//$('form#dcs_photo_form_create').submit();
			//return;
			$('form#dcs_photo_form_create').ajaxSubmit({
				beforeSubmit: function() {
					//$('#results').html('Submitting...');
					//alert("test");
				},
				success: function(data) {
					var _data = JSON.parse(data);
					if (_data.state == '1') {
						alert("<?php echo JText::_('VITABOOK_LIST_CREATE_SUCCESS') ?>");
						$('form#dcs_photo_form_create input[name="dcs_title"]').empty();
						$('form#dcs_photo_form_create input[name="dcs_message"]').empty();
						showThem('discuss_form_photo');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError);
					showThem('discuss_form_photo');
				}
			});
			
			<?php else: ?>
			showThem('login_pop');
			<?php endif ?>
			
		});
	});
})(jQuery);
</script>

<?php if($type == 'logout') : ?>
<div id="logins">
	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
                <?php
                require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
                $user = CFactory::getUser();
                //$avatar = $user->getAvatar();
                //$image = '<img class="userBoxAvatar" src="'. $avatar .'" alt="" border="0"/>';
                $username = $user->get('name');
		$shortName = mod_YJPOPHelper::substring($username, 15);
                $userId = $user->get('id');
                $view = JHtml::link( "#" , $shortName, array("class" => "user-link yj_login_info", "onclick" => "return false;", "id" => "yj_login_info", 'title' => $username) );
                echo $view;
                ?>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
        <div class="loginManagement" id="loginManagement">
            <ul>
                <li><?php echo JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $userId) , JText::_('VIEWMYPROFILE'), array("class" => "user-link") );?></li>
                <li><?php echo JHtml::link( "#" , JText::_('LOGOUT'), array("class" => "yj_login_logout", "onclick" => "return false;") );?></li>
            </ul>
        </div>
<?php 
JHTML::_('behavior.mootools'); 
$document->addScript(JURI::base() . 'modules/mod_yj_pop_login/src/yj_login_pop.js');
$javascript = "";
$javascript .= "window.addEvent('domready', function() {" . "\n";
$javascript .= "$('yj_login_info').addEvent('click', function(e){" . "\n";
$javascript .= "$('loginManagement').toggle();" . "\n";
$javascript .= '});' . "\n";
$javascript .= "$$('.yj_login_logout').addEvent('click', function(e){" . "\n";
$javascript .= "$('login-form').submit();" . "\n";
$javascript .= "});" . "\n";
$javascript .= "});" . "\n";
$document->addScriptDeclaration( $javascript );
?>
</div>
<?php else : ?>
<?php 
JHTML::_('behavior.mootools'); 
$document->addScript(JURI::base() . 'modules/mod_yj_pop_login/src/yj_login_pop.js');
?>
<script type="text/javascript">
window.addEvent('domready', function() {
                
		$("login_pop").setStyles({
			left: (window.getScrollLeft() + (window.getWidth() - 290)/2)+'px'
	
		}); 

		$("reg_pop").setStyles({
			left: (window.getScrollLeft() + (window.getWidth() - 445)/2)+'px'

		}); 
                
});

</script>
<!-- registration and login -->
<div class="poping_links"> 
	<a href="javascript:;" onclick="showThem('login_pop');return false;" id="openLogin"><?php echo JText::_('LOGIN') ?></a>
	<?php $usersConfig = &JComponentHelper::getParams( 'com_users' ); if ($usersConfig->get('allowUserRegistration')) : ?>
	<a href="javascript:;" onclick="this.blur();showThem('reg_pop');return false;" id="openReg"><?php echo JText::_('REGISTER') ?></a>
	<?php endif; ?>
	
</div>
<!-- login -->
<div id="login_pop" style="display:none;">
	<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
	<?php JHTML::_('script', 'openid.js'); ?>
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
		<label for="yjpop_username"><?php echo JText::_('USERNAME') ?></label>
		
		<input id="yjpop_username" type="text" name="username" class="inputbox" alt="username" size="18" />
		
		<label for="yjpop_passwd"><?php echo JText::_('PASSWORD') ?></label>
		
		<input id="yjpop_passwd" type="password" name="password" class="inputbox" size="18" alt="password" />
		
		<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
		
		<label for="yjpop_remember"><?php echo JText::_('REMEMBER') ?></label>
		<input id="yjpop_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
		
		<?php endif; ?>
		<input type="submit" name="Submit" class="button yjpop_login" value="<?php echo JText::_('LOGIN') ?>" />
		<?php echo $params->get('posttext'); ?>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
	<a href="javascript:;" onclick="this.blur();showThem('login_pop');return true;" id="closeLogin"><?php echo JText::_('CLOSE') ?></a> </div>
<!-- registration  -->
<div id="reg_pop"  style="display:none;">
	<script type="text/javascript" src="<?php echo JURI::base() ?>media/system/js/validate.js"></script>
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" id="josForm" name="josForm" class="form-validate">
		<div class="popyjreg">
			<div class="popyjreg_ins">
				<label id="namemsg" for="name"> *&nbsp;<?php echo JText::_( 'NAME' ); ?>: </label>
				<input type="text" name="jform[name]" id="name" size="40" value="" class="inputbox required" maxlength="50" />
			</div>
			<div class="popyjreg_ins">
				<label id="usernamemsg" for="username"> *&nbsp;<?php echo JText::_( 'USERNAME' ); ?>: </label>
				<input type="text" id="username" name="jform[username]" size="40" value="" class="inputbox required validate-username" maxlength="25" />
			</div>
			<div class="popyjreg_ins">
				<label id="emailmsg" for="email"> *&nbsp;<?php echo JText::_( 'EMAIL' ); ?>: </label>
				<input type="text" id="email" name="jform[email1]" size="40" value="" class="inputbox required validate-email" maxlength="100" />
			</div>
			<div class="popyjreg_ins">
				<label id="emailmsg2" for="email2"> *&nbsp;<?php echo JText::_( 'VERIFY_EMAIL' ); ?>: </label>
				<input type="text" id="email2" name="jform[email2]" size="40" value="" class="inputbox required validate-email" maxlength="100" />
			</div>
			<div class="popyjreg_ins">
				<label id="pwmsg" for="password"> *&nbsp;<?php echo JText::_( 'PASSWORD' ); ?>: </label>
				<input class="inputbox required validate-password" type="password" id="password" name="jform[password1]" size="40" value="" />
			</div>
			<div class="popyjreg_ins">
				<label id="pw2msg" for="password2"> *&nbsp;<?php echo JText::_( 'VERIFY_PASSWORD' ); ?>: </label>
				<input class="inputbox required validate-passverify" type="password" id="password2" name="jform[password2]" size="40" value="" />
			</div>
		</div>
		<p class="information_td"><?php echo JText::_( 'REGISTER_REQUIRED' ); ?></p>
		<button class="button validate" type="submit"><?php echo JText::_('REGISTER'); ?></button>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="registration.register" />
		<?php echo JHtml::_('form.token');?>
	</form>
	<a href="javascript:;" onclick="this.blur();showThem('reg_pop');return true;" id="closeReg"><?php echo JText::_('CLOSE') ?></a> </div>
<!-- end registration and login -->
<?php endif; ?>

<!--discuss-form-->
<div id="discuss_form_photo" style="display:none;" logged="<?php echo $type != 'logout'?'0':'1' ?>" >
	<div class="dcs_form">
		<form id="dcs_photo_form_create" action="<?php echo JRoute::_('index.php?option=com_vitabook'); ?>" method="post">
		
		<input class="borderGrey" placeholder="<?php echo JText::_('VITABOOK_LIST_HINT_TITLE') ?>" type="text" name="dcs_title" />
		<textarea class="borderGrey" placeholder="<?php echo JText::_('VITABOOK_LIST_HINT_MESSAGE') ?>" name="dcs_message"></textarea>
		<input id="dcs_photo_id" name="dcs_photo_id" value="" type="hidden" />
		<p>
			<button type="button" id="dcs_form_cancel" onclick="this.blur();showThem('discuss_form_photo');return true;"  ><?php echo JText::_('CLOSE') ?></button>
			<button type="button" id="dcs_photo_form_submit"><?php echo JText::_('VITABOOK_LIST_BUTTON_POST') ?></button>
		</p>
		<?php 
		// hidden fields
		echo JHtml::_('form.token');
		//echo $this->form->getInput('id');
		//echo $this->form->getInput('parent_id'); ?>
		<input type="hidden" name="task" value="message.save" />
		<input type="hidden" name="format" value="raw" />
		</form>
	</div>
</div>
<!--end-discuss-form-->