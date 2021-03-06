<?php
/**
 * @version: $Id: chbxgroup.php 1785 2011-08-08 19:09:21Z Radek Suski $
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
 * $Date: 2011-08-08 21:09:21 +0200 (Mon, 08 Aug 2011) $
 * $Revision: 1785 $
 * $Author: Radek Suski $
 * File location: components/com_sobipro/opt/fields/adm/chbxgroup.php $
 */
defined( 'SOBIPRO' ) || exit( 'Restricted access' );
SPLoader::loadClass( 'opt.fields.chbxgroup' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 27-Nov-2009 17:10:03
 */
class SPField_ChbxGrAdm extends SPField_ChbxGr
{
	/**
	 * @var string
	 */
	protected $cssClass = "inputbox";

	public function save( &$attr )
	{
		$options = array();
		static $lang = null;
		static $defLang = null;
		if( !( $lang ) ) {
			$lang = Sobi::Lang();
			$defLang = Sobi::DefLang();
		}
		$file = SPRequest::file( 'spfieldsopts', 'tmp_name' );
		if( $file ) {
			$options = $this->parseOptsFile( $file, false );
		}
		if( !( count( $options ) ) && count( $attr['options'] ) ) {
			$p = 0;
			$hold = array();
			foreach ( $attr['options'] as $o ) {
				if( is_numeric( $o[ 'id' ] ) ) {
					$o[ 'id' ] = $this->nid.'_'.$o[ 'id' ];
				}
				if( isset( $o[ 'id' ] ) ) {
					$i = 0;
					$oid = $o[ 'id' ];
					while( isset( $hold[ $oid ] ) ) {
						$oid = $o[ 'id' ].'_'.++$i;
					}
					$options[] = array( 'id' => $oid, 'name' => $o[ 'name' ], 'parent' => null, 'position' => ++$p );
					$hold[ $oid ] = $oid;
				}
			}
		}
		if( count( $options ) ) {
			unset( $attr['options'] );
			$optionsArr = array();
			$labelsArr = array();
			$defLabelsArr = array();
			$optsIds = array();
			foreach ( $options as $i => $option ) {
				/* check for doubles */
				foreach ( $options as $pos => $opt ) {
					if( $i == $pos ) {
						continue;
					}
					if( $option[ 'id' ] == $opt[ 'id' ] ) {
						$option[ 'id' ] = $option[ 'id' ].'_'.substr( ( string ) microtime(), 2, 8 ).rand( 1, 100 );
						SPMainFrame::msg( 'FIELD_WARN_DUPLICATE_OPT_ID', SPC::WARN_MSG );
					}
				}
				$optionsArr[] = array( 'fid' => $this->id, 'optValue' => $option[ 'id' ], 'optPos'  => $option[ 'position' ], 'optParent' =>  $option[ 'parent' ] );
				$defLabelsArr[] = array( 'sKey' => $option[ 'id' ], 'sValue' => $option[ 'name' ], 'language' => $defLang, 'oType' => 'field_option', 'fid' => $this->id );
				$labelsArr[] = array( 'sKey' => $option[ 'id' ], 'sValue' => $option[ 'name' ], 'language' => $lang, 'oType' => 'field_option', 'fid' => $this->id );
				$optsIds[] = $option[ 'id' ];
			}
			/* @var SPdb $db */
			$db =& SPFactory::db();

			/* try to delete the existing labels */
			try {
				$db->delete( 'spdb_field_option', array( 'fid' => $this->id ) );
				$db->delete( 'spdb_language', array( 'oType' => 'field_option', 'fid' => $this->id, '!sKey' => $optsIds ) );
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'CANNOT_DELETE_SELECTED_OPTIONS', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
			}
			/* insert new values */
			try {
				$db->insertArray( 'spdb_field_option', $optionsArr );
				$db->insertArray( 'spdb_language', $labelsArr, true );
				if( $defLang != $lang ) {
					$db->insertArray( 'spdb_language', $defLabelsArr, false, true );
				}
			}
			catch ( SPException $x ) {
				Sobi::Error( $this->name(), SPLang::e( 'CANNOT_STORE_FIELD_OPTIONS_DB_ERR', $x->getMessage() ), SPC::ERROR, 500, __LINE__, __FILE__ );
			}
		}
		if( !isset( $attr[ 'params' ] ) ) {
			$attr[ 'params' ] = array();
		}
		$myAttr = $this->getAttr();
		$properties = array();
		if( count( $myAttr ) ) {
			foreach ( $myAttr as $property ) {
				$properties[ $property ] = isset( $attr[ $property ] ) ? ( $attr[ $property ] ) : null;
			}
		}
		$attr[ 'params' ] = $properties;
	}

	public function onFieldEdit( &$view )
	{
		$view->assign( $this->options, 'options' );
	}
}
?>