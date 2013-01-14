<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

// No direct access.
defined('_JEXEC') or die;

// Import modelform library
jimport('joomla.application.component.modeladmin');

// Import dispatcher
jimport('joomla.event.dispatcher');
jimport('joomla.application.categories');
jimport('joomla.filesystem.file');

define('VITABOOK_CATEGORY_PHOTO_ALIAS', 'photoquestion');
if (!defined('DISCUSS_PHOTOS_PATH')) define('DISCUSS_PHOTOS_PATH', "images/discuss");
if (!defined('DISCUSS_PHOTOS_THUMB')) define('DISCUSS_PHOTOS_THUMB', "thumbs");

require_once ( implode( DS, array( JPATH_ROOT, 'components', 'com_sobipro', 'lib', 'sobi.php' ) ) );
Sobi::Init( JPATH_ROOT, JFactory::getConfig()->getValue( 'config.language' ));
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');

/**
 * Vitabook model.
 */
class VitabookModelMessage extends JModelAdmin
{
    protected $_canDo;

    /**
     * Constructor.
     * @param   array  $config  An optional associative array of configuration settings.
     * @see     JController
     * @since   11.1
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        // Get user permissions
        $this->_canDo = VitabookHelper::getActions();
    }


    /**
     * Method to get a single record.
     * @param   integer  $pk  The id of the primary key.
     * @return  mixed    Object on success, false on failure.
     * @since   11.1
     */
    public function getItem($pk = null)
    {
        // Initialise variables.
        $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
        $table = $this->getTable();

        if ($pk > 0)
        {
            // Attempt to load the row.
            $return = $table->load($pk);

            // Check for a table object error.
            if ($return === false && $table->getError())
            {
                $this->setError($table->getError());
                return false;
            }
        }

        // Convert to the JObject before adding other data.
        $properties = $table->getProperties(1);
        $item = JArrayHelper::toObject($properties, 'JObject');

        if (property_exists($item, 'params'))
        {
            $registry = new JRegistry;
            $registry->loadString($item->params);
            $item->params = $registry->toArray();
        }

        // get actions
        $item->actions = VitabookHelper::messageActions($this->_canDo,$item);
        // get avatar
        $item->avatar = VitabookHelperAvatar::messageAvatar($item);
        // format date
        $item->date = VitabookHelper::formatDate($item);
        $item->category = JCategories::getInstance('Vitabook')->get($item->catid);

		// get photos
		$item->photos = array();
		
		$images = json_decode($item->images);
		
		foreach($images as $image) {
			if (file_exists(JPATH_BASE . DS . $image->origin) && file_exists(JPATH_BASE . DS . $image->thumb)) {
				$image_photo = (object)array(
					'origin'=>JURI::base() . DS . $image->origin,
					'thumb'=>JURI::base() . DS . $image->thumb
				);
			}
			else {
				$image_photo = (object)array(
					'origin'=>JURI::base() . DISCUSS_PHOTOS_PATH . DS . 'no_photo.jpg',
					'thumb'=>JURI::base() . DISCUSS_PHOTOS_PATH . DS . 'no_photo.jpg'
				);
			}
			
			$item->photos[] = $image_photo;
		}
		
		$user = CFactory::getUser($item->jid);
		
		$item->user_avatar = $user->getAvatar();
		$item->user_name = $user->get('name');
		$item->user_link = CRoute::_('index.php?option=com_community&view=profile&userid=' . $user->get('id'));
		
        return $item;
    }


    /**
     * Method override to check if you can edit an existing record.
     * @param       array   $data   An array of input data.
     * @param       string  $key    The name of the key for the primary key.
     * @return      boolean
     * @since       1.7
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Check specific edit permission then general edit permission.
        return JFactory::getUser()->authorise('core.edit', 'com_vitabook.message.'.((int) isset($data[$key]) ? $data[$key] : 0))
            or parent::allowEdit($data, $key);
    }


   /**
    * Method to test whether a record can be deleted.
    * @param   object  $record  A record object.
    * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
    * @since   11.1
    */
   protected function canDelete($record)
   {
        // override joomla authorization for admin email links
        if(VitabookHelperMail::checkMailHash(JRequest::getInt('messageId'),'delete'))
            return true;
        // proxy to JModelAdmin for normal requests
        return parent::canDelete($record);
   }

