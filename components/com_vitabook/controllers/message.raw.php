<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport( 'joomla.application.application' );
require_once JPATH_SITE.'/components/com_vitabook/helpers/route.php';
/**
 * Message controller class.
 */
class VitabookControllerMessage extends JControllerForm
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        // Define standard task mappings.
        $this->registerTask('unpublish', 'publish'); // value = 0

        // Set the option as com_NameOfController.
        if (empty($this->option))
        {
            $this->option = 'com_vitabook';
        }
    }

    /**
     * Method to retrieve a message.
     * @return      JController             This object to support chaining.
     */
    public function getMessage()
    {
        // Check for request forgeries.
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
        // Get/Create the view
        $view = $this->getView('Vitabook', 'html');
        // Get/Create the models
        $view->setModel($this->getModel('message'), true);
        // we only want messages, no joomla
        JRequest::setVar('tmpl','component');
        // Display the view
        $view->display('message');
        return $this;
    }

    /**
     * Method to save a message
     *
     * @return  json array
     *   state   int     0 if unsuccessfull, 1 if successfull, 2 if moderation is required
     */
    public function save($key = null, $urlVar = null)
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(json_encode(array("state"=>0, "result" => JText::_('JINVALID_TOKEN'))));

        // Initialise variables.
        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel('message');
        $table = $model->getTable();
        //$data = JRequest::getVar('jform', array(), 'post', 'array');
		$data = array();
		
		// kiennd
		$action = JRequest::getVar('action', null);
		//Retrieve file details from uploaded file, sent from upload form
		$file = JRequest::getVar('file_upload', null, 'files', 'array');
		$file_exists = JRequest::getVar('file_uploaded', array());
		
		$file_images = array();
		
		if (count($file_exists)) {
			foreach($file_exists as $file_exist) {
				$file_exist = explode(",", $file_exist);
				if (count($file_exist) ==2 && JFile::exists(JPATH_BASE . DS . $file_exist[0]) && JFile::exists(JPATH_BASE . DS . $file_exist[1])) {
					$file_images[] = (object)array('origin'=>$file_exist[0], 'thumb'=>$file_exist[1]);
				}
			}
		}
		
		if ($file) {
			$filenames = $model->upload_img($file);
			$file_images = array_merge($file_images, $filenames);
		}
		//var_dump($file_images);die();
		$data['images'] = json_encode($file_images);
		
		$sobi_id = JRequest::getInt('dcs_photo_id', 0);
		//var_dump($sobi_id);
		if ($sobi_id > 0) {
			$entry = SPFactory::Entry($sobi_id);
			$field = SPConfig::unserialize( $entry->getField( 'field_hnh_nh' )->getRaw() );
			$data['images'] = json_encode(array((object)array('origin'=>$field['original'], 'thumb'=>$field['original'])));
		}
		
		$data['published'] = 1;
		$data['date'] = JFactory::getDate('utc')->toSql();
		
		$user = JFactory::getUser();
		$data['title'] = JRequest::getVar('dcs_title', '');
		$data['id'] = JRequest::getVar('dcs_id', 0);
		$data['message'] = JRequest::getVar('dcs_message', '');
		
		// check if category is photo or not
		$data['catid'] = JRequest::getVar('dcs_category', '');
		$photo_category = null;
		
		if ($data['catid'] == '') {
			$photo_category = $model->create_photo_category();
			$data['catid'] = $photo_category->id;
		}
		
		$data['jid'] = $user->get('id');
		
		// kiennd prevent anonymous user to create message
		if (!$data['jid']) {
		 	jexit(json_encode(array("state"=>1, "result" => $result)));
		 	return;
		} 
		$data['parent_id'] = 1;
		
		// end
		
        $context = "$this->option.edit.$this->context";
        $task = $this->getTask();

        // Determine the name of the primary key for the data.
        if (empty($key))
        {
            $key = $table->getKeyName();
        }
		
        // Populate the row id from the session.
        $recordId = $data[$key];
		//var_dump($data);
        // for messages which are being edited
        if(!empty($recordId))
        {
            // Access check.
            if (!$this->allowEdit($data, $key))
            {
                $state = JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED');
                // notify user their not allowed to edit this message
                jexit(json_encode(array("state"=>0, "result" => $state)));
            }
            // tell joomla we'll be editing this record
            $this->holdEditId($context, $recordId);
        }

        // Access check.
        if (!$this->allowSave($data, $key))
        {
            $result = JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED');
            // notify user their not allowed to save
            jexit(json_encode(array("state"=>0, "result" => $result)));
        }

        // Validate the posted data.
        // Sometimes the form needs some posted data, such as for plugins and modules.
        $form = $model->getForm($data, false);
        if (!$form)
        {
            $result = $model->getError();
            // notify user of our failure
            jexit(json_encode(array("state"=>0, "result" => $result)));
        }

        // Test whether the data is valid.
        $validData = $model->validate($form, $data);
	
        // Check for validation errors.
        if ($validData === false)
        {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up one validation error to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 1; $i++)
            {
                if ($errors[$i] instanceof Exception)
                {
                    $result = $errors[$i]->getMessage();
                }
                else
                {
                    $result = $errors[$i];
                }
            }

            // Save the data in the session.
            $app->setUserState($context . '.data', $data);

            // notify user of their failure
            jexit(json_encode(array("state"=>0, "result"=>$result)));
        }

        // Attempt to save the data.
        if (!$model->save($validData))
        {
            // Save the data in the session.
            $app->setUserState($context . '.data', $validData);

            // get error message.
            $result = JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError());

            // notify user of our failure
            jexit(json_encode(array("state"=>0, "result" => $result)));
        }

        // Clear the record id and data from the session.
        $this->releaseEditId($context, $recordId);
        $app->setUserState($context . '.data', null);

        // Invoke the postSave method to allow for the child class to access the model.
        $this->postSaveHook($model, $validData);

        // if user is guest and moderation is enabled
        if(JFactory::getUser()->get('guest') && JFactory::getApplication()->getParams()->get('guest_post_state') == 0)
            jexit(json_encode(array("state"=>2, "result" => JText::_( 'COM_VITABOOK_MESSAGE_WAIT' ) )));

        // get id of newly created message
        $result = $model->getItem()->get('id');

		$app = & JFactory::getApplication();
		
		switch($action) {
			case 'edit':
				$app->redirect(VitabookHelperRoute::getListRoute());
				break;
			case 'create':
				$app->redirect(VitabookHelperRoute::getEditRoute($result));
				break;
		}

        jexit(json_encode(array("state"=>1, "result" => $result)));
    }

    /**
     * Method to check if you can add a new record.
     *
     * Extended classes can override this if necessary.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   11.1
     */
    protected function allowAdd($data = array())
    {
        $user = JFactory::getUser();
        if($data['parent_id'] == 1)
        {
            return $user->authorise('vitabook.create.new', $this->option);
        }
        else
        {
            return $user->authorise('vitabook.create.reply', $this->option);
        }
    }

    /**
     * Method to delete a message (and it's children).
     */
    public function delete()
    {
        // Check for request forgeries.
        //JRequest::checkToken('request') or VitabookHelperMail::checkMailHash(JRequest::getInt('messageId'),'delete') or jexit(json_encode(array("success"=>0, "state" => JText::_('JINVALID_TOKEN'))));
		// check user
		
        // Initialise variables.
        $model  = $this->getModel('Message');
        $messageId = JRequest::getInt('id',0);
        
        $message = $model->getItem($messageId);
        
        if (!$message || $message->jid != JFactory::getUser()->get('id')) {
			$this->setRedirect(VitabookHelperRoute::getListRoute());
        	$this->redirect();
        	return;
		}
		
        // get images
        /**/
        if ($message->images != '' && $message->category && $message->category->alias != VITABOOK_CATEGORY_PHOTO_ALIAS) {
			$model->delete_img($message->images);		
		}
		
        
        $model->delete($messageId);		
		$this->setRedirect(VitabookHelperRoute::getListRoute());
        $this->redirect();
        
        /*
        if (!$model->delete($messageId))
        {
            $return = json_encode(array("success"=>0, "state" => $model->getError()));
        }
        else
        {
            $return = json_encode(array("success"=>1, "state" => ''));
        }
        */
        
		/*    
        if(JRequest::getVar('code')){
            $this->setRedirect(JRoute::_('index.php?option=com_vitabook'), JText::_('COM_VITABOOK_MESSAGE_DELETED'));
            $this->redirect();
        }
        */
        //jexit($return);
    }


    /**
     * FROM: JControllerAdmin class
     *
     * Method to publish a list of items
     *
     * @return  void
     *
     * @since   11.1
     */
    public function publish()
    {
        // Check for request forgeries.
        JRequest::checkToken('request') or VitabookHelperMail::checkMailHash(JRequest::getInt('messageId'),'publish') or jexit(json_encode(array("success"=>0, "state" => JText::_('JINVALID_TOKEN'))));

        // Get items to publish from the request.
        $cid = array(JRequest::getInt('messageId'));
        $data = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid))
        {
            $return = json_encode(array("success"=>0, "state" => JText::_($this->text_prefix . '_NO_ITEM_SELECTED')));
        }
        else
        {
            // Get the model.
            $model = $this->getModel('message');

            // Publish the items.
            if (!$model->publish($cid, $value))
            {
                $return = json_encode(array("success"=>0, "state" => $model->getError()));
            }
            else
            {
                $return = json_encode(array("success"=>1, "state" => $value));
            }
        }
        
        $messageId = JRequest::getInt('messageId');
        $code = JRequest::getVar('code');
        if(!empty($code)){
            switch ($value) {
                case 0:
                    $this->setRedirect(JRoute::_('index.php?option=com_vitabook&messageId='.$messageId, true, -1).'#'.$messageId, JText::_('COM_VITABOOK_MESSAGE_UNPUBLISHED'));
                    break;
                case 1:
                default:
                    $this->setRedirect(JRoute::_('index.php?option=com_vitabook&messageId='.$messageId, true, -1).'#'.$messageId);
                    break;
            }
            $this->redirect();
        }
        jexit($return);
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * Method to check if you can add a new record.
     *
     * Extended classes can override this if necessary.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key; default is id.
     *
     * @return  boolean
     *
     * @since   11.1
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Initialise variables.
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $user = JFactory::getUser();
        $userId = $user->get('id');

        // Check general edit permission first.
        if ($user->authorise('core.edit', 'com_vitabook'))
        {
            return true;
        }

        // Fallback on edit.own.
        // First test if the permission is available.
        if ($user->authorise('core.edit.own', 'com_vitabook'))
        {
            // Now test the owner is the user.
            $ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
            if (empty($ownerId) && $recordId)
            {
                // Need to do a lookup from the model.
                $record = $this->getModel('message')->getItem($recordId);
                if (empty($record))
                {
                        return false;
                }
                $ownerId = $record->jid;
                $messageDate = $record->date;
            }
            // Test if record can be edited
            return VitabookHelper::messageEdit($ownerId,$messageDate);
        }
    }

   /**
     * Function that allows child controller access to model data
     * after the data has been saved.
     *
     * @param   JModel  &$model     The data model object.
     * @param   array   $validData  The validated data.
     *
     * @return  void
     *
     * @since   11.1
     */
    protected function postSaveHook($model, $validData = Array())
    {
        // get admin mail param
        $admin_mail = JComponentHelper::getParams('com_vitabook')->get('admin_mail');

        // are we supposed to send e-mail notifications?
        if( empty($validData['id']) && ( ($admin_mail == 2) || ($admin_mail == 1 && empty($validData['jid'])) ) )
        {
            $item = $model->getItem();
            $data = $validData;
            $data['id'] = $item->get('id');
            VitabookHelperMail::sendAdminMail($data);
        }
    }
}
