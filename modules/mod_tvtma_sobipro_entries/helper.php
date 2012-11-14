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
defined('_JEXEC') || die('Direct Access to this location is not allowed.');
SPLoader::loadController('section');

/**
 * @author Radek Suski
 * @version 1.0
 * @created 04-Apr-2011 10:13:08
 */
class SPEntriesMod extends SPSectionCtrl {

    public static function ListEntries($params) {
        static $instance = null;
        if (!( $instance )) {
            $instance = new self();
        }
        $instance->display($params);
    }

    public function display($params) {

        $template = SOBI_PATH . '/usr/templates/front/modules/entries/' . $params->get('tplFile');
        if ($params->get('tplFile') && file_exists($template)) {
            $css = '';
            $css = explode(" ", $css);
            $document = & JFactory::getDocument();
            $send = false;
            if (count($css)) {
                foreach ($css as $file) {
                    if (trim($file)) {
                        $file = explode('.', trim($file));
                        array_pop($file);
                        $file = implode('.', $file);
                        SPFactory::header()->addCssFile("root.{$file}", false, null, true);
                    }
                    $head = SPFactory::header()->getData('cssFiles');
                    if (count($head)) {
                        foreach ($head as $html) {
                            $document->addCustomTag($html);
                        }
                        $send = true;
                    }
                }
            }
            //$params->set('jsFlies','/media/sobipro/megamenu.js /media/sobipro/jquerymin.js');
            $jsFiles = '';
            $jsFiles = explode(",", $jsFiles);
            if (count($jsFiles)) {
                foreach ($jsFiles as $file) {
                    if (trim($file)) {
                        $file = explode('.', trim($file));
                        array_pop($file);
                        $file = implode('.', $file);
                        SPFactory::header()->addJsFile("root.{$file}", false, null, true);
                    }
                    $head = SPFactory::header()->getData('jsFiles');
                    if (count($head)) {
                        $document = & JFactory::getDocument();
                        foreach ($head as $html) {
                            $document->addCustomTag($html);
                        }
                        $send = true;
                    }
                }
                if ($send) {
                    SPFactory::header()->reset();
                }
            }
            $result = array();
            $fid = 0;
            if ($params->get('tplFile') == 'project.xsl') {
                $userids = $params->get('userid');
                $result = $this->Action3($result, $params, $userids);
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('fid');
                $query->from('#__sobipro_field');
                $query->where("nid='field_d_n'");
                $db->setQuery((string) $query);
                $fid = $db->loadResult();
            } else {
                // Home page
                $result = $this->entries($params);
            }
            $creatby = JText::_('CREAT_BY');
            //$cats = $this->GotMenu( $params );
            $pid = $params->get('sid');
            $this->setModel('section');
            $this->_model->init($pid);
            $view = new SPEntriesModView();
            $view->assign($this->_model, 'section');
            $view->setTemplate('front/modules/entries/' . preg_replace('/\.xsl$/', null, $params->get('tplFile')));
            $view->assign(SPFactory::user()->getCurrent(), 'visitor');
            $view->assign($result, 'entries');
            //$view->assign( $cats, 'cats' );
            if ($fid != 0) {
                $view->assign($fid, 'fid');
            }
            $view->assign($creatby, 'creatby');
            $view->assign($params->get('xmlDeb'), 'debug');
            $view->assign($userids, 'userid');
            $view->assign($params->get('spOrder'), 'order');
            $view->assign($params->get('filter'), 'filter');
            $view->assign($params->get('limitChaDesc'), 'limit');
            $view->assign($params->get('entriesLimit'), 'entriesLimit');

            $view->display();
            unset($view);
        } else {
            Sobi::Error('EntriesMod', SPLang::e('Template file %s is missing', str_replace(SOBI_ROOT . DS, null, $template)), SPC::WARNING, 0);
        }
    }

