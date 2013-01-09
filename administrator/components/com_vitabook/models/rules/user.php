<?php
/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */
 
defined('JPATH_PLATFORM') or die;
 
/**
 * Form Rule class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormRuleUser extends JFormRule
{
    /**
     * The regular expression to use in testing a form field value.
     *
     * @var    string 
     * @since  11.1
     */
    protected $regex = '^[^<|>|"|\'|%|;|(|)|&]{2,25}$'; // Disallows < > " ' % ; ( ) and & in a username. Username also has to be within 2 to 25 characters
    
}