   /**
    * Method to test whether the state of a record can be changed.
    * @param   object  $record  A record object.
    * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
    * @since   11.1
    */
   protected function canEditState($record)
   {
        // override joomla authorization for admin email links
        if(VitabookHelperMail::checkMailHash(JRequest::getInt('messageId'),'publish'))
            return true;
        // proxy to JModelAdmin for normal requests
        return parent::canEditState($record);
   }

    /**
     * Returns a reference to the a Table object, always creating it.
     * @param       type    The table type to instantiate
     * @param       string  A prefix for the table class name. Optional.
     * @param       array   Configuration array for model. Optional.
     * @return      JTable  A database object
     * @since       1.6
     */
    public function getTable($type = 'Message', $prefix = 'VitabookTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     * @param       array   $data           An optional array of data for the form to interogate.
     * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
     * @return      JForm   A JForm object on success, false on failure
     * @since       1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_vitabook.message', 'message', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }

        // Skip site-field if necessary
        if(JFactory::getApplication()->getParams()->get('vbForm_site', 1) == 0)
        {
            $form->removeField('site');
        }
        // Skip location-field if necessary
        if(JFactory::getApplication()->getParams()->get('vbForm_location', 0) == 0)
        {
            $form->removeField('location');
        }
        
        $user = JFactory::getUser();
        $userId = $user->get('id');
        // Skip captcha for logged-in users (necessary to prevent validation trouble)
        if(!empty($userId))
        {
            $form->removeField('captcha');
        }
        // If configured skip captcha for guest also
        if(JFactory::getApplication()->getParams()->get('guest_captcha') == 0)
        {
            $form->removeField('captcha');
        }
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     * @return      mixed   The data for the form.
     * @since       1.6
     */
    protected function loadFormData()
    {
        $data = (object) array();
        $user = JFactory::getUser();
        //-- Autofill some form fields of new messages for registered users
        if(!$user->get('guest'))
        {
            $data->name = $user->name;
            $data->email = $user->email;
        }
        return $data;
    }


    /**
     * Prepare and sanitise the table prior to saving.
     * @since       1.6
     */
    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');

        if (empty($table->id))
        {
            $table->setLocation($table->parent_id, 'last-child');
        }
    }

	/*
	protected function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-";
		
		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
		return $str;
	}
	*/
	
