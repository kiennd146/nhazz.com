<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHTML::_('behavior.mootools');
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet($baseurl.'modules/mod_tvtma_banners/includes/tvtma_banners.css');
require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
require_once JPATH_ROOT . '/modules/mod_tvtma_banners/helper.php';
$baseurl = JURI::base();
?>
<div class="bannergroup<?php echo $moduleclass_sfx ?>">
<?php if ($headerText) :  echo $headerText; endif; ?>
    <?php
    $arrayCat = array();
    $arrayCat['']['val'] = 0;
    $arrayCat['']['text'] = JText::_('MOD_TVTMA_BANNER_SELECT');
    foreach ($cat as  $key => $value) {
        $arrayCat[$key]['val'] = $value->id;
        $arrayCat[$key]['text'] = $value->title;
    }
    echo JHTML::_('select.genericlist', $arrayCat, 'lstBanner', array('class' => 'ajaxSelect bannerDrop'), 'val', 'text');
    ?>
    <div id="bannersShow">
    <?php 
    $status = array('badImage', 'goodImage');
    for($i=0;$i<2;$i++):?>
        <div class="<?php echo $status[$i];?>">
        <?php foreach($list as $item):?>
            <?php 
            $classSpecial = modTVTMABannersHelper::getImageDetail($item->params->get('imageurl'));
            if($classSpecial != $status[$i])
                continue;
            ?>
            <div class="banneritem">
                    <?php $link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);?>
                    <?php if($item->type==1) :?>
                            <?php // Text based banners ?>
                            <?php echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);?>
                    <?php else:?>
                            <?php $imageurl = $item->params->get('imageurl');?>
                            <?php $width = $item->params->get('width');?>
                            <?php $height = $item->params->get('height');?>
                            <?php if (BannerHelper::isImage($imageurl)) :?>
                                    <?php // Image based banner ?>
                                    <?php $alt = $item->params->get('alt');?>
                                    <?php $alt = $alt ? $alt : $item->name ;?>
                                    <?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER') ;?>
                                    <?php if ($item->clickurl) :?>
                                            <?php // Wrap the banner in a link?>
                                            <?php $target = $params->get('target', 1);?>
                                            <?php if ($target == 1) :?>
                                                    <?php // Open in a new window?>
                                                    <a
                                                            href="<?php echo $link; ?>" target="_blank"
                                                            title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
                                                            <img
                                                                    src="<?php echo $baseurl . $imageurl;?>"
                                                                    alt="<?php echo $alt;?>"
                                                                    <?php if (!empty($width)) echo 'width ="'. $width.'"';?>
                                                                    <?php if (!empty($height)) echo 'height ="'. $height.'"';?>
                                                            />
                                                    </a>
                                            <?php elseif ($target == 2):?>
                                                    <?php // open in a popup window?>
                                                    <a
                                                            href="<?php echo $link;?>" onclick="window.open(this.href, '',
                                                                    'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
                                                                    return false"
                                                            title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
                                                            <img
                                                                    src="<?php echo $baseurl . $imageurl;?>"
                                                                    alt="<?php echo $alt;?>"
                                                                    <?php if (!empty($width)) echo 'width ="'. $width.'"';?>
                                                                    <?php if (!empty($height)) echo 'height ="'. $height.'"';?>
                                                            />
                                                    </a>
                                            <?php else :?>
                                                    <?php // open in parent window?>
                                                    <a
                                                            href="<?php echo $link;?>"
                                                            title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
                                                            <img
                                                                    src="<?php echo $baseurl . $imageurl;?>"
                                                                    alt="<?php echo $alt;?>"
                                                                    <?php if (!empty($width)) echo 'width ="'. $width.'"';?>
                                                                    <?php if (!empty($height)) echo 'height ="'. $height.'"';?>
                                                            />
                                                    </a>
                                            <?php endif;?>
                                    <?php else :?>
                                            <?php // Just display the image if no link specified?>
                                            <img
                                                    src="<?php echo $baseurl . $imageurl;?>"
                                                    alt="<?php echo $alt;?>"
                                                    <?php if (!empty($width)) echo 'width ="'. $width.'"';?>
                                                    <?php if (!empty($height)) echo 'height ="'. $height.'"';?>
                                            />
                                    <?php endif;?>
                            <?php elseif (BannerHelper::isFlash($imageurl)) :?>
                                    <object
                                            classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                                            codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
                                            <?php if (!empty($width)) echo 'width ="'. $width.'"';?>
                                            <?php if (!empty($height)) echo 'height ="'. $height.'"';?>
                                    >
                                            <param name="movie" value="<?php echo $imageurl;?>" />
                                            <embed
                                                    src="<?php echo $imageurl;?>"
                                                    loop="false"
                                                    pluginspage="http://www.macromedia.com/go/get/flashplayer"
                                                    type="application/x-shockwave-flash"
                                                    <?php if (!empty($width)) echo 'width ="'. $width.'"';?>
                                                    <?php if (!empty($height)) echo 'height ="'. $height.'"';?>
                                            />
                                    </object>
                            <?php endif;?>
                    <?php endif;?>
                    <div class="clr"></div>
            </div>
    <?php endforeach; ?>
    </div>
    <?php endfor; ?>
    
    </div>

