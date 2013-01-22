<?php 
require_once JPATH_ROOT . '/modules/mod_jcomments_latest/helper.php';
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
$baseurl = JURI::base();
SPLoader::loadView( 'section' );
?> 
	<ul class="jcomments-latest<?php echo $this->moduleclass_sfx; ?>">
	<?php $i = 0; $n = count($this->list);
            $t=0;
            if ($n%2 == 1){
                $d=intval($n/2)+1;
            }else{$d=$n/2;}
        ?>
        <div class="leftcom" style="width:49%;display: block;float:left">
	<?php 
    $imgpth = '';
    foreach ($this->list as $item) :
            if(($item->object_group)=='com_sobipro'){
                $com_type=$item->object_group;
                $value = $item->object_id;
                $entry = SPFactory::Entry($value);
                $imgpth = modJCommentsLatestHelper::takeImage($entry,'original',$com_type,$value);
            } else if(($item->object_group)=='com_content'){
                $value= $item->object_id;
                $com_type=$item->object_group;
                $imgpth = modJCommentsLatestHelper::takeImage(NULL,'original',$com_type,$value);
            } else if(($item->object_group)=='com_vitabook'){
                $value= $item->object_id;
                $com_type=$item->object_group;
                $imgpth = modJCommentsLatestHelper::takeImage(NULL,'original',$com_type,$value);
            }
        ?>
	<li>
            <? if($imgpth && ($this->show_image)): ?>
            <div class="imgcom" style="overflow:hidden;width:82px;height:76px;float:left">
                <a href="<?echo $item->displayCommentLink;?>">
                <img style="width:100%;height:100%" src="<?echo $imgpth?>" />
                </a>
            </div>
            <? endif;?>
            <div class="contcom" style="overflow:hidden;width:77%;float:right;">
		<?php if ($this->show_object_title) :?>
		<h<?php echo $this->item_heading; ?> class="nhazz-title">
			<?php if ($this->link_object_title && $item->object_link != '') : ?>
				<a href="<?php echo $item->object_link;?>"><?php echo ucfirst($item->displayObjectTitle); ?></a>
			<?php else : ?>
				<?php echo ucfirst($item->displayObjectTitle); ?>
			<?php endif; ?>
		</h<?php echo $this->item_heading; ?>>
		<?php endif; ?>

		

		<div class="comment rounded <?php echo $this->show_avatar ? 'avatar-indent' : '';?>">
		<?php if ($this->show_comment_title && $item->displayCommentTitle) :?>
			<a class="title" href="<?php echo $item->displayCommentLink; ?>" title="<?php echo htmlspecialchars($item->displayCommentTitle); ?>">
				<?php echo ucfirst($item->displayCommentTitle); ?>
			</a>
			<?php endif; ?>
			<div>
				<?php echo ucfirst($item->displayCommentText); ?>
				<?php if ($this->readmore) : ?>
				<p class="jcomments-latest-readmore">
					<a class="more-link" href="<?php echo $item->displayCommentLink; ?>"><?php echo JText::_($item->readmoreText); ?></a>
				</p>
				<?php endif; ?>
			</div>
		</div>
                
                <?php if ($this->show_avatar) :?>
			<?php echo $item->avatar; ?>
		<?php endif; ?>

		<?php if ($this->show_comment_author) :?>
                <?php $ownerLink = JHtml::link( JRoute::_('index.php?option=com_community&view=profile&userid=' . $item->userid) , $item->displayAuthorName, array("class" => "author-link") ); ?>
		<span class="author"><? echo $item->creatby;?> : <?php echo $ownerLink; ?></span>
		<?php endif; ?>
		<?php if ($this->show_comment_date) :?>
		<span class="date"><?php echo $item->displayDate; ?></span>
		<?php endif; ?>
                
            </div>
		<?php if ($n > 1 && ($i < $n - 1)) :?>
		<span class="comment-separator">&#160;</span>
		<?php endif; ?>
	</li>
	<?php $i++;$t++;
        if($t==$d){
        ?>
        </div>
        <div class="rightcom" style="width: 49%;display: block;float: right;">
	<?php } endforeach; ?>
        </div>
</ul>
<?
$string=$this->sectionidarray;
?>

