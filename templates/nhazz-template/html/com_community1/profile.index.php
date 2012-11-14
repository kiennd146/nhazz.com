<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 **/
defined('_JEXEC') OR DIE();
?>

<script type="text/javascript" src="<?php echo JURI::root();?>components/com_community/assets/ajaxfileupload.pack.js"></script>
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_community/assets/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::root();?>components/com_community/assets/imgareaselect/css/imgareaselect-default.css" />

<script type="text/javascript"> joms.filters.bind();</script>

<!-- begin: #cProfileWrapper -->
<div id="cProfileWrapper">
	<?php echo $adminControlHTML; ?>
	<!-- begin: .cLayout -->
	<div class="cLayout clrfix">
		<?php //$this->renderModules( 'js_profile_top' ); ?>
		<?php //if($isMine) $this->renderModules( 'js_profile_mine_top' ); ?>		
        <!-- begin: .cMain -->
           
            <div class="profile-left leftWidth">
                <?php //$this->renderModules( 'js_side_top' ); ?>
	    	<?php //$this->renderModules( 'js_profile_side_top' ); ?>
                <?php echo $sidebarTop; ?>
                <?php //if($isMine) $this->renderModules( 'js_profile_mine_side_top' ); ?>
                <?php //if($isMine) $this->renderModules( 'js_profile_mine_side_bottom' ); ?>
                <?php echo $sidebarBottom; ?>
                <?php //$this->renderModules( 'js_profile_side_bottom' ); ?>
                <?php //$this->renderModules( 'js_side_bottom' ); ?>
                <div class="profile-avatar" onMouseOver="joms.jQuery('.rollover').toggle()" onmouseout="joms.jQuery('.rollover').toggle()">
                    <img src="<?php echo $user->getAvatar(); ?>" alt="<?php echo $this->escape( $user->getDisplayName() ); ?>" />
                    <?php if( $isMine ): ?>
                    <div class="rollover"><a href="javascript:void(0)" onclick="joms.photos.uploadAvatar('profile','<?php echo $user->id?>')"><?php echo JText::_('COM_COMMUNITY_CHANGE_AVATAR')?></a></div>
                    <?php endif; ?>
                </div>
                <div class="profile-menu-box">
                    <h1>Hồ sơ của <?php echo ($isMine) ? "tôi" : $user->getDisplayName(); ?></h1>
                </div>
            </div>
            
	    <div class="cMain  profile-right noRight">
			
                        <h1 class="contentheading">
				<?php echo $user->getDisplayName(); ?>
			</h1>
                        <div class='communityActionBox'>
                            <ul class="ActionBoxUl">
                                <?php 
                                echo @$header; 
                                unset($header);
                                ?>
                                <?php if( $isMine ): ?>
                                <li class='editProfile'><a href="<?php echo CRoute::_( 'index.php?option=com_community&view=profile&task=edit' );?>">Chỉnh sửa hồ sơ</a></li>
                                <li class='addFriend'><a href="<?php echo CRoute::_( 'index.php?option=com_community&view=friends&task=invite' );?>">Mời bạn</a></li>
                                <?php else: ?>
                                
                                <?php endif; ?>
                            </ul>
                        </div>
			<?php $this->renderModules( 'js_profile_feed_top' ); ?>
			<div class="activity-stream-front">
				<div class="joms-latest-activities-container">
					<a id="activity-update-click" href="javascript:void(0);">1 new update </a>
				</div>
				<?php if($config->get('enable_refresh') == 1 && $isMine && empty($actId) ) : ?>
				<script type="text/javascript">
					joms.jQuery(document).ready(function(){
						
						joms.jQuery('#activity-update-click').click(function(){
							joms.jQuery('.joms-latest-activities-container').hide();
							joms.jQuery('.newly-added').show();
							joms.jQuery('.newly-added').removeClass('newly-added');
						});
						joms.activities.nextActivitiesCheck(<?php echo $config->get('stream_refresh_interval');?> );
					});
					
					function reloadActivities(){
						if(joms.jQuery('.cFeed-item').size() > 0){
						   joms.activities.getLatestContent(joms.jQuery('.cFeed-item').attr('id').substring(21),true); 
						}
					}
				</script>
				<?php endif; ?>
				<div class="activity-stream-profile">
					<div id="activity-stream-container">
				  	<?php echo $newsfeed; ?>
				  	</div>
				</div>
				
				<?php //$this->renderModules( 'js_profile_feed_bottom' ); ?>
				<div id="apps-sortable" class="connectedSortable" >
				<?php echo $content; ?>
				</div>
			</div>
		</div>
	    <!-- end: .cMain -->

		<?php //if($isMine) $this->renderModules( 'js_profile_mine_bottom' ); ?>
		<?php //$this->renderModules( 'js_profile_bottom' ); ?>
		
	</div>
	<!-- end: .cLayout -->
</div>
<!-- begin: #cProfileWrapper -->