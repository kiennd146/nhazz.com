<?php
/**
 * @version: $Id: fieldtype.php 2343 2012-04-06 19:37:22Z Radek Suski $
 * @package: SobiPro Library
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2012 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/lgpl.html GNU/LGPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU Lesser General Public License version 3
 * ===================================================
 * $Date: 2012-04-06 21:37:22 +0200 (Fri, 06 Apr 2012) $
 * $Revision: 2343 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/opt/fields/fieldtype.php $
 */
defined( 'SOBIPRO' ) || ( trigger_error( 'Restricted access ' . __FILE__, E_USER_ERROR ) && exit( 'Restricted access' ) );
SPLoader::loadClass( 'models.fields.interface' );
/**
 * @author Radek Suski
 * @version 1.0
 * @created 09-Sep-2009 12:52:45 PM
 */
class SPFieldType extends SPObject
{
    /**
     * @var SPField
     */
    private $_field = null;
    /**
     * @var array
     */
    protected $_attr = array();
    /**
     * @var string
     */
    protected $_selected = null;
    /**
     * @var string
     */
    protected $dType = 'free_single_simple_data';
    /**
     * @var string
     */
    protected $_rdata = null;


    public function __construct( &$field )
    {
        $this->_field =& $field;
        /* transform params from the basic object to the spec. field properties */
        if ( count( $this->params ) ) {
            foreach ( $this->params as $k => $v ) {
                if ( isset( $this->$k ) ) {
                    $this->$k = $v;
                }
            }
        }
        $this->cssClass = $field->get( 'cssClass' );
    }

    protected function setData( $data )
    {
        $this->_rdata = $data;
        $this->_field->setRawData( $data );
    }

    /**
     * @param string $val
     * @return void
     */
    public function setCSS( $val = 'spField' )
    {
        $this->cssClass = $val;
    }

    /**
     * @param string $val
     * @return void
     */
    public function setSelected( $val )
    {
        $this->_selected = $val;
    }

    /**
     * Proxy pattern
     * @param string $method
     * @param array $args
     */
    public function __call( $method, $args )
    {
        if ( $this->_field && method_exists( $this->_field, $method ) ) {
            return call_user_func_array( array( $this->_field, $method ), $args );
        }
        else {
            throw new SPException( SPLang::e( 'CALL_TO_UNDEFINED_METHOD_S', $method ) );
        }
    }

    protected function rangeSearch( $values )
    {
        $request[ 'from' ] = isset( $this->_selected[ 'from' ] ) ? $this->_selected[ 'from' ] : '';
        $request[ 'to' ] = isset( $this->_selected[ 'to' ] ) ? $this->_selected[ 'to' ] : '';
        $values = str_replace( array( "\n", "\r", "\t" ), null, $values );
        $values = explode( ',', $values );
        $data = array();
        $data2 = array();
        if ( count( $values ) ) {
            foreach ( $values as $k => $v ) {
                $data[ '' ] = Sobi::Txt( 'SH.SEARCH_SELECT_RANGE_FROM', array( 'name' => $this->name ) );
                $data2[ '' ] = Sobi::Txt( 'SH.SEARCH_SELECT_RANGE_TO', array( 'name' => $this->name ) );
                $data[ preg_replace( '/[^\d\.\-]/', null, trim( $v ) ) ] = $v;
                $data2[ preg_replace( '/[^\d\.\-]/', null, trim( $v ) ) ] = $v;
            }
        }
        $from = SPHtml_Input::select( $this->nid . '[from]', $data, $request[ 'from' ], false, array( 'class' => $this->cssClass . ' ' . Sobi::Cfg( 'search.form_list_def_css', 'SPSearchSelect' ), 'size' => '1' ) );
        $to = SPHtml_Input::select( $this->nid . '[to]', $data2, $request[ 'to' ], false, array( 'class' => $this->cssClass . ' ' . Sobi::Cfg( 'search.form_list_def_css', 'SPSearchSelect' ), 'size' => '1' ) );
        return '<div class="SPSearchSelectRangeFrom"><span>' . Sobi::Txt( 'SH.RANGE_FROM' ) . '</span> ' . $from . ' ' . $this->suffix . '</div><div class="SPSearchSelectRangeTo"><span>' . Sobi::Txt( 'SH.RANGE_TO' ) . ' ' . $to . ' ' . $this->suffix . '</span></div>';
    }

