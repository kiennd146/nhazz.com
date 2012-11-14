<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet($baseurl.'components/com_tvtma1080/js/sobiprotag.css');
?>

<table class="tagTable" border="1">
    <thead>
     <th><?php echo JText::_('COM_TVTMA_1080_IMAGE');?></th>
     <th><?php echo JText::_('COM_TVTMA_1080_TITLE');?></th>
     <th><?php echo JText::_('COM_TVTMA_1080_AUTHOR');?></th>
    </thead>
    <?php 
    $model =& $this->getModel();
    foreach ($this->totalId as $value) :
        $entry = SPFactory::Entry($value);
        $id = $entry->get('id');
        $author = $model->getUser($entry->get('owner'), 'name');
        $avatar = $model->getAvatar($entry->get('owner'));
        $authorLink = JHtml::link( JRoute::_('index.php?option=com_community&view=profile&userid=' . $entry->get('owner')) , $avatar . $author, array("class" => "user-link") );
        $fid = $entry->get('primary');
        $title = $entry->get('name');
        $url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
        $image = $model->getImage($entry, 'field_hnh_nh', 'ico');
    ?>
    <tr>
        <td width="20%" style="text-align: center"><?php echo JHtml::link( JRoute::_($url) , $image, array("class" => "image-link") ); ?></td>
        <td><?php echo JHtml::link( JRoute::_($url) , $title, array("class" => "image-link") ); ?></td>
        <td width="20%" style="text-align: center"><?php echo $authorLink; ?></td>
    </tr>
<?php endforeach;?>
</table>
