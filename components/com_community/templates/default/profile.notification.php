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

<?php echo $beforeFormDisplay;?>

	<div class="cNotifications" class="app-box">
		<div class="ctitle"><h2><?php echo JText::_('COM_COMMUNITY_PROFILE_NOTIFICATION_LIST');?></h2></div>
		<div class="ctitle"><?php echo JText::sprintf('COM_COMMUNITY_PROFILE_NOTIFICATION_LIST_LIMIT_MSG',$config->get('maxnotification'));?></div>
		
		<ul class="cProfile-DataStream">
			<?php
				foreach( $notifications as $row ) {
				$user	= CFactory::getUser( $row->actor );
			?>
			<li><a href="<?php echo CContentHelper::injectTags('{url}',$row->params,true); ?>">
				<img src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>" />
				</a>
				<p><?php echo CContentHelper::injectTags($row->content,$row->params,true); ?> 
				<span class="time"><?php echo CTimeHelper::timeLapse(CTimeHelper::getDate($row->created)); ?></span></p>
			</li>
			<?php } ?>
		</ul>
	</div>

<?php echo $beforeFormDisplay;?>
<?php echo (isset($pagination)) ? '<div class="pagination-container">'.$pagination->getPagesLinks().'</div>' : ''; ?>

<script>
joms.jQuery(".cProfile-DataStream p a").each(function(key,val){
	joms.jQuery(val).attr("target","_blank");
	joms.jQuery(val).click(function(e){
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();	
	});
});
joms.jQuery(".cProfile-DataStream li").each(function(key,val){
	joms.jQuery(val).click(function(e){
		var link = joms.jQuery(this).find("a").attr("href");
		if (link.length > 0){
			window.open(link,null);
		}
	});
});
</script>