<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Hello World Component Controller
 */
class TvtProjectController extends JController
{
    function display()
    {
            parent::display();

    }
        
    function createProject()
    {
            $list = "";
            $model = $this->getModel();
            $projectName = JRequest::getVar('projectName');
            if($projectName == "" || strlen($projectName) < 6) {
                $text = "Tên dự án không hợp lệ hoặc quá ngắn ( Dưới 6 ký tự )";
                $html = "";
                $key = 0;
            } else {
                $userId = $model->getUserId();
                if(isset($userId)) {
                    $db =& JFactory::getDBO();
                    $data = new stdClass();
                    $data->id = null;
                    $data->owner = $userId;
                    $data->name = $projectName;
                    $data->publish = 1;
                    $data->active = 1;
                    $db = JFactory::getDBO();
                    $a = $db->insertObject( '#__tvtproject', $data, 'id');
                    $text =  "Tạo dự án " . $projectName . " thành công !";
                    $html = $model->getDrop();
                    $list = $model->displayList();
                    $key  = 1;
                }
            }
            $response['text'] = $text;
            $response['html'] = $html;
            $response['list'] = $list;
            $response['key'] = $key;
            echo json_encode($response);
            return;
            
    }
    
    
    /**
     *  Display dropdownlist in entry create 
     */
    function createDrop()
    {
        $model = $this->getModel();
        $html = $model->getDrop();
        echo json_encode($html);
        return;
    }
    
    /**
     * Delete project from project list of user 
     */
    function deleteProject()
    {
            $projectId = JRequest::getVar('id');
            $model = $this->getModel();
            $project = $model->getProject($projectId);
            $userid  = $model->getUserId();
            $html = "";
            if($userid == $project->owner) {
                $text = "Xóa dự án " . $project->name . " thành công !";
                //$model->delete($project->id);
                $model->changeValue($projectId, 'publish', 0);
                $key = 1;
                $html = $model->displayList();
            } else {
                $text = "Thao tác lỗi";
                $key = 0;
            }
            $response['text'] = $text;
            $response['key'] = $key;
            $response['html'] = $html;
            echo json_encode($response);
    }
    
    /**
     * Get Name Of project for edit action 
     */
    function getNameProject()
    {
        $text = "";
        $key = 0;
        $html = "";
        $projectId = JRequest::getVar('id');
        $model = $this->getModel();
        $project = $model->getProject($projectId);
        $userid  = $model->getUserId();
        if($userid == $project->owner) {
            $text = $project->name;
            $key = 1; 
        }
        $response['text'] = $text;
        $response['key'] = $key;
        $response['html'] = $html;
        echo json_encode($response);
    }
    
    /*
         * Change name of project 
         */
        function changeNameProject()
        {
            $projectId = JRequest::getVar('id');
            $newProjectName = JRequest::getVar('projectName');
            $model = $this->getModel();
            $project = $model->getProject($projectId);
            $userid  = $model->getUserId();
            $html = "";
            if($userid == $project->owner) {
                $model->changeValue($projectId, 'name', $newProjectName);
                $text = "Đổi tên dự án thành công !";
                $key = 1;
                $html = $model->displayList();
            } else {
                $text = "Thao tác lỗi";
                $key = 0;
            }
            $response['text'] = $text;
            $response['key'] = $key;
            $response['html'] = $html;
            echo json_encode($response);
        }
        
    
    
}
