<?php
/**
 * @version: $Id: jcomments.php 2305 2012-03-16 15:21:29Z Sigrid Suski $
 * @package: SobiPro JComments Application
 * ===================================================
 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET
 * ===================================================
 * @copyright Copyright (C) 2006 - 2012 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license see http://www.gnu.org/licenses/gpl.html GNU/GPL Version 3.
 * You can use, redistribute this file and/or modify it under the terms of the GNU General Public License version 3
 * ===================================================
 * $Date: 2012-03-16 16:21:29 +0100 (Fr, 16 Mrz 2012) $
 * $Revision: 2305 $
 * $Author: Sigrid Suski $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );

SPLoader::loadController( 'config', true );
/**
 * @author Radek Suski
 * @version 1.0
 * @created 22-Jun-2010 15:55:21
 */
class SPJComment extends SPConfigAdmCtrl
{
	/**
	 * @var string
	 */
	protected $_type = 'jcomments';
	/**
	 * @var string
	 */
	protected $_defTask = 'screen';

	public function execute()
	{
		$view = $this->getView( 'jcomments' );
		if( SPFs::exists( implode( DS, array( SOBI_PATH, 'opt', 'plugins', 'jcomments', 'description_'.Sobi::Lang(false).'.html' ) ) ) ) {
			$c = SPFs::read( implode( DS, array( SOBI_PATH, 'opt', 'plugins', 'jcomments', 'description_'.Sobi::Lang(false).'.html' ) ) );
		}
		else {
			$c = SPFs::read( implode( DS, array( SOBI_PATH, 'opt', 'plugins', 'jcomments', 'description_en-GB.html' ) ) );
		}
		$view->assign( $c, 'content' );
        $title = 'jComments Integrator';
		$view->assign( $title, 'title' );
		$view->loadConfig( 'extensions.default' );
		$view->setTemplate( 'extensions.default' );
		$view->display();
	}
}
?>