    /**
     * @return array
     */
    private function entries($params) {
        if ($params->get('fieldOrder')) {
            $eOrder = $params->get('fieldOrder');
        } else {
            $eOrder = $params->get('spOrder');
        }
        $entriesRecursive = true;
        /* var SPDb $db */
        $db = & SPFactory::db();
        $entries = array();
        $eDir = 'asc';
        $oPrefix = null;
        $conditions = array();

        /* get the ordering and the direction */
        if (strstr($eOrder, '.')) {
            $eOrder = explode('.', $eOrder);
            $eDir = $eOrder[1];
            $eOrder = $eOrder[0];
        }
        $pid = $params->get('sid');
        $this->setModel('section');
        $this->_model->init($pid);

        if ($entriesRecursive) {
            $sidT = (int) $params->get('sidT');
            $pidT = $params->get('pidT');
            $taskT = $params->get('taskT');
            if ($sidT == 0 || ($pid == $sidT && $pid = $pidT) || $pid == $sidT || $taskT === 'entry.add') {
                $pids = $this->_model->getChilds('category', true);
            } else {
                if (isset($pidT)) { // Entry
                    $entry = SPFactory::Entry($sidT);
                    // Get Category of this entry
                    $categories = $entry->get('categories');
                    // Get all entry of all category
                    foreach ($categories as $category) {
                        $pids[$category['pid']] = $category['pid'];
                    }
                } elseif (!isset($pid)) {
                    $pids = $this->_model->getChilds('category', true);
                } else {
                    $categories = SPFactory::Category($sidT);
                    $pids = $categories->getChilds('category', true);
                    if (count($pids) == 0) {
                        $pids[$sidT] = $sidT;
                    }
                }
            }
            if (is_array($pids)) {
                $pids = array_keys($pids);
            }
            $conditions['sprl.pid'] = $pids;
        } else {
            $conditions['sprl.pid'] = $pid;
        }

        if ($pid == -1) {
            unset($conditions['sprl.pid']);
        }

        /* sort by field */
        if (strstr($eOrder, 'field_')) {
            static $fields = array();
            $specificMethod = false;
            $field = isset($fields[$pid]) ? $fields[$pid] : null;
            if (!$field) {
                try {
                    $db->select('fieldType', 'spdb_field', array('nid' => $eOrder));
                    $fType = $db->loadResult();
                } catch (SPException $x) {
                    Sobi::Error($this->name(), SPLang::e('CANNOT_DETERMINE_FIELD_TYPE', $x->getMessage()), SPC::WARNING, 0, __LINE__, __FILE__);
                }
                if ($fType) {
                    $field = SPLoader::loadClass('opt.fields.' . $fType);
                }
                $fields[$pid] = $field;
            }
            if ($field && method_exists($field, 'sortBy')) {
                $table = null;
                $oPrefix = null;
                $specificMethod = call_user_func_array(array($field, 'sortBy'), array(&$table, &$conditions, &$oPrefix, &$eOrder, &$eDir));
            }
            if (!$specificMethod) {
                $table = $db->join(
                        array(
                            array('table' => 'spdb_field', 'as' => 'fdef', 'key' => 'fid'),
                            array('table' => 'spdb_field_data', 'as' => 'fdata', 'key' => 'fid'),
                            array('table' => 'spdb_object', 'as' => 'spo', 'key' => array('fdata.sid', 'spo.id')),
                            array('table' => 'spdb_relations', 'as' => 'sprl', 'key' => array('fdata.sid', 'sprl.id')),
                        )
                );
                $oPrefix = 'spo.';
                $conditions['spo.oType'] = 'entry';
                $conditions['fdef.nid'] = $eOrder;
                $eOrder = 'baseData.' . $eDir;
            }
        } else {
            $table = $db->join(array(
                array('table' => 'spdb_relations', 'as' => 'sprl', 'key' => 'id'),
                array('table' => 'spdb_object', 'as' => 'spo', 'key' => 'id')
                    ));
            $conditions['spo.oType'] = 'entry';
            $eOrder = $eOrder . '.' . $eDir;
            $oPrefix = 'spo.';
        }

        /* check user permissions for the visibility */
        if (Sobi::My('id')) {
            $this->userPermissionsQuery($conditions, $oPrefix);
        } else {
            $conditions = array_merge($conditions, array($oPrefix . 'state' => '1', '@VALID' => $db->valid($oPrefix . 'validUntil', $oPrefix . 'validSince')));
        }
        $conditions['sprl.copy'] = '0';
        try {
            $db->select($oPrefix . 'id', $table, $conditions, $eOrder, $params->get('entriesLimit'), 0, true);
            $results = $db->loadResultArray();
        } catch (SPException $x) {
            Sobi::Error($this->name(), SPLang::e('DB_REPORTS_ERR', $x->getMessage()), SPC::WARNING, 0, __LINE__, __FILE__);
        }
        if (count($results)) {
            foreach ($results as $i => $sid) {
                $entries[$i] = $sid;
            }
        }
        return $entries;
    }

