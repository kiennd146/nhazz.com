<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
SPLoader::loadView( 'section' );
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');		
?>
<select id="surf" class="ajaxSelect" name="surf" style="float:right;margin-top: 10px;">
                <option value="0">Tìm theo chủ đề</option>
                <option value="1">
      Tin tức
                </option>
                <option value="2">
      Hình ảnh
                </option>
                <option value="3">
      Sản phẩm
                </option>
    
            </select>
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
						<?php echo ucfirst($item->displayCommentTitle); ?>
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
        <div class="leftcom" style="width:49%;display: block;float:left">
	<?php foreach ($list as $item) :
            if(($item->object_group)=='com_sobipro'){
                $com_type=$item->object_group;
                $value = $item->object_id;
                $entry = SPFactory::Entry($value);
                $imgpth = modJCommentsLatestHelper::takeImage($entry,'original',$com_type,$value);
            } else if(($item->object_group)=='com_content'){
                $value= $item->object_id;
                $com_type=$item->object_group;
                $imgpth = modJCommentsLatestHelper::takeImage(NULL,'original',$com_type,$value);
            }
        ?>
	<li>
            <? if(isset($imgpth) && $imgpth && ($params->get('show_image'))): ?>
            <div class="imgcom" style="overflow:hidden;width:22%;height:76px;float:left">
                <a href="<?echo $item->displayCommentLink;?>">
                <img style="width:100%;height:100%" src="<?echo $imgpth?>" />
                </a>
            </div>
            <? endif;?>
            <div class="contcom" style="overflow:hidden;width:76%;float:right;">
		<?php if ($params->get('show_object_title')) :?>
		<h<?php echo $item_heading; ?> class="nhazz-title">
			<?php if ($params->get('link_object_title',1) && $item->object_link != '') : ?>
				<a href="<?php echo $item->object_link;?>"><?php echo ucfirst($item->displayObjectTitle); ?></a>
			<?php else : ?>
				<?php echo ucfirst($item->displayObjectTitle); ?>
			<?php endif; ?>
		</h<?php echo $item_heading; ?>>
		<?php endif; ?>

		

		<div class="comment rounded <?php echo $params->get('show_avatar') ? 'avatar-indent' : '';?>">
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
                
                <?php if ($params->get('show_avatar')) :?>
			<?php echo $item->avatar; ?>
		<?php endif; ?>

		<?php if ($params->get('show_comment_author')) :?>
                <?php $ownerLink = JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $item->userid) , $item->displayAuthorName, array("class" => "author-link") ); ?>
		<span class="author"><? echo $item->creatby;?> : <?php echo $ownerLink; ?></span>
		<?php endif; ?>
		<?php if ($params->get('show_comment_date')) :?>
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
<?php endif; ?>
</ul>
<?php endif; ?>
</div>
</div>
<?
$sectionid=$params->get('sectionid');
$string=implode(",",$sectionid);
?>
<form name="mod_comment" id="mod_comment" method="post" action="#">
  <input type="hidden" name="option" value ="com_tvtma1080" />
  <input type="hidden" name="view" value="tvtmacomments" />
  <input type="hidden" name="task" value="getTVTMAComments" />
  <input type="hidden" name="format" value="ajax" />
  <input type="hidden" name="count" id="count" value="<?php echo $params->get('count');?>"/>
  <input type="hidden" name="group" id="group" value="<?php echo $params->get('comments_grouping');?>"/>
  <input type="hidden" name="ordering" id="ordering" value="<?php echo $params->get('ordering');?>"/>
  <input type="hidden" name="show_comment_title" id="show_comment_title" value="<?php echo $params->get('show_comment_title');?>"/>
  <input type="hidden" name="show_comment_author" id="show_comment_author" value="<?php echo $params->get('show_comment_author');?>"/>
  <input type="hidden" name="limit_comment_text" id="limit_comment_text" value="<?php echo $params->get('limit_comment_text');?>"/>
  <input type="hidden" name="readmore" id="readmore" value="<?php echo $params->get('readmore');?>"/>
  <input type="hidden" name="show_comment_date" id="show_comment_date" value="<?php echo $params->get('show_comment_date');?>"/>
  <input type="hidden" name="date_type" id="date_type" value="<?php echo $params->get('date_type');?>"/>
  <input type="hidden" name="date_format" id="date_format" value="<?php echo $params->get('date_format');?>"/>
  <input type="hidden" name="show_object_title" id="show_object_title" value="<?php echo $params->get('show_object_title');?>"/>
  <input type="hidden" name="link_object_title" id="link_object_title" value="<?php echo $params->get('link_object_title');?>"/>
  <input type="hidden" name="item_heading" id="item_heading" value="<?php echo $params->get('item_heading');?>"/>
  <input type="hidden" name="show_avatar" id="show_avatar" value="<?php echo $params->get('show_avatar');?>"/>
  <input type="hidden" name="show_image" id="show_image" value="<?php echo $params->get('show_image');?>"/>
  <input type="hidden" name="show_smiles" id="show_smiles" value="<?php echo $params->get('show_smiles');?>"/>
  <input type="hidden" name="catid" id="catid" value="<?php echo $params->get('catid');?>"/>
  <input type="hidden" name="sectionid" id="sectionid" value="<?php echo $string;?>"/>
  <input type="hidden" name="useCSS" id="useCSS" value="<?php echo $params->get('useCSS');?>"/>
  <input type="hidden" name="layout" id="layout" value="<?php echo $params->get('layout');?>"/>
  <input type="hidden" name="moduleclass_sfx" id="moduleclass_sfx" value="<?php echo $params->get('moduleclass_sfx');?>"/>
  <input type="hidden" name="cache" id="cache" value="<?php echo $params->get('cache');?>"/>
  <input type="hidden" name="cache_time" id="cache_time" value="<?php echo $params->get('cache_time');?>"/>
  <input type="hidden" name="cachemode" id="cachemode" value="<?php echo $params->get('cachemode');?>"/>
