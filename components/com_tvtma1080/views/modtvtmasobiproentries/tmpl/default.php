<?php
$bigCats = $this->bigCats;
$n = count($bigCats);
$html = "";
$i = 1;
foreach ($bigCats as $bigCat) {
    if ($bigCat == reset($bigCats)) {
	$html .= '<div class="mega" id="megacontent">';
	$html .= '<div class="column">';
	$html .= '<ul style="display:block;">';
    }
    $html .= '<li style="display:inherit">';
    $categories = SPFactory::Category($bigCat);
    $id = $categories->get('id');
    $fid = $categories->get('primary');
    $title = $categories->get('name');
    $url = Sobi::Url(array('title' => $title, 'pid' => $fid, 'sid' => $id));
    $linkSobipro = JHtml::link(JRoute::_($url), ucfirst($title), array("rel" => $id));
    $html .= $linkSobipro;
    $html .= '</li>';
    if ($i == $n / 2) {
	$html .= '</ul>';
	$html .= '</div>';
	$html .= '<div class="column">';
	$html .= '<ul style="display:block;">';
    }
    if ($bigCat == end($bigCats)) {
	$html .= '</ul></div></div>';
    }
    $i++;
    unset($categories);
}
foreach ($bigCats as $bigCat) {
    $categories = SPFactory::Category($bigCat);
    $childs = $categories->getChilds('category', true);
    if(count($childs) > 0) :
    $html .= '<div id="'. $bigCat .'" style="width:300px" class="mega">';
    $html .= '<ul class="ulmenu">';
    foreach ($childs as $child) {
	$catChild = SPFactory::Category($child);
	$id = $catChild->get('id');
	$fid = $catChild->get('primary');
	$title = $catChild->get('name');
	$url = Sobi::Url(array('title' => $title, 'pid' => $fid, 'sid' => $id));
	$linkSobipro = JHtml::link(JRoute::_($url), ucfirst($title), array("rel" => $id));
	$html .= '<li>' . $linkSobipro . '</li>';
	unset($catChild);
    }
    $html .= '</ul>';
    $html .= '</div>';
    endif;
    unset($categories);
}
unset($bigCats);
echo $html;
?>
