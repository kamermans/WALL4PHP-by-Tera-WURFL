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

class WallElementImg extends WallElement {
    
    var $tag = 'img';
    
    var $src = false;
    var $alt = false;
    
    var $render_as = false; //valid values: "nothing","icon","image"

    var $inside_cool_menu = false;
    var $coolmenu = false;
    var $cool_menu_perform_tabularize = false;

    var $xhtml_br = false;
    var $css_hook = false;
    var $chtml_br = false;
    var $opwv_icon_space = false;
    
    var $height = false;
    var $width = false;
    
    var $imode_eu_icon = false;
    var $imode_ja_icon = false;
    var $opwv_icon_localsrc = false;
    
    function WallElementImg (&$wall, $attributes = Array()){
        $this->WallElement($wall, $attributes);
        
        $this->render_as = 'image';

        $this->xhtml_br = false;
        $this->css_hook = false;
        $this->chtml_br = false;
        $this->imode_eu_icon = false;
        $this->imode_ja_icon = false;
        $this->opwv_icon_localsrc = false;
        $this->opwv_icon_space = false;
        
        $this->coolmenu =& $this->getAncestorByClassName('wallelementcoolmenu');
        if ($this->coolmenu) {
            $this->cool_menu_perform_tabularize = $this->coolmenu->peform_tabularization;
            if ($this->cool_menu_perform_tabularize) {
                $this->xhtml_br = '<br />';
                $this->chtml_br = '<br>';
            } else {
                $this->xhtml_br = ' ';
                $this->chtml_br = '&nbsp;';
            }
            $this->css_hook = ' class="coolmenu"';
            $this->opvw_icon_space = ' ';
        }
        
    }
    
    function doStartTag() {
        parent::doStartTag();
        
    }
    
    function doEndTag() {
        parent::doEndTag();
        
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            if ($this->render_as == 'image') {
                $this->write('<img' . $this->css_hook . ' src="' . $this->src . '" ');
                if ($this->width) $this->write('width="' . $this->width . '" ');
                if ($this->height) $this->write('height="' . $this->height . '" ');
                $this->write('alt="' . $this->alt . '" />' . $this->xhtml_br);
            } else if ($this->render_as == 'icon' && $this->_wall->use_xhtml_extensions) {
                $this->write('<img src="" alt="' . $this->alt . '" localsrc="' . $this->opwv_icon_localsrc . '"/>' . $this->xhtml_br);
            }
        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            if ($this->render_as == 'image') {
                $this->write($this->opwv_icon_space . '<img src="' . $this->src . '" ');
                if ($this->width) $this->write('width="' . $this->width . '" ');
                if ($this->height) $this->write('height="' . $this->height . '" ');
                $this->write('alt="' . $this->alt . '" />');
            } else if ($this->render_as == 'icon' && $this->_wall->use_wml_extensions) {
                $this->write($this->opwv_icon_space . '<img src="" alt="' . $this->alt . '" localsrc="' . $this->opwv_icon_localsrc . '"/>');
            }
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {
            if ($this->render_as == 'image') {
                $this->write('<img src="' . $this->src . '" ');
                if ($this->width) $this->write('width="' . $this->width . '" ');
                if ($this->height) $this->write('height="' . $this->height . '" ');
                $this->write('alt="' . $this->alt . '" />' . $this->chtml_br);
            } else if ($this->render_as == 'icon') {
                $region = $this->_wall->getCapa('imode_region');
                if ($region == 'ja') {
                    $this->write($this->imode_ja_icon . $this->chtml_br);
                } else if ($region == 'eu') {
                    $this->write($this->imode_eu_icon . $this->chtml_br);
                }
            }
        }
    }
    
}

?>
