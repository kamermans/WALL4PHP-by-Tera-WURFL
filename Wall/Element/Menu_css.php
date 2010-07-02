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

class WallElementMenu_css extends WallElement {
    
    var $tag = 'menu_css';
    
    var $autonumber = false;
    var $colorize = false;
    
    var $bgcolor1 = false;
    var $bgcolor2 = false;
    var $table_and_css_background = false;
    
    var $wml_menu_with_select = false;

    function WallElementMenu_css (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();

        if (!$this->getAncestorByClassName('wallelementdocument') ||
            !$this->getAncestorByClassName('wallelementhead')) {
            trigger_error("tag 'menu_css' must be nested inside 'head' and 'document' tags", E_USER_ERROR);
        }

        $this->css_table_coloring = $this->_wall->getCapa('xhtml_supports_css_cell_table_coloring');
        $this->table_for_layout = $this->_wall->getCapa('xhtml_supports_table_for_layout');
        $this->bgcolor1 = $this->bgcolor1 ? $this->bgcolor1 : $this->_wall->getCapa('xhtml_readable_background_color1');
        $this->bgcolor2 = $this->bgcolor2 ? $this->bgcolor2 : $this->_wall->getCapa('xhtml_readable_background_color2');
        
        $this->_wall->menu_css_tag = true;
        
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false &&
            $this->css_table_coloring &&
            $this->table_for_layout) {
                
#            if (!$this->bgcolor1) $this->bgcolor1 = '#99CCFF';
#            if (!$this->bgcolor2) $this->bgcolor2 = '#FFFFFF';
            
            $this->writeln('<style type="text/css">');
            $this->writeln('.bgcolor1 { background-color: ' . $this->bgcolor1 . '; }');
            $this->writeln('.bgcolor2 { background-color: ' . $this->bgcolor2 . '; }');
            $this->writeln('</style>');

        }
        
    }
    
}

?>