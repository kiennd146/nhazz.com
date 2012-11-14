<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * TvtMA1080 Model
 */
class TvtMA1080ModelTvtMASobiproTags extends JModelItem
{
        function getImage($entry,$field,$type) {
            $src = SPConfig::unserialize( $entry->getField($field )->getRaw() );
            $image = "<img src='$src[$type]' class='imageTag' />";
            return $image;
            
        }
        
        /**
         * Get Information of user use userID
         * @param int $user_id 
         * return object
         */
        function getUser($user_id, $atributes = null)
        {
            //$user = SPFactory::user()->getInstance($user_id);
            $user = CFactory::getUser($user_id);
            if(isset($atributes)) :
                return $user->get($atributes);
            else :
                return $user;
            endif;
            
        }
        
        /**
         * Get avatar of user
         * @param type $user_id 
         */
        function getAvatar($user_id)
        {
            $user = CFactory::getUser($user_id);
            $avatar = $user->getAvatar();
            $image = '<img class="tagAvatar" src="'. $avatar .'" alt="" border="0"/>';
            return $image;
        }
}
