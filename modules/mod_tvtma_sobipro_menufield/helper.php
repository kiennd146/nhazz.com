<?php
/**
 * @version: $Id: helper.php 1759 2011-08-02 14:54:17Z Radek Suski $
 * @package: SobiPro Entries Module Application
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
 * $Date: 2011-08-02 16:54:17 +0200 (Di, 02 Aug 2011) $
 * $Revision: 1759 $
 * $Author: Radek Suski $
 */
defined( '_JEXEC' ) || die( 'Direct Access to this location is not allowed.' );
SPLoader::loadController( 'section' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 04-Apr-2011 10:13:08
 */
class SPCatMod extends SPSectionCtrl
{
	public static function ListEntries( $params )
	{
		static $instance = null;
		if( !( $instance ) ) {
			$instance = new self();
		}
		$instance->display( $params );
	}
        
        /**
         * Đếm số lượng entry con của danh mục
         * @param integer $cat_id
         * @return integer 
         */
        public static function CatNumber($cat_id) 
        {
            $view = new SPCatModView();
            // Lấy ra danh sách các danh mục con
            $listCat = $view->getCat($cat_id);
            $result = array(0);
            foreach ($listCat['subcategories'] as $value) {
                $result[] = $value['_attributes']['id'];
            }
            // Chuyển mảng danh mục con thành chuỗi
            $listString = implode(",", $result);
            $db =& SPFactory::db();
            $sql = "SELECT COUNT(*) FROM #__sobipro_relations WHERE pid IN (".$listString.") AND oType ='entry'";
            // Thực hiện truy vấn
            $db->setQuery($sql);
            $query_result = $db->loadResult();

            return $query_result;
        
        }

	public function display( $params )
	{
		$template = SOBI_PATH.'/usr/templates/front/modules/cat/'.$params->get( 'tplFile' );
		if( $params->get( 'tplFile' ) && file_exists( $template ) ) {
                $entries = $this->categories( $params );
                $fields = $this->takefield( $params );
                $view = new SPCatModView();
                $view->assign( $this->_model, 'section' );
                $view->setTemplate( 'front/modules/cat/'.preg_replace( '/\.xsl$/', null, $params->get( 'tplFile' ) ) );
                $view->assign( SPFactory::user()->getCurrent(), 'visitor' );
                $view->assign( $entries, 'entries' );
                $view->assign( $fields, 'fields' );
                $view->assign( $params->get( 'xmlDeb' ), 'debug' );
                $view->assign( $params->get( 'sid' ), 'sid' );
                $view->assign( $params->get( 'fid' ), 'fid' );
                $view->display();
		}
		else {
			Sobi::Error( 'EntriesMod', SPLang::e( 'Template file %s is missing', str_replace( SOBI_ROOT . DS, null, $template ) ), SPC::WARNING, 0 );
		}
	}
        /**
         * Lấy ra danh sách các danh mục con của section
         * @param array $params
         * @return array 
         */
        private function categories( $params ) {
                // Lấy ra id của section từ Params
		$pid = $params->get( 'sid' );
		$this->setModel( 'section' );
		$this->_model->init( $pid );
                // Lấy danh sách danh mục con của section
		$pids = $this->_model->getChilds( 'category', true );
                if( is_array( $pids ) ) {
                        $pids = array_keys( $pids );
                }
		return $pids;
        }
        private function takefield( $params ) {
                // Lấy ra id của section từ Params
		$fid = $params->get( 'fid' );
		 $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                 $query->select('*');
                $query->from('#__sobipro_field_option');
                $query->where("fid='". $fid . "'");
                $db->setQuery((string)$query);
                $fields = $db->loadObjectList();
            return $fields;
        }
}
?>