	protected function upload_img($file) {
		
		//Clean up filename to get rid of strange characters like spaces etc
		$name = array();
		if (count($file['name']) <=0 || count($file['name']) > 4 ) return $name;
		
		//-- Get parameters for image uploading
		$params = JComponentHelper::getParams('com_vitabook');
		$imageQuality = $params->get('upload_image_quality');
		$imageWidth = $params->get('upload_image_width');
		
		$imageThumbWidth = $params->get('upload_image_thumb_width');

		$succes = 0;

		foreach($file['name'] as $i=>$fname) {
			$succes = 0;
			//-- Check if file type is supported
			$types = array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg', 'image/x-png');
			if(!in_array($file["type"][$i], $types)) {
				continue;
			}
			try {
				$fileInfo = JImage::getImageFileProperties($file["tmp_name"][$i]);
			}
			catch(Exception $e) {
				continue;
			}
						
			$image = new JImage;
			
			try {
				$image->loadFile($file["tmp_name"][$i]);
			}
			//-- Loading image failed, stop!
			catch(Exception $e) {
				continue;
			}
			
			if(!$image->isLoaded()) {
                continue;
            }
			
			//-- Check for memory
			if(!VitabookHelper::checkMemory($image)) {
				continue;
			}
			
			$fileName = md5($image->getPath() + (rand()*1000));
			//-- Get correct extension
			switch (JImage::getImageFileProperties($image->getPath())->mime) {
				case 'image/gif':
					$extension = 'gif';
					$img_type = 'IMAGETYPE_GIF';
					break;
				case 'image/png':
					$extension = 'png';
					$img_type = 'IMAGETYPE_PNG';
					break;
				case 'image/x-png':
					$extension = 'png';
					$img_type = 'IMAGETYPE_PNG';
					break;
				case 'image/jpeg':
					$extension = 'jpg';
					$img_type = 'IMAGETYPE_JPEG';
					break;
				case 'image/pjpeg':
					$extension = 'jpg';
					$img_type = 'IMAGETYPE_JPEG';
					break;
				default :
					continue;
			}
			
			//-- Where to store the image
			$created_filename = array();
			$created_filename['origin'] = DISCUSS_PHOTOS_PATH . DS . $fileName.'.'.$extension;
			$dest = JPATH_BASE . DS . $created_filename['origin'];
			
			$created_filename['thumb'] = DISCUSS_PHOTOS_PATH . DS . $fileName . DISCUSS_PHOTOS_THUMB . '.'.$extension;
			$dest_thumb = JPATH_BASE . DS . $created_filename['thumb'];
			//$loc = JURI::root().'media/com_vitabook/images/uploaded/'.$fileName.'.'.$extension;

			//-- Resize image proportional to its original size
			if (intval($fileInfo->width) > $imageWidth) {
				try {
					$image->resize($imageWidth,'100%',false);
				}
				//-- Loading image failed, stop!
				catch(Exception $e) {
					continue;
				}
			}
			
			//-- Copy the renamed file to media/com_vitabook/images.
			@$image->toFile($dest, $img_type, array('quality' => $imageQuality));

			//-- Check if uploading was successful
			if(JFile::exists($dest)) {
				$succes = 1;
			}
			
			//-- create thumb
			try {
				$thumb = $image->resize($imageThumbWidth,'100%',true);
			}
			//-- Loading image failed, stop!
			catch(Exception $e) {
				continue;
			}
			
			//-- Copy the renamed file to media/com_vitabook/images.
			@$thumb->toFile($dest_thumb, $img_type, array('quality' => $imageQuality));

			//-- Check if uploading was successful
			if(JFile::exists($dest_thumb)) {
				$succes = 1;
			}
			
			if ($succes==1) $name[] = $created_filename;
			
		}
		return $name;
	}
	
	protected function create_photo_category() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('c.*');
		$query->from('#__categories AS c');
		$query->where("c.alias = '".VITABOOK_CATEGORY_PHOTO_ALIAS."'");
		$db->setQuery($query);
		$data = $db->loadObject();
		
