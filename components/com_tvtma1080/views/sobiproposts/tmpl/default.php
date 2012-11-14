<?php
$results = $this->results;
$html = '';
foreach ($results as $value):
    $entry = SPFactory::Entry($value);
    $id = $entry->get('id');
    $fid = $entry->get('primary');
    $title = ucfirst($entry->get('name'));
    $url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
    //$linkSobipro = JHtml::link( JRoute::_($url) , $title, array("class" => "") );
    $field = SPConfig::unserialize( $entry->getField( $this->field_nid )->getRaw() );
    $urlImage = JURI::base() . $field['original'];
    $image =  "<img src='{$urlImage}' />";
    $linkImage = JHtml::link( JRoute::_($url) , $image, array("class" => "") );
    //$html .= '<div class="tb-row">';
    $html .= "<div class='imageBox'>{$linkImage}</div>";
    
    //$html .= "<div class='col2'>{$linkSobipro}</div>";
    //$categories = $entry->get('parent');
    //$catName = $entry->get('categories');
    //$catUrl =  Sobi::Url( array('title' => $catName[$categories]['name'], 'sid' => $categories));
    //$linkCat = JHtml::link( JRoute::_($catUrl) , $catName[$categories]['name'], array("class" => "") );
    //$html .= "<div class='col3'>{$linkCat}</div>";
    //$createDate = $entry->get('createdTime');
    //$html .= "<div class='col4'>{$createDate}</div>";
    //$html .= '</div>';
unset($entry);
endforeach; 
unset($results);
echo $html;
?>
