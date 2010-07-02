<?php
/*
 * Copyright (c) 2004-2005, Kaspars Foigts
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *
 *    * Redistributions in binary form must reproduce the above
 *      copyright notice, this list of conditions and the following
 *      disclaimer in the documentation and/or other materials provided
 *      with the distribution.
 *
 *    * Neither the name of the WALL4PHP nor the names of its
 *      contributors may be used to endorse or promote products derived
 *      from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 * Authors: Kaspars Foigts (wall4php@laacz.lv)
 *
*/

require_once('Wall/Element.php');

class WallElementCool_menu_css extends WallElement {
    
    var $tag = 'cool_menu_css';
    
    var $src = false;
    var $alt = false;
    
    var $css_table_coloring = '';
    var $table_for_layout = '';
    var $width = '50%';
    var $xhtml_table_support = '';
    var $create_table_css = false;

    //Attributes
    var $colnum = 2;
    
    var $tabularize = true;
    
    
    function WallElementCool_menu_css (&$wall, $attributes = Array()){
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();
        if (!$this->getAncestorByClassName('wallelementhead')) {
            trigger_error("tag 'cool_menu_css' must be nested inside 'head' and 'document' tags", E_USER_ERROR);
        }
        
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            $this->colnum = $this->colnum ? $this->colnum : 1;
            $width = (int)(100 / $this->colnum) . '%';
            $this->xhtml_table_support = $this->_wall->getCapa('xhtml_table_support');
            $this->create_table_css = $this->tabularize && $this->xhtml_table_support;
            
            $this->write('<style type="text/css">');
            if ($this->create_table_css) {
                $this->write(' table.coolmenu {width:100%}');
                $this->write(' td.coolmenu {text-align:center;font-size:smaller;width:' . $width . ';vertical-align:top;}');
                $this->write(' img.coolmenu {vertical-align:top;}');
            } else {
                $this->write(' .noneedtomatchanything {font-size:100%}');
            }
            
        }
    }
    
    function doEndTag() {
        parent::doEndTag();
        
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            $this->write('</style>');
        }
    }
    
}

?>
