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

/**
 * Mail helper.
 */
abstract class VitabookHelperMail
{  
    /**
    * Method to send a admin notification email
    * @input object message data
    * @return bool true on success, false on fail 
    */
    public static function sendAdminMail($data = 'default')
    {
        if($data == 'default')
            return false;
        
        $app = JFactory::getApplication();
        
        // get JMail object
        $mailer =& JFactory::getMailer();
        
        // set sender from global configuration
        $config =& JFactory::getConfig();
        $mailer->setSender(array($app->getCfg('mailfrom'), $app->getCfg('fromname')));;
        // set subject
        $mailer->setSubject(JText::_('COM_VITABOOK_EMAIL_SUBJECT'));
        // set recipient
        $mailer->addRecipient($app->getCfg('mailfrom'));
        
        // get/set recipients (BCC)
        $mail_group = JComponentHelper::getParams('com_vitabook')->get('admin_mail_group');
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('email');
        $query->from('#__users');
        $query->where('id IN (SELECT user_id FROM #__user_usergroup_map WHERE group_id IN ('.implode(',',$mail_group).'))');
        $db->setQuery((string)$query);
        $recipients = $db->loadResultArray();
        $mailer->addBCC(implode(',', $recipients));
        
        // set mail body
        $mailer->setBody(VitabookHelperMail::getMailBody($data));
        
        // send the e-mail
        $result = $mailer->Send();
    }

    /**
    * Method to generate the email body
    * @input array message data
    * @return string unique hash for this message id 
    */      
    public static function getMailBody($data)
    {     
        $url = JRoute::_('index.php?option=com_vitabook&messageId='.$data['id'], true, -1).'#'.$data['id'];
        $body = JText::_('COM_VITABOOK_EMAIL_BODY_OPENING')."\n\n";
        $body .= JText::_('COM_VITABOOK_EMAIL_BODY_POSTED_BY').": ".$data['name']." (".$data['email']." / ".$data['ip'].")\n\n";
        $body .= JText::_('COM_VITABOOK_EMAIL_BODY_URL').": ".$url."\n\n";
        $body .= JText::_('COM_VITABOOK_EMAIL_BODY_MESSAGE').":\n-----\n".strip_tags($data['message'])."\n-----\n\n";
        
        if(JFactory::getApplication()->getParams()->get('guest_post_state')){
            $body .= JText::_('COM_VITABOOK_EMAIL_BODY_UNPUBLISH').":\n";
            $body .= JRoute::_('index.php?option=com_vitabook&task=message.unpublish&format=raw&messageId='.$data['id'].'&code='.VitabookHelperMail::getMailHash($data['id'],'publish'), true, -1)."\n\n";
        }
        else {
            $body .= JText::_('COM_VITABOOK_EMAIL_BODY_PUBLISH').":\n";
            $body .= JRoute::_('index.php?option=com_vitabook&task=message.publish&format=raw&messageId='.$data['id'].'&code='.VitabookHelperMail::getMailHash($data['id'],'publish'), true, -1)."\n\n";
        }
        
        $body .= JText::_('COM_VITABOOK_EMAIL_BODY_DELETE').":\n";
        $body .= JRoute::_('index.php?option=com_vitabook&task=message.delete&format=raw&messageId='.$data['id'].'&code='.VitabookHelperMail::getMailHash($data['id'],'delete'), true, -1)."\n\n";
        
        return $body;
    }


    /**
    * Method to generate the email hash
    * @input int message id
    * @return string unique hash for this message id 
    */
    public static function getMailHash($messageId,$task)
    {
        if(!empty($messageId) && !empty($task)){
            $secret = JFactory::getConfig()->get('secret');
            return sha1($secret.(int)$messageId.(string)$task);
        }
    }    

    public static function checkMailHash($messageId,$task)
    {
        if(!empty($messageId)){
            $validHash = VitabookHelperMail::getMailHash($messageId,$task);
            
            $mailHash = (string)JRequest::getVar('code');
            
            return ($validHash == $mailHash ? true : false);
        }
    }
    

}
