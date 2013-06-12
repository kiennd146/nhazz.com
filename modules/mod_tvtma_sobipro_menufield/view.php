<?php
/**
 * @version: $Id: view.php 1759 2011-08-02 14:54:17Z Radek Suski $
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
defined('_JEXEC') || die( 'Direct Access to this location is not allowed.' );
SPLoader::loadView( 'section' );

/**
 * @author Radek Suski
 * @version 1.0
 * @created 04-Apr-2011 10:13:08
 */
class SPCatModView extends SPSectionView
{
	public function display()
	{  
		$data = array();
                $sid=$this->get('sid');
                $data['sid'] = $sid;
                $fid=$this->get( 'fid' );
                $data['viewall'] = JRoute::_('index.php?option=com_sobipro&sid='.$sid);
                $data['nid']= $this->getnid($fid);
		$visitor = $this->get( 'visitor' );
		
		$current = $this->get( 'section' );
		$entries = $this->get( 'entries' );
		$debug = $this->get( 'debug' );
		
                $fields = $this->get( 'fields' );
                require_once dirname( __FILE__ ).'/helper.php';
              
		if( count( $entries ) ) {

			foreach ( $entries as $eid ) {
			    $en = $this->category($eid);
                //$con = SPCatMod::CatNumber($eid);
                // kiennd optimize
                $con = SPCatMod::NumberCategoryChildren($eid);
                
                $pid = $this->getPid($en['id']);
                $grandid = $this->getPid($pid);
                $en['entry'] = $con;
                $title=JRoute::_('index.php?option=com_sobipro&sid='.$en['id'].'&Itemid=225');
                $data[ 'categories' ][] = array(
                    '_complex' => 1,
                    '_attributes' => array( 'id' => $en[ 'id' ], 'alt'=>$en['entry'], 'title'=>$pid, 'grand'=>$grandid,'name'=>$title),
                    '_data' => $en
                );

			/*
			// kiennd optimize
			$category = SPFactory::Category( $eid );
			//$category = $this->category( $eid );
			$count_children = SPCatMod::NumberCategoryChildren($eid);

			if ($count_children == 0) continue;
			$en = array();
				$en[ 'id' ] = $category->get( 'id' ); 
            //$en[ 'nid' ] = $category->get( 'nid' );
            $en[ 'name' ] = array(
                '_complex' => 1,
                '_data' => $category->get( 'name' ),
                '_attributes' => array( 'lang' => Sobi::Lang( false ) )
            );
            
            
                                
                                
                                $pid = $this->getPid($en['id']);
                                $grandid = $this->getPid($pid);
                                $title=JRoute::_('index.php?option=com_sobipro&sid='.$en['id'].'&Itemid=225');
                                
                                $en['entry'] = $count_children;
				$data[ 'categories' ][] = array(
					'_complex' => 1,
					'_attributes' => array( 'id' => $en[ 'id' ], 'alt'=>$en['entry'], 'title'=>$pid, 'grand'=>$grandid,'name'=>$title, ),
					//'_attributes' => array( 'id' => $en[ 'id' ], 'title'=>$pid, 'grand'=>$grandid,'name'=>$title, ),
					'_data' => $en
				);
                  */              
			}
			 
			// kiennd optimize
			
			unset($entries);
		}
		
                require_once dirname( __FILE__ ).'/spelements.php';
                if( count( $fields ) ) {
			foreach ( $fields as $fiid ) {
                                $name = JElementSPElements::getNameOfField($fiid->fid,'field_option',$fiid->optValue);
                                $title=$fiid->optValue;
                                $str =JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='.$title.'&'.$this->getnid($fid).'&sid='.$sid.'&spsearchphrase=exact');
				$data[ 'fieloptions' ][] = array(
					'_complex' => 1,
					'_attributes' => array( 'alt'=>$str, 'title'=>$title ),
					'_data' => $name
				);
                                
			}
			unset($fields);
		}
		$data[ 'visitor' ] = $this->visitorArray( $visitor );
		
		$this->_attr = $data;
		$this->_attr[ 'template_path' ] = Sobi::FixPath( str_replace( SOBI_ROOT, Sobi::Cfg( 'live_site' ), dirname( $this->_template.'.xsl' ) ) );
		$parserClass = SPLoader::loadClass( 'mlo.template_xslt' );
		$parser = new $parserClass();
		$parser->setData( $this->_attr ); 
		$parser->setType( 'CatModule' ); 
		$parser->setTemplate( $this->_template );
		$parser->setProxy( $this );
		if( $debug ) {
			echo $parser->XML();
		}
		else {
			echo $parser->display( 'html' );
		}
	}
        /**
         *Lấy da ranh mục các danh mục con của danh mục $id
         * @param type $id
         * @return array 
         */
        public function getCat($id)
        {
            $en = $this->category($id);
            return $en;            
        }
        public function getPid($id)
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('pid');
            $query->from('#__sobipro_relations');
            $query->where("id='". $id . "'");
            $db->setQuery((string)$query);
            $pid = $db->loadResult();
            return $pid;
        }
        private function getnid($fid)
        {
             $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from('#__sobipro_field');
            $query->where("fid='".$fid."'");
            $db->setQuery((string)$query);
            $field = $db->loadResult();
            return $field;
        }
        
}
?>
