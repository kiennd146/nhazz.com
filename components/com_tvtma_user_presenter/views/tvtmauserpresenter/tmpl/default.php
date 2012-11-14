<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet( $baseurl.'components/com_tvtma_user_presenter/css/styles.css' );
$currentTitle = $document->getTitle();
$document->setTitle($currentTitle . " - " .JRequest::getVar('value'));
?>
<style>
    #rightMain {
	background: #fafbf6;
    }
</style>
<h1 class='totalUser'><?php
if($this->total) {
    echo number_format($this->total, 0, '', ',') . ' chuyên gia';
} else {
    echo "Không có chuyên gia nào";
}
?></h1>
<div class="userPresenter">
    <?php
    foreach ($this->items as $userItem):
	$user = CFactory::getUser($userItem->id);
	$avatarImageLink = $user->getAvatar();
	$avatarImage = '<img class="" src="'. $avatarImageLink .'" alt="" border="0"/>';
	$avatarLink = JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->get('id')) , $avatarImage, array("class" => "") );
	$userLink = JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->get('id')) , $user->get('name'), array("class" => "") );
	$moreLink = JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->get('id')) , "Xem thêm", array("class" => "more-link") );
	
	//$userProject = 
    ?>
    <div class="userBoxPresenter">
	<div class="imageBoxPresenter">
	    <?php echo $userItem->image;?>
	</div>
	<div class="userNamePresenter">
	    <div class="avatarPresenter"><?php echo $avatarLink;?></div>
	    <div class=""><?php echo $userLink;?></div>
	</div>
	<div class="detailPresenter">
	    
	    <?php 
	    echo $userItem->value;
	    echo $moreLink;
	    ?>
	</div>
    </div>
    <?php 
    unset($user);
    endforeach;
    ?>
</div>
<?php if($this->pagination->getPagesLinks()) :?>
<div class="page-navigation">
	<?php //echo $this->pagination->getListFooter(); ?>
	<?php //echo $this->pagination->getPagesCounter(); ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php endif;?>
<div class="metroPresenter">
    <h3>Chuyên gia trên các vùng miền</h3>
    <ul>
	<?php 
	$value = JRequest::getVar('value');
	$metros =  $this->metros;
	$metroOptions = $metros->options;
	$metroOptions = explode("\n", $metroOptions);
	$metroId = $metros->id;
	//echo $metroId;
	foreach ($metroOptions as $metroOption) {
	    $class = ($metroOption == $value) ? "active" : "";
	    echo "<li class='{$class}'>";
	    echo JHTML::link(JRoute::_('index.php?option=com_tvtma_user_presenter&view=tvtmauserpresenter&fieldSeach=' . $metroId . '&value=' . urlencode($metroOption)), $metroOption);
	    echo "</li>";
	}
	?>
	
    </ul>
</div>


