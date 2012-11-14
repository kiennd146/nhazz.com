<?php
/**
 * @version: $Id: template.php 551 2011-01-11 14:34:26Z Radek Suski $
 * @package: SobiPro Library
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/lgpl.html GNU/LGPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU Lesser General Public License version 3
 * ===================================================
 * $Date: 2011-01-11 15:34:26 +0100 (Tue, 11 Jan 2011) $
 * $Revision: 551 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/usr/templates/default/template.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 28-Oct-2010 10:39:33
 */
abstract class TplFunctions
{
	public static function Txt( $txt )
	{
		return Sobi::Txt( $txt );
	}

	public static function Tooltip( $tooltip, $title = null )
	{
		return SPTooltip::_( $tooltip, $title );
	}

	public static function Cfg(  $key, $def = null, $section = 'general'  )
	{
		return Sobi::Cfg( $key, $def, $section );
	}
        public static function myAvatarFunction( $id )
        {

            $thumb = SPFactory::db()
                    ->select( 'thumb', '#__community_users', array( 'userid' => $id ) )
                    ->loadResult();
            return  strlen( $thumb ) ? '<img src="' . $thumb . '" alt="aaa" longdesc="aaa" border="0" />' : null;
        }
        
        
        public static function myAvatarLink( $id )
        {
            require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');		            return CRoute::_("index.php?option=com_community&view=profile&userid=" . $id);
        }
        public static function CCSelectList( $selected )
        {
        $multi = false; $size = 1; $sel = array();
        if( isset( $selected[ 0 ] ) && $selected[ 0 ] instanceof DOMElement ) {
            foreach ( $selected[ 0 ]->childNodes as $node ) {
            $sel[] = $node->getAttribute( 'id' );
            }
        }
        $result = SPFactory::cache()
            ->getVar( 'cat_chooser_select_list', Sobi::Section() );
        if( !( $result ) ) {
            $result = array();
            self::travelCats( Sobi::Section(), $result, false );
            SPFactory::cache()
            ->addVar( $result, 'cat_chooser_select_list', Sobi::Section() );
        }
        $box = array( '' => Sobi::Txt( 'EN.SELECT_CAT_PRO_PATH' ) );

        foreach ( $result as $id => $name ) {
            $box[ $id ] = $name;
        }

        $params = array(
            'size' => $size,
            'style' => 'width: 360px;',
            'id' => 'SPCatChooserSl',
            'class' => 'required'
        );

        return SPHtml_Input::select( 'entry.parent', $box, $sel, $multi, $params );

        }
        private static function travelCats( $sid, &$result, $margin )
        {
        $msign = '-';
        $category = SPFactory::Model( $margin == false ? 'section' : 'category' );
        $category->init( $sid );
        if( $category->get( 'state' ) ) {
            if( $category->get( 'oType' ) == 'category' ) {
            $result[ $sid ] = $margin.' '.$category->get( 'name' );
            }
            $childs = $category->getChilds( 'category' );
            if( count( $childs ) ) {
            foreach ( $childs as $id => $name ) {
                self::travelCats( $id, $result, $msign.$margin );
            }
            }
        }
        }
        
}
?>