<?php if ($footerText) : ?>
	<div class="bannerfooter">
		<?php echo $footerText; ?>
	</div>
<?php endif; ?>
</div>
<form name="mod_banners" id="mod_banners" method="post" action="#">
  <input type="hidden" name="option" value ="com_tvtma1080" />
  <input type="hidden" name="view" value="tvtmabanners" />
  <input type="hidden" name="task" value="getTVTMABanners" />
  <input type="hidden" name="format" value="ajax" />
  <input type="hidden" name="cat_id" value="" id="testId" />
  
  <input type="hidden" name="target" id="target" value="<?php echo $params->get('target');?>"/>
  <input type="hidden" name="count" id="countb" value="<?php echo $params->get('count');?>"/>
  <input type="hidden" name="cid" id="cid" value="<?php echo $params->get('cid');?>"/>
  <input type="hidden" name="tag_search" id="tag_search" value="<?php echo $params->get('tag_search');?>"/>
  <input type="hidden" name="ordering" id="ordering" value="<?php echo $params->get('ordering');?>"/>
  <input type="hidden" name="header_text" id="header_text" value="<?php echo $params->get('header_text');?>"/>
  <input type="hidden" name="footer_text" id="footer_text" value="<?php echo $params->get('footer_text');?>"/>
  <input type="hidden" name="layout" id="layout" value="<?php echo $params->get('layout');?>"/>
  <input type="hidden" name="moduleclass_sfx" id="moduleclass_sfx" value="<?php echo $params->get('moduleclass_sfx');?>"/>
  <input type="hidden" name="cache" id="cache" value="<?php echo $params->get('cache');?>"/>
  <input type="hidden" name="cache_time" id="cache_time" value="<?php echo $params->get('cache_time');?>"/>
  
  
</form>

<script>
window.addEvent('domready', function(){
        $('lstBanner').addEvent('change', function(e){
                var cat_id = $('lstBanner').get('value');
                new Event(e).stop();
                var myRequest = new Request.HTML ({
                        url: 'index.php',
                        onRequest: function(){
                            $('bannersShow').set('text', 'loading...');
                        },
                        onComplete: function(response){
                            $('bannersShow').empty().adopt(response);
                        },
                        data: {
                            option: "com_tvtma1080",
                            view: "tvtmabanners",
                            task: "getTVTMABanners",
                            format : "ajax",
                            cat_id: cat_id,
                            target: $("target").value,
                            count: $("countb").value,
                            cid: $("cid").value,
                            tag_search : $("tag_search").value,
                            ordering : $("ordering").value,
                            header_text : $("header_text").value,
                            footer_text : $("footer_text").value,
                            layout : $("layout").value,
                            moduleclass_sfx : $("moduleclass_sfx").value,
                            cache: $("cache").value,
                            cache_time : $("cache_time").value
                        }
                }).send();
        });
});
</script>

