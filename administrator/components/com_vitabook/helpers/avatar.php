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

//Import filesystem libraries and component helper.
jimport('joomla.filesystem.file');
jimport('joomla.application.component.helper');

/**
 * Avatar helper.
 */
abstract class VitabookHelperAvatar
{
    /**
    * Method to get the avatar link for a message
    *   executes database query if necessary for avatar source, then proxies to getAvatarUrl method
    * @return link to avatar
    */
    public static function messageAvatar($message) {
        // Get avatar parameters
        $source = JComponentHelper::getParams('com_vitabook')->get('vbAvatar');
        // Get info from database
        if(!empty($message->jid))
        {
            switch ($source)
            {
                case 2:
                    //-- Community Builder's avatar system is used
                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query->select('avatar');
                    $query->from('#__comprofiler');
                    $query->where('user_id = '.$message->jid);
                    $db->setQuery((string)$query);
                    $message->avatar = $db->loadResult();
                    break;
                case 3:
                    //-- Kunena's avatar system is used
                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query->select('avatar');
                    $query->from('#__kunena_users');
                    $query->where('userid = '.$message->jid);
                    $db->setQuery((string)$query);
                    $message->avatar = $db->loadResult();
                    break;
                default:
            }
        }
        // return img url
        return VitabookHelperAvatar::getAvatarUrl($message);
    }

    /**
     * Method to set the avatar join query (if necessary for an avatar source)
     * @param   object $query   JDatabase query object
     * @return  void
     */
    public static function setAvatarQuery(&$query)
    {
        $source = JComponentHelper::getParams('com_vitabook')->get('vbAvatar');

        if($source == 2)
        {
            $query->select('cb.avatar');
            $query->leftjoin('#__comprofiler AS cb ON m.jid = cb.user_id');
        }
        elseif($source == 3)
        {
            $query->select('k.avatar');
            $query->leftjoin('#__kunena_users AS k ON m.jid = k.userid');
        }
    }


   /**
    * Method to get the avatar src url for a message
    * @param    object  $message    VitaBook message object
    * @return   string  avatar img src url
    */
    public static function getAvatarUrl($message)
    {
        $source = JComponentHelper::getParams('com_vitabook')->get('vbAvatar');
        
        // Default avatar
        $defaultAvatar = JComponentHelper::getParams('com_vitabook')->get('defaultAvatar', 'default1.png');      

        // no avatar
        if(empty($source))
            return false;

        // return default avatar for guests unless gravatar is enabled
        if(($message->jid == 0) && ($source != 4)){
            return JURI::root().'media/com_vitabook/images/avatars/default/'.$defaultAvatar;
        }

        // determine links for supported avatar systems
        switch ($source)
        {
            case 1:
                // Vitabook built-in avatar system
                $path = JPATH_SITE.'/media/com_vitabook/images/avatars/'.$message->jid.'.png';
                if(JFile::exists($path)) {
                    //-- ?filemtime() is workaround for browser-cache
                    return JURI::root().'media/com_vitabook/images/avatars/'.$message->jid.'.png?'.filemtime($path);
                }
                break;
            case 2:
                // Community Builder's avatar system
                if(!empty($message->avatar)){
                    return JURI::root().'images/comprofiler/'.$message->avatar;
                }
                break;
            case 3:
                // Kunena's avatar system
                if(!empty($message->avatar)){
                    return JURI::root().'media/kunena/avatars/'.$message->avatar;
                }
                break;
            case 4:
                // gravatar avatar service
                return 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($message->email))).'?d='.urlencode( JURI::root().'media/com_vitabook/images/avatars/default/'.$defaultAvatar );
                break;
            default:
                return false;
        }
        // return default avatar when no avatar was found
        return JURI::root().'media/com_vitabook/images/avatars/default/'.$defaultAvatar;
    }

   /**
    * Method to check if the external avatar source is available
    * @source   avatar source
    * @return   boolean
    */    
    public static function checkAvatarSystem($source)
    {
        switch ($source)
        {
            case 2:
                if(!JComponentHelper::isEnabled('com_comprofiler', true)) {
                    return false;
                }
                break;
            case 3:
                if(!JComponentHelper::isEnabled('com_kunena', true)) {
                    return false;
                }
                break;
        }
        return true;
    }
}
