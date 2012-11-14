<?php
$results = $this->results;
$html = '';

foreach ($results as $value):
    $html .= "<div class='project-$value->id project-box'>";
    //echo "<div id='$value->id'><a href='#'>".$value->name."</a>" . " -- <a href='' onclick='return false;' class='edit'>Sửa</a>  --  <a href='' onclick='return false;' class='delete'>Xóa</a><br/></div>";
    $SID = $this->section_id;
    $FieldNid = $this->field_id;
    $userlogin = JFactory::getUser();
    $isMe = ($userlogin->get('id') == $value->owner) ? true : false;
    $link = JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. $value->id .'&'. $FieldNid .'&sid=' . $SID . '&spsearchphrase=exact&search_user_id=' . $value->owner), ucfirst($value->name));
    $model =& $this->getModel();
    $entries = $model->getEntryOfProject($value->id,$this->field_id,4);
    $totalEntries = $model->getEntryOfProject($value->id,$this->field_id);
    $total = count($totalEntries);
    $i = 0; 
    foreach ($entries as $entry) {
        $image = $model->getImage($entry,$this->image_id,'thumb');
        if($i == 0) {
            $html .= "<div class='projectBigImage'>$image</div>";
        } else {
            $html .= "<div class='projectSmallImage'>$image</div>";
        }
        $i++;
        unset($entry);
    }

    $html .= "<div class='projectName'>" . $link . " ( {$total} )" . "</div>";
    //$html .= ($isMe) ? "<div class='control-project-tvtma'><a href='' onclick='return false;' class='delete'>Xóa</a><a href='' onclick='return false;' class='edit'>Sửa</a></div>" : "";
    $html .= "</div>";
    unset($entries);
    unset($value);
    unset($totalEntries);
endforeach; 
unset($results);
echo $html;
?>
