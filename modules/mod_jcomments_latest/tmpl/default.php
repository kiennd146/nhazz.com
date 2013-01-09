<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ));
SPLoader::loadView( 'section' );
?>
<div id="mod_comment">
<div class="mod_comment_show">
<?php if (!empty($list)) : ?>
<ul class="jcomments-latest<?php echo $params->get('moduleclass_sfx'); ?>">
<?php if ($grouped) : 
    $m=0;
    foreach ($list as $group_name => $group) :
            $m++;
    endforeach;
    $t=0;
    if ($m%2 == 1){
            $d=intval($m/2)+1;
                  }else{$d=$m/2;}
 ?>
<div class="leftcom" style="width:49%;display: block;float:left">
	<?php foreach ($list as $group_name => $group) : ?>
	<li>
		<h<?php echo $item_heading; ?>>
			<?php if ($params->get('link_object_title') && $group[0]->object_link != '') : ?>
				<a href="<?php echo $group[0]->object_link;?>"><?php echo $group_name;?></a>
			<?php else : ?>
				<?php echo $group_name; ?>
			<?php endif; ?>
		</h<?php echo $item_heading; ?>>
		<ul>
			<?php $i = 0; $n = count($group);?>
			<?php foreach ($group as $item) : ?>
			<li>
				<?php if ($params->get('show_avatar')) :?>
					<?php echo $item->avatar; ?>
				<?php endif; ?>

				<?php if ($params->get('show_comment_author')) :?>
				<span class="author"><?php echo $item->displayAuthorName; ?></span>
				<?php endif; ?>
				<?php if ($params->get('show_comment_date')) :?>
				<span class="date"><?php echo $item->displayDate; ?></span>
				<?php endif; ?>

				<div class="comment rounded<?php echo $params->get('show_avatar') ? ' avatar-indent' : '';?>">
					<?php if ($params->get('show_comment_title') && $item->displayCommentTitle) :?>
					<a class="title" href="<?php echo $item->displayCommentLink; ?>" title="<?php echo htmlspecialchars($item->displayCommentTitle); ?>">
						<?php echo $item->displayCommentTitle; ?>
					</a>
					<?php endif; ?>
					<div>
						<?php echo $item->displayCommentText; ?>
						<?php if ($params->get('readmore')) : ?>
						<p class="jcomments-latest-readmore">
							<a href="<?php echo $item->displayCommentLink; ?>"><?php echo $item->readmoreText; ?></a>
						</p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ($n > 1 && ($i < $n - 1)) :?>
				<span class="comment-separator">&#160;</span>
				<?php endif; ?>
			</li>
			<?php $i++;?>
		<?php endforeach; ?>
		</ul>
	</li>
        <?php $t++;
                        if($t==$d){ ?>
                        </div>
                <div class="rightcom" style="width: 49%;display: block;float: right;">
	<?php } endforeach; ?>
                </div>
<?php else : ?>
    
	<?php $i = 0; $n = count($list);
            $t=0;
            if ($n%2 == 1){
                $d=intval($n/2)+1;
            }else{$d=$n/2;}
        ?>
        <div class="leftcom" style="width:100%;display: block;float:left">
	<?php foreach ($list as $item) :
            if(($item->object_group)=='com_sobipro'){
                $com_type=$item->object_group;
                $value = $item->object_id;
                $entry = SPFactory::Entry($value);
                $imgpth = modJCommentsLatestHelper::takeImage($entry,'original',$com_type,$value);
            } else if(($item->object_group)=='com_content'){
                $value= $item->object_id;
                $com_type=$item->object_group;
                $imgpth = modJCommentsLatestHelper::takeImage($entry,'original',$com_type,$value);
            }
        ?>
	<li>
            <? if($imgpth && ($params->get('show_image'))): ?>
            <div class="imgcom" style="overflow:hidden;width:49%;height:76px;float:left;margin-top: 0; margin-bottom: 5px;margin-right: 5px;">
                <img style="width:100%;height:100%" src="<?echo $imgpth?>" />
            </div>
            <? endif;?>
           
		<?php if ($params->get('show_object_title')) :?>
		<h<?php echo $item_heading; ?> class="jcomment-name">
			<?php if ($params->get('link_object_title',1) && $item->object_link != '') : ?>
				<a href="<?php echo $item->object_link;?>"><?php echo ucfirst($item->displayObjectTitle); ?></a>
			<?php else : ?>
				<?php echo ucfirst($item->displayObjectTitle); ?>
			<?php endif; ?>
		</h<?php echo $item_heading; ?>>
		<?php endif; ?>

		

		<div class="rounded  <?php echo $params->get('show_avatar') ? 'avatar-indent' : '';?>">
		<?php if ($params->get('show_comment_title') && $item->displayCommentTitle) :?>
			<a class="title" href="<?php echo $item->displayCommentLink; ?>" title="<?php echo htmlspecialchars($item->displayCommentTitle); ?>">
				<?php echo $item->displayCommentTitle; ?>
			</a>
			<?php endif; ?>
			<div>
				<?php echo ucfirst($item->displayCommentText); ?>
				<?php if ($params->get('readmore')) : ?>
				<p class="jcomments-latest-readmore">
					<a href="<?php echo $item->displayCommentLink; ?>"><?php echo $item->readmoreText; ?></a>
				</p>
				<?php endif; ?>
			</div>
		</div>
                
		<?php if ($params->get('show_comment_author')) :?>
                <?php $ownerLink = JHtml::link( JRoute::_('index.php?option=com_community&view=profile&userid=' . $item->userid) , $item->displayAuthorName, array("class" => "author-link") ); ?>
		<span class="author">
                <?php if ($params->get('show_avatar')) :?>
			<?php echo $item->avatar; ?>
		<?php endif; ?>
                : <?php echo $ownerLink; ?></span>
		<?php endif; ?>
		<?php if ($params->get('show_comment_date')) :?>
		<span class="date"><?php echo $item->displayDate; ?></span>
		<?php endif; ?>
                
            
		<?php if ($n > 1 && ($i < $n - 1)) :?>
		<span class="comment-separator">&#160;</span>
		<?php endif; ?>
	</li>
	<?php $i++;$t++;
        if($t==$d && $d>1){
        ?>
        </div>
        <div class="rightcom" style="width: 49%;display: block;float: right;">
	<?php } endforeach; ?>
        </div>
<?php endif; ?>
</ul>
<?php endif; ?>
</div>
</div>
