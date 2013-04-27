<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::core();

// Create some shortcuts.
$params		= &$this->item->params;
$n              = count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
/**
 * Get random image of article
 * @param int $id
 * @param int $number
 * @return array 
 */
function getImage($id, $number = null) {
    require_once( JPATH_ROOT . DS . 'components' . DS . 'com_content' . DS . 'models' . DS . 'article.php');
    $a = new ContentModelArticle();
    $items = $a->getItem($id);
    $regexp = '/(<img[^>]*>)/siU';
    $iResults = preg_match_all($regexp, $items->fulltext, $aMatches);
    $iResults2 = preg_match_all($regexp, $items->introtext, $aMatches1);
    unset($items);
    $result = array_merge($aMatches[1], $aMatches1[1]);
    $img = array();
    foreach( $result as $img_tag)
    {
        preg_match_all('/(src)=("[^"]*")/i',$img_tag, $img[]);
    }
    unset($result);
    $images = array();
    foreach ($img as $value) {
        $src = str_replace('"', '', $value[2][0]);
        $src = str_replace("'", '', $src);
        $images[] = $src;
    }
    $n = count($images);
    if($n && $n >= 3) {
        if($number && $number <= $n) {
            $rand_keys = array_random($images,$number);
        } else {
            $rand_keys = array_random($images,$n);
        }
        $imgscache = array();
        foreach($rand_keys as $src) {
        	$imgscache[] = JImage::getCachedImage($src, 250, 230);
        }
        return $imgscache;
        //return $rand_keys;
    } else {
        return null;
    }
    
}

/**
 * Get full text of article
 * @param type $id
 * @return type 
 */
function getFulltext($id) {
    require_once( JPATH_ROOT . DS . 'components' . DS . 'com_content' . DS . 'models' . DS . 'article.php');
    $a = new ContentModelArticle();
    $items = $a->getItem($id);
    if($items->fulltext && $items->fulltext != "") {
        $content =  strip_tags($items->fulltext);
    } else {
        $content =  strip_tags($items->introtext);
    }
    $content = ltrim($content);
    unset($items);
    return $content;
}


/**
 * Sort random array
 * @param array $arr
 * @param int $num
 * @return array 
 */
function array_random($arr, $num = 1) {
    shuffle($arr);
    $r = array();
    for ($i = 0; $i < $num; $i++) {
        $r[] = $arr[$i];
    }
    return $num == 1 ? $r[0] : $r;
}

/**
 * Cut string
 * @param string $text
 * @param int $length
 * @param string $replacer
 * @param type $isStrips
 * @param type $stringtags
 * @return type 
 */
function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {
		$string = $isStrips ? strip_tags($text, $stringtags) : $text;
		if (mb_strlen($string) < $length)
			return $string;
		$string = mb_substr($string, 0, $length);
                $string = removeSpace($string);
		return $string . $replacer;
}

/**
 * Get first sentence of content
 * @param type $content
 * @return type 
 */
function first_sentence($content, $number = 1) {
    $content = html_entity_decode(strip_tags($content));
    $content = removeSpace($content);
    $dot = ".";
    $i = 1;
    $pos = strpos($content, $dot);
    if($pos === false) {
        return $content;
    }
    else {
        while($i < $number && $pos) {
            $offset = $pos + 1;
            $pos = stripos ($content, $dot, $offset);
            $i++;
        }
        //$offset = $pos + 1; //prepare offset
        //$pos2 = stripos ($content, $dot, $offset); 
        $first_two = substr($content, 0, $pos + 1); 
        return $first_two;
    }
}

function removeSpace($string) {
    $string = preg_replace('~(\s+)~', ' ', $string);
    return $string;

}


/**
 * Show html of featured article
 * @param type $id
 * @param type $number 
 */
function showFeaturedArticle ($article, $number = 1) {
   $html = "";
   $introText = substring($article->introtext,150);
   $images  = getImage($article->id,$number);
   $imageHtml = "<img src='{$images}'/>";
   $url = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
   $title = strip_tags($article->title);
   $articleLink = JHtml::link($url,$title, array('class' => 'more-link'));
   $imageLink = JHtml::link($url,$imageHtml, array('class' => ''));
   $moreLink = JHtml::link($url,"Chi tiết", array('class' => 'more-link'));
   $introText .= $introText . $moreLink;
   $authorLink =  JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $article->created_by) , $article->author, array("class" => "author-link") );
   $createDate = JHtml::_('date', $article->displayDate, JText::_('DATE_FORMAT_LC3'));
   $html .= <<<html
    <div class='article-featured'>
        <div class='article-featured-left'>
            <div class='article-featured-image'>{$imageLink}</div>
        </div>
        <div class='article-featured-right'>
            <div class='article-title'>
                {$articleLink}
            </div>
            <div class='article-featured-introtext'>
                {$introText}
            </div>
            <div class='article-featured-author'>
                T/g : {$authorLink}
                <div>{$createDate}</div>
            </div>
        </div>
    </div>
   
html;
    return $html;
}



/**
 * Show html of normal article
 * @param type $id
 * @param type $number 
 */
function showNormalArticle($article, $number = null) {
    $html = "";
    $introText = first_sentence($article->introtext,2);
    $images  = getImage($article->id,$number);
    $imageHtml = '';
    if(count($images) == 1) {
        $imageHtml = "<img src='{$images}'/>";
    } else {
        foreach ($images as $image) {
        $imageHtml .= "<img src='{$image}'/>";
        }
    }
    
    $url = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
    $title = strip_tags($article->title);
    $articleLink = JHtml::link($url,$title, array('class' => 'more-link'));
    $moreLink = JHtml::link($url,"Chi tiết", array('class' => 'more-link'));
    // Get fulltext
    $content = substring(getFulltext($article->id),500);
    // Remove introtext from fulltext
    $content = str_replace($introText, '', $content);
    
    if($introText == $content) {
        $introText = "";
    }
    $contentMore = $content . $moreLink;
    $authorLink =  JHtml::link( CRoute::_('index.php?option=com_community&view=profile&userid=' . $article->created_by) , $article->author, array("class" => "author-link") );
    $createDate = JHtml::_('date', $article->displayDate, JText::_('DATE_FORMAT_LC3'));
    
    $html .= <<<html
    <div class="article-normal" >
        <div class='article-title'>
            {$articleLink}
        </div>
        <div class='article-intro-text'>
            {$introText}
        </div>
        <div class='article-image-random'>
            {$imageHtml}
        </div>
        <div class='article-content'>
            <div class='article-content-left'>
                {$contentMore}
            </div>
            <div class='article-content-right'>
                T/g : {$authorLink}
                <div>{$createDate}</div>
            </div>
            
        </div>
    </div>
html;
    return $html;
}

//echo "<pre>";
//var_dump($this->items);
//echo "</pre>";
//die();
?>

<?php if (empty($this->items)) : ?>

	<?php if ($this->params->get('show_no_articles', 1)) : ?>
	<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
	<?php endif; ?>

<?php else : ?>

<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
    <style>
        
        #rightMain {
        padding: 0;
        background : #fafbf6;
        }
        #rightMain.noRight, .noLeft {
            width : 810px;
        }
        body {

        }
        
    </style>
    <?php
    foreach ($this->items as $i => $article) : 
        if($article->featured == 1) {
            $html =  showFeaturedArticle($article,1);
        } else {
            $html =  showNormalArticle($article,3);
        }
        echo $html;
    endforeach;
    ?>
    
<?php endif; ?>
<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		 	<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
</form>
<?php  endif; ?>
