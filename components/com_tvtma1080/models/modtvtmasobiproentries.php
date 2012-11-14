<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * TvtMA1080 Model
 */
class TvtMA1080ModelModTVTMASobiproEntries extends JModelItem
{
    public function GotMenu($pid, $state)
    {
	    $section = SPFactory::Model( 'section' );
            $section->init( $pid );
            $pids = $section->getChilds('category', $state);
	    if( is_array( $pids ) ) {
		    $pids = array_keys( $pids );
	    }
	    return $pids;
    }
}