    /**
     * Lấy ra toàn bộ các entry từ các section
     * Sử dụng code của sobipro
     * @param type $params 
     */
    private function filterbyproject($params, $fid, $userids) {
        $limit = $params->get('entriesLimit');
        $user_array = $this->array_random(explode(',', $userids), $limit);
        $pro_arr = array();
        foreach ($user_array as $user) {
            $pro_arr[] = $this->getrandproject($user);
        }
        $pro_str = implode(',', $pro_arr);
	//var_dump($pro_str);
        $entries = array();
        foreach ($user_array as $user) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('sid');
            $query->from('#__sobipro_field_data');
            $query->where("fid = '" . $fid . "' AND baseData IN(" . $pro_str . ") AND createdBy ='" . $user . "'");
            $db->setQuery((string) $query);
            $entries[] = $this->array_random($db->loadResultArray());
        }
        return $entries;
    }

    private function Action3($result, $params, $userids) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('fid');
        $query->from('#__sobipro_field');
        $query->where("nid='field_d_n'");
        $db->setQuery((string) $query);
        $fid = $db->loadResult();
        $listSection = $this->filterbyproject($params, $fid, $userids);
        return $listSection;
    }

    private function GotMenu($params) {
        // Lấy ra id của section từ Params
        $pid = $params->get('sid');
        $this->setModel('section');
        $this->_model->init($pid);
        // Lấy danh sách danh mục con của section
        $pids = $this->_model->getChilds('category', true);
        if (is_array($pids)) {
            $pids = array_keys($pids);
        }
        return $pids;
    }

    public static function CatNumber($cat_id) {
        $view = new SPEntriesModView();
        // Lấy ra danh sách các danh mục con
        $listCat = $view->getCat($cat_id);
        $result = array(0);
        foreach ($listCat['subcategories'] as $value) {
            $result[] = $value['_attributes']['id'];
        }
        // Chuyển mảng danh mục con thành chuỗi
        $listString = implode(",", $result);
        $db = & SPFactory::db();
        $sql = "SELECT COUNT(*) FROM #__sobipro_relations WHERE pid IN (" . $listString . ") AND oType ='entry'";
        // Thực hiện truy vấn
        $db->setQuery($sql);
        $query_result = $db->loadResult();
        return $query_result;
    }

    private function array_random($arr, $num = 1) {
        shuffle($arr);
        $r = array();
        for ($i = 0; $i < $num; $i++) {
            $r[] = $arr[$i];
        }
        return $num == 1 ? $r[0] : $r;
    }

    public function getrandproject($owner) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__tvtproject');
        $query->where("owner ='" . $owner . "'");
        $db->setQuery((string) $query);
        $pro_array = $this->array_random($db->loadResultArray());
        if ($this->checkexistentry($pro_array)) {
            return $pro_array;
        } else {
            return $this->getrandproject($owner);
        }
    }

    private function checkexistentry($pro_id) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__sobipro_field_data');
        $query->where("baseData='" . $pro_id . "'");
        $db->setQuery((string) $query);
        if ($db->loadResult()) {
            return true;
        } else {
            return false;
        }
    }

}

?>
