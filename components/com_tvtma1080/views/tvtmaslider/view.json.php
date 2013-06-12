<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class TvtMA1080ViewTvtMASlider extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{       
        $lang = JFactory::getLanguage();
        $lang->load('mod_tvtma_slider', JPATH_SITE);
        
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}        
        $mainframe =& JFactory::getApplication();
        $menu_id = $mainframe->getUserState( "mod_tvtmaslider_menu_id" );
        $offset = $mainframe->getUserState( "mod_tvtmaslider_offset",0 );
        $limit = $mainframe->getUserState( "mod_tvtmaslider_limit",5 );
        $model =& $this->getModel();
        // Get list entry
        $totalImage = 0;
        //echo $totalImage;die();
        $lists = $model->dataListWithCount($menu_id, $offset, $limit, true, $totalImage);
		
        $datas = array();
		$i=0; 
        foreach ($lists as $list) {
        	if ($i == $limit) {
				break;
			}
        	$i++;
            $entry = SPFactory::Entry($list);
            $id = $entry->get('id');
            $fid = $entry->get('primary');
            $title = $entry->get('name');
            $url =  Sobi::Url( array('title' => $title, 'pid' => $fid, 'sid' => $id));
            $linkSobipro = JHtml::link( JRoute::_($url) , $title, array("class" => "") );
            $data['title'] = $linkSobipro;
            //$data['description'] = $entry->get('name');
            $data['description'] = "";
            //$data['image'] = $model->getImage($entry);
            //kiennd optimize
            $data['image'] = JImage::getCachedImage($model->getImage($entry), 1024, 512);
            
		    // Fix admin add null image
		    if($data['image'] == null){
				continue;
		    }
		    
		    // Fix image type
		    $checkType = $this->checkImageType($data['image']);
		    
		    if($checkType === false) {
				continue;
		    }
		
            $image = $model->getAvatar($entry->get('owner'));
            $data['author'] = JHtml::link( "#" ,$model->getUser($entry->get('owner'), 'name') . $image, array("class" => "tvtma_slider_more") );
            $data['about'] = $model->getPublicProfile($entry);
            $datas[] = $data;
            unset($data);
            unset($entry);
            unset($list);
        }
        
        unset($lists);
        $result = array();
        $result['json'] = $datas;
        unset($datas);
        $result['total'] = $totalImage . " " . JText::_('PICTURE');
        //var_dump($result);die();
        
        echo json_encode($result);
	}
	
	function checkImageType($imageLink){
	    $array = explode('.', $imageLink);
	    $ext = end($array);
	    if(in_array($ext, array('jpg','gif','png','JPG','PNG','GIF', 'jpeg', 'JPEG'))) {
		return true;
	    } else {
		return false;
	    }
	}
}
