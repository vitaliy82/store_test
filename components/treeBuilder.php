<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 * Description of treeBuuider
 *
 * @author vit
 */
class treeBuilder {
    
    static $activeRecord = false;
    
    static function buildTree(Array $data, $parent = 0) {
        $tree = array();
        foreach ($data as $d) {
            if(self::$activeRecord){
                $d = $d->attributes;
            }
            if ($d['parent_id'] == $parent) {
                $children = self::buildTree($data, $d['id']);
                // set a trivial key
                if (!empty($children)) {
                    $d['_children'] = $children;
                }
                $tree[] = $d;
            }
        }
        return $tree;
    }

    static function printTree($current, $tree, $r = 0, $p = null) {
        foreach ($tree as $i => $t) {
            $dash = ($t['parent_id'] == 0) ? '' : str_repeat('--', $r) .' ';
            $sel =  ($t['id']== $current) ? "selected" : '';
            echo sprintf("\t<option value='%d' " . $sel . ">%s%s</option>\n", $t['id'], $dash, $t['name']);
            if ($t['parent_id'] == $p) {
                // reset $r
                $r = 0;
            }
            if (isset($t['_children'])) {
                self::printTree($current, $t['_children'], ++$r, $t['parent_id']);
            }
        }
    }

    static function getTreeList($tree, $r = 0, $p = null, &$out = '') {
        foreach ($tree as $i => $t) {
            $out .= '<li class="dd-item" data-id="'.$t['id'].
                '"><div class="dd-handle">'.$t['name'].'</div>';
            if ($t['parent_id'] == $p) {
                // reset $r
                $r = 0;
            }
            if (isset($t['_children'])) {
                $out .= '<ol class="dd-list">';
                self::getTreeList( $t['_children'], ++$r, $t['parent_id'], $out);
                $out .= '</ol>';
            }
            $out .= '</li>';
        }
        return $out;
    }
    
    static function getTreeListFull($tree, $dom_id){
        return '<div class="dd" id="'.$dom_id.'"><ol class="dd-list">'.
        self::getTreeList($tree).'</ol></div>';       
    }

    static function getTreeListLink($link = '', $tree, $r = 0, $p = null, &$out = '') {
        foreach ($tree as $i => $t) {
            $out .= '<li><a href='.$link.$t['id'].'>'.$t['name'].'</a>';
            if ($t['parent_id'] == $p) {
                $r = 0;
            }
            if (isset($t['_children'])) {
                $out .= '<ul>';
                self::getTreeListLink($link, $t['_children'], ++$r, $t['parent_id'], $out);
                $out .= '</ul>';
            }
            $out .= '</li>';
        }
        return $out;
    }
    
    static function getTreeListLinkFull($tree, $link = ''){
        return '<ul>'.self::getTreeListLink($link, $tree).'</ul>';       
    }
    
    static function getRoot() {
        echo "\t<option value='0'>--</option>\n";
    }
    
}