    protected function searchForRange( $request, $section )
    {
        $sids = array();
        $request[ 'from' ] = isset( $request[ 'from' ] ) ? $request[ 'from' ] : SPC::NO_VALUE;
        $request[ 'to' ] = isset( $request[ 'to' ] ) ? $request[ 'to' ] : SPC::NO_VALUE;
        try {
            SPFactory::db()->dselect(
                'sid', 'spdb_field_data',
                array( 'fid' => $this->fid, 'copy' => '0', 'enabled' => 1, 'baseData' => $request, 'section' => $section )
            );
            $sids = SPFactory::db()->loadResultArray();
        }
        catch ( SPException $x ) {
            Sobi::Error( $this->name(), SPLang::e( 'CANNOT_SEARCH_DB_ERR', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
        }
        return $sids;
    }

    /**
     * Returns meta description
     */
    public function metaDesc()
    {
        return $this->addToMetaDesc ? $this->data() : null;
    }

    public function cleanCss()
    {
        $css = explode( ' ', $this->cssClass );
        if ( count( $css ) ) {
            $this->cssClass = implode( ' ', array_unique( $css ) );
        }
    }

    /**
     * Returns meta keys
     */
    public function metaKeys()
    {
        return $this->addToMetaKeys ? $this->data() : null;
    }

    /**
     * Proxy pattern
     * @param string $property
     */
    public function __get( $property )
    {
        if ( !( isset( $this->$property ) ) && $this->_field ) {
            return $this->_field->get( $property );
        }
        else {
            return $this->get( $property );
        }
    }

    /**
     * @param $vals
     * @return void
     */
    public function save( &$vals )
    {
        $this->_attr =& $vals;
        if ( !isset( $vals[ 'params' ] ) ) {
            $vals[ 'params' ] = array();
        }
        $attr = $this->getAttr();
        $properties = array();
        if ( count( $attr ) ) {
            foreach ( $attr as $property ) {
                $properties[ $property ] = isset( $vals[ $property ] ) ? ( $vals[ $property ] ) : null;
            }
        }
        $vals[ 'params' ] = $properties;
    }

    protected function getAttr()
    {
        return array();
    }

    public function approve( $sid )
    {
        $db =& SPFactory::db();
        static $lang = null;
        if ( !( $lang ) ) {
            $lang = Sobi::Lang( false );
        }
        try {
            $db->select( 'COUNT( fid )', 'spdb_field_data', array( 'sid' => $sid, 'copy' => '1', 'fid' => $this->fid ) );
            $copy = $db->loadResult();
            if ( $copy ) {
                /**
                 * Fri, Apr 6, 2012
                 * Ok, this is tricky now.
                 * Normally we have such situation:
                 * User is adding an entry and flags are:
                 * approved	| copy	| baseData
                 *    0		|  1	|	Org
                 * When it's just being approved everything works just fine
                 * Problem is when the admin is changing the data then after edit it looks like this
                 * approved	| copy	| baseData
                 *    0		|  1	|	Org         << org user data
                 *    1		|  0	|	Changed     << data changed by the administrator
                 * So in the normal way we'll delete the changed data and approve the old data
                 * Therefore we have to check if the approved data is maybe newer than the non-approved copy
                 */
                $date = $db
                        ->select( 'copy', 'spdb_field_data', array( 'sid' => $sid, 'fid' => $this->fid ), 'updatedTime.desc', 1 )
                        ->loadResult();
                /**
                 * If the copy flag of the newer version is 0 - then delete all non-approved versions
                 * and this is our current version
                 */
                if( $date == 0 ) {
                    $db->delete( 'spdb_field_data', array( 'sid' => $sid, 'copy' => '1', 'fid' => $this->fid ) );
                }
                else {
                    $params = array( 'sid' => $sid, 'copy' => '1', 'fid' => $this->fid );
                    /**
                     * when we have good multilanguage management
                     * we can change it
                     * for the moment if an entry is entered in i.e. de_DE
                     * but the admin approves the entry in en_GB and the multilang mode is enabled
                     * in case it was a new entry - emty data is being displayed
                     */
                    if ( !( Sobi::Cfg( 'entry.approve_all_langs', true ) ) ) {
                        $params[ 'lang' ] = array( $lang, SPC::NO_VALUE );
                    }
                    $el = $db
                            ->select( 'editLimit', 'spdb_field_data', $params )
                            ->loadResult();
                    $cParams = $params;
                    $cParams[ 'copy' ] = 0;
                    $db->delete( 'spdb_field_data', $cParams );
                    $db->update( 'spdb_field_data', array( 'copy' => '0', 'editLimit' => $el ), $params );
                }
            }
        }
        catch ( SPException $x ) {
            Sobi::Error( $this->name(), SPLang::e( 'CANNOT_GET_FIELDS_DATA_DB_ERR', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
        }
    }

    public function changeState( $sid, $state )
    {
        $db =& SPFactory::db();
        try {
            $db->update( 'spdb_field_data', array( 'enabled' => $state ), array( 'sid' => $sid, 'fid' => $this->fid ) );
        } catch ( SPException $x ) {
            Sobi::Error( $this->name(), SPLang::e( 'CANNOT_CHANGE_FIELD_STATE', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
        }
    }

    /**
     * @return bool
     */
    public function searchString()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function searchData()
    {
        return false;
    }

    public function searchSuggest()
    {
        return false;
    }

    /**
     * @param int $sid - entry id
     * @return void
     */
    public function deleteData( $sid )
    {
        static $deleted = array();
        if ( !( isset( $deleted[ $sid ] ) ) ) {
            $db =& SPFactory::db();
            try {
                $db->delete( 'spdb_field_data', array( 'sid' => $sid ) );
            }
            catch ( SPException $x ) {
                Sobi::Error( $this->name(), SPLang::e( 'CANNOT_DELETE_FIELD_DATA', $x->getMessage() ), SPC::WARNING, 0, __LINE__, __FILE__ );
            }
            $deleted[ $sid ] = true;
        }
    }

    protected function checkCopy()
    {
        return (
                in_array( SPRequest::task(), array( 'entry.approve', 'entry.edit', 'entry.save', 'entry.submit' ) ) ||
                        ( Sobi::Can( 'entry.access.unapproved_any' ) ) ||
                        //( $entry->get( 'owner' ) == Sobi::My( 'id' ) && Sobi::Can( 'entry.manage.own' ) ) ||
                        Sobi::Can( 'entry.manage.*' )
        );
    }

    protected function parseOptsFile( $file, $sections = true )
    {
        $file = parse_ini_file( $file, $sections );
        $p = 0;
        $group = null;
        $gid = null;
        $options = array();
        if ( count( $file ) ) {
            foreach ( $file as $key => $value ) {
                if ( is_array( $value ) ) {
                    if ( strstr( $key, '|' ) ) {
                        $group = explode( '|', $key );
                        $gid = SPLang::nid( $group[ 0 ] );
                        $group = $group[ 1 ];
                    }
                    else {
                        $gid = SPLang::nid( $key );
                        $group = $key;
                    }
                    $options[ ] = array( 'id' => $gid, 'name' => $group, 'parent' => null, 'position' => ++$p );
                    if ( count( $value ) ) {
                        foreach ( $value as $k => $v ) {
                            if ( is_numeric( $k ) ) {
                                $k = SPLang::nid( $v );
                            }
                            $options[ ] = array( 'id' => SPLang::nid( $gid . '_' . $k ), 'name' => $v, 'parent' => $gid, 'position' => ++$p );
                        }
                    }
                }
                else {
                    $group = null;
                    $gid = null;
                    $options[ ] = array( 'id' => SPLang::nid( $key ), 'name' => $value, 'parent' => null, 'position' => ++$p );
                }
            }
        }
        return $options;
    }
}