		if (!$data) {
			$data = new stdClass();
			$data->title = JText::_('VITABOOK_CATEGORY_PHOTO_QUESTION');
			$data->alias = VITABOOK_CATEGORY_PHOTO_ALIAS;
			$data->published = 1;
			$data->parent_id = 1;
			$data->level = 1;
			$data->metadata = '{"author":"","robots":""}';
			$data->extension = 'com_vitabook';
			$data->language = '*';
			$data->access = '1';
			
			$user = JFactory::getUser();
			//$data->	created_user_id = $user->get('id');

			if (!$db->insertObject( '#__categories', $data, 'id' )) {
				echo $db->stderr();
			}
		}
		return $data;
	}
	
    /**
     * Method to validate the form data. Override JModelForm to preserve youtube and vimeo iframes/div-objects
     *
     * @param   object  $form   The form to validate against.
     * @param   array   $data   The data to validate.
     * @param   string  $group  The name of the field group to validate.
     * @return  mixed  Array of filtered data if valid, false otherwise.
     * @see     JFormRule
     * @see     JFilterInput
     * @since   11.1
     */
    public function validate($form, $data, $group = null)
    {
        //Retrieve file details from uploaded file, sent from upload form
		$file = JRequest::getVar('file_upload', null, 'files', 'array');
		if ($file) {
			$filenames = $this->upload_img($file);
			$data['images'] = json_encode($filenames);
		}
		
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
		$data['message'] = JRequest::getVar('dcs_message', '');
		
		// check if category is photo or not
		$data['catid'] = JRequest::getVar('dcs_category', '');
		$photo_category = null;
		if ($data['catid'] == '') {
			$photo_category = $this->create_photo_category();
			$data['catid'] = $photo_category->id;
		}
		
		$data['jid'] = $user->get('id');
		$data['parent_id'] = 1;
		
		// fill in form fields a user can't be trusted with
            // for new messages
			/*
            if(empty($data['id']))
            {
                $data['date'] = JFactory::getDate('utc')->toSql();
                $data['ip']= $_SERVER['REMOTE_ADDR'];
                // for logged in users
                $user = JFactory::getUser();
                if(!$user->get('guest'))
                {
                    $data['jid'] = JFactory::getUser()->get('id');
                    $data['published'] = 1;
                }
                // for guests
                else
                {
                    if(JFactory::getApplication()->getParams()->get('guest_post_state'))
                        $data['published'] = 1;
                }
            }
            // for edited messages
            else{
                // nothing to do
            }
			*/
        // protect embedded youtube/vimeo iframes from joomla safehtml filtering
            // detect iframes
			/*
            if(JFactory::getUser()->authorise('vitabook.insert.video', 'com_vitabook'))
            {
                if(strpos($data['message'],'<iframe src="http://www.youtube.com/embed/') !== false || strpos($data['message'],'<iframe src="http://player.vimeo.com/video/') !== false)
                {
                    // initialize variables
                    $youtube = false; 
                    $vimeo = false;
                    // store youtube/vimeo video ids
                    preg_match_all('#(?<=youtube.com\/embed\/)[a-zA-Z0-9_-]+(?=\?wmode)#', $data['message'], $youtube);
                    preg_match_all('#(?<=player.vimeo.com\/video\/)[\d]+(?=" frameb)#', $data['message'], $vimeo);
                    // replace iframes in message with temporary markers
                    if(!empty($youtube))
                    {
                        foreach ($youtube[0] as $key => $videoId):
                            $pattern = '/(<iframe src="http:\/\/www.youtube.com\/embed\/'.$videoId.'\?wmode\=opaque" frameborder="0" width="350px" height="300px"><\/iframe>)/';
                            $data['message'] = preg_replace($pattern, '##youtube_'.$key, $data['message']);
                        endforeach;
                    }
                    if(!empty($vimeo))
                    {
                        foreach ($vimeo[0] as $key => $videoId):
                            $pattern = '/(<iframe src="http:\/\/player.vimeo.com\/video\/'.$videoId.'" frameborder="0" width="350px" height="300px"><\/iframe>)/';
                            $data['message'] = preg_replace($pattern, '##vimeo_'.$key, $data['message']);
                        endforeach;
                    }
                }
            }

//TODO: This ugly hack has to go, someday ;-)
        // (1/2) ugly hack to make possible that site is edited to be empty. Override for JTable store method ($updateNulls=true) would be preferable, but imposes an unnecessary db-scheme change
        if(JFactory::getApplication()->getParams()->get('vbForm_site') && $data['site'] == '')
        {
            $data['site'] = "http://clear.clr";
        }

        // Get the data cleaned and validated by proxying the task to JModelForm
        $data = parent::validate($form, $data, $group);

        // (2/2) ugly hack to make possible that site is edited to be empty. Override for JTable store method ($updateNulls=true) would be preferable, but imposes an unnecessary db-scheme change
        if(JFactory::getApplication()->getParams()->get('vbForm_site') && $data['site'] == 'http://clear.clr')
        {
            $data['site'] = "";
        }

        // if necessary, restore iframes at the temporary markers
        if(!empty($youtube))
        {
            foreach ($youtube[0] as $key => $videoId):
                $pattern = '/(##youtube_'.$key.')/';
                $data['message'] = preg_replace($pattern, '<iframe src="http://www.youtube.com/embed/'.$videoId.'?wmode=opaque" frameborder="0" width="350px" height="300px"></iframe>', $data['message']);
            endforeach;
        }
        if(!empty($vimeo))
        {
            foreach ($vimeo[0] as $key => $videoId):
                $pattern = '/(##vimeo_'.$key.')/';
                $data['message'] = preg_replace($pattern, '<iframe src="http://player.vimeo.com/video/'.$videoId.'" frameborder="0" width="350px" height="300px"></iframe></div>', $data['message']);
            endforeach;
        }
		*/
		
        return $data;
    }
}