</form>

<script>
window.addEvent('domready', function(){
        $('surf').addEvent('change', function(e){
                var com_id = $('surf').get('value');
                switch(com_id){
                    case '0':
                        var sectionid = parseInt(com_id) - 2;
                        com_id=new Array("com_content","com_sobipro");
                        break;
                    case '1':
                        var sectionid = parseInt(com_id) - 2;
                        com_id="com_content";
                        break;
                    case '2':
                        var sectionid = parseInt(com_id) - 2;
                        com_id = "com_sobipro";
                        break;
                    case '3':
                        var sectionid = parseInt(com_id) - 2;
                        com_id = "com_sobipro";
                        break;
                }
                new Event(e).stop();
                var myRequest = new Request.HTML ({
                        url: 'index.php',
                        onRequest: function(){
                            $('mod_comment').set('text', 'loading...');
                        },
                        onComplete: function(response){
                            $('mod_comment').empty().adopt(response);
                        },
                        data: {
                            option: "com_tvtma1080",
                            view: "tvtmacomments",
                            task: "getTVTMAComments",
                            format : "ajax",
                            com_id: com_id,
                            sectionid: sectionid,
                            count: $("count").value,
                            show_comment_title : $("show_comment_title").value,
                            group : $("group").value,
                            ordering : $("ordering").value,
                            show_comment_author : $("show_comment_author").value,
                            limit_comment_text : $("limit_comment_text").value,
                            readmore : $("readmore").value,
                            show_comment_date : $("show_comment_date").value,
                            date_type : $("date_type").value,
                            date_format : $("date_format").value,
                            show_object_title : $("show_object_title").value,
                            link_object_title : $("link_object_title").value,
                            item_heading : $("item_heading").value,
                            show_avatar : $("show_avatar").value,
                            show_image : $("show_image").value,
                            show_smiles : $("show_smiles").value,
                            catid : $("catid").value,
                            sectionidarray : $("sectionid").value,
                            useCSS : $("useCSS").value,
                            layout : $("layout").value,
                            moduleclass_sfx : $("moduleclass_sfx").value,
                            cache: $("cache").value,
                            cache_time : $("cache_time").value,
                            cachemode : $("cachemode").value
                        }
                }).send();
        });
});
</script>
