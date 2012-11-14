<?php
/**
 * @version: $Id: init.php 1603 2011-07-06 16:10:04Z Sigrid Suski $
 * @package: SobiPro JComments Application
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2011 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/gpl.html GNU/GPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU General Public License version 3
 * ===================================================
 * $Date: 2011-07-06 18:10:04 +0200 (Mi, 06 Jul 2011) $
 * $Revision: 1603 $
 * $Author: Sigrid Suski $
 */
 
defined( 'SOBIPRO' ) || exit( 'Restricted access' );

/**
 * @author Radek Suski
 * @version 1.0
 */
class SPJComments extends SPPlugin
{
	private static $methods = array( 'EntryViewDetails', 'ListEntry' );

	private $enabled = false;

	private $class = array( SOBI_ROOT, 'components', 'com_jcomments', 'jcomments.php' );

	public function __construct()
	{
		$this->class = implode( DS, $this->class );
		if( SPFs::exists( $this->class )  ) {
			require_once( $this->class );			
			$this->enabled = true;
		}
		else {
			Sobi::Error( 'SPJComments', 'JComments component is not installed', SPC::WARNING );
		}
	}

	/* (non-PHPdoc)
	 * @see Site/lib/plugins/SPPlugin#provide($action)
	 */
	public function provide( $action )
	{
		if( $this->enabled ) {
			return in_array( $action, self::$methods );
		}
		return false;
	}

	public function EntryViewDetails( &$data )
	{
		if( $this->enabled ) {
			SPLang::load( 'SpApp.jcomments' );
			$data[ 'jcomments' ] = JComments::showComments(
					$data[ 'entry' ][ '_attributes' ][ 'id' ],
					'com_sobipro',
					$data[ 'entry' ][ '_attributes' ][ 'nid' ]
			);
		}
	}

	public function ListEntry( &$data )
	{
		if( $this->enabled ) {
			SPLang::load( 'SpApp.jcomments' );
			$data[ 'jcomments' ] = Sobi::Txt( 'JC.COM_COUNT', JComments::getCommentsCount( $data[ 'id' ], 'com_sobipro' ) );
		}
	}

	public static function admMenu( &$links )
	{
		$links[ 'jComments' ] = 'jcomments';
	}
}
?>