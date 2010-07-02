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

class WallElementAlternate_img extends WallElement {
    
    var $tag = 'alternate_img';
    
    var $test = false;
    var $nopicture = false;
    
    var $src = false;
    var $opwv_icon = false;
    var $eu_imode_icon = false;
    var $ja_imode_icon = false;
    
    var $render_as = false; //valid values: "nothing","icon","image"

    var $inside_cool_menu = false;
    var $coolmenu = false;
    var $cool_menu_perform_tabularize = false;
    
    var $img = false;

    var $xhtml_br = false;
    var $css_hook = false;
    var $chtml_br = false;
    var $opwv_icon_space = false;
    
    var $imode_eu_icon = false;
    var $imode_ja_icon = false;
    var $opwv_icon_localsrc = false;
    
    function WallElementAlternate_Img (&$wall, $attributes = Array()){
        $this->WallElement($wall, $attributes);
        
        $c = 0;
        if ($this->src) $c++;
        if ($this->eu_imode_icon) $c++;
        if ($this->ja_imode_icon) $c++;
        if ($this->opwv_icon) $c++;
        if ($this->nopicture) $c++;

        if ($c == 0) {
            trigger_error("tag 'alternate_img' must use one of the following attributes:\nsrc,opwv_icon,eu_imode_icon,ja_imode_ico or nopicture", E_USER_ERROR);
        } else if ($c > 1) {
            trigger_error("tag 'alternate_img' must use one and only one of the following attributes:\nsrc,opwv_icon,eu_imode_icon,ja_imode_ico or nopicture", E_USER_ERROR);
        }
        
        $this->img =& $this->getAncestorByClassName('wallelementimg');
        
        if (!$this->img) {
            trigger_error("tag 'alternate_img' must be nested inside an 'img' tag", E_USER_ERROR);
        }
        
        if ($this->test && $this->nopicture) {
            $this->img->render_as = 'nothing';
        }
        
        if ($this->test && $this->src) {
            $this->img->src = $this->src;
        }
        
    }
    
    function doStartTag() {
        parent::doStartTag();
        
        if (strpos($this->preferred_markup, 'chtml') !== false) {
            $this->region = $this->_wall->getCapa('imode_region');
            if ($this->test && $this->region == 'ja' && $this->ja_imode_icon) {
                $this->img->render_as = 'icon';
                $this->img->imode_ja_icon = $this->ja_imode_icon;
            } else if ($this->test && $this->region == 'eu' && $this->eu_imode_icon) {
                $this->img->render_as = 'icon';
                $this->img->imode_eu_icon = $this->eu_imode_icon;
            }
        } else if ((strpos($this->preferred_markup, 'xhtmlmp') !== false) || (strpos($this->preferred_markup, 'wml') !== false)) {
            if ($this->test && $this->opwv_icon) {
                $this->img->render_as = 'icon';
                $this->img->opwv_icon_localsrc = $this->opwv_icon;
            }
            
        }
    }
    
}

?>
