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
    
    static function getRoot() {
        echo "\t<option value='0'>--</option>\n";
    }

}
