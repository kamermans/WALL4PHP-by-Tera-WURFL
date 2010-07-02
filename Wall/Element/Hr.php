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

class WallElementHr extends WallElement {
    
    var $tag = 'hr';
    
    var $color = false;
    
    function WallElementHr (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();
        
        if ($this->getAncestorByClassName('wallelementblock') ||
            $this->getAncestorByClassName('wallelementmenu') ||
            $this->getAncestorByClassName('wallelementform')
            ) {
            trigger_error("'hr' tag cannot be used inside a block, form or cool_menu tag (breaks XHTML validity and will produce an error on some browsers)", E_USER_ERROR);
        }

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            $this->write('<hr');
            if ($this->color) {
                $this->write(' color="' . $this->color . '" style="border-color: ' . $this->color . '; background-color: ' . $this->color . '"');
            }
            $this->write('/>');
        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            $this->write('<p>---</p>');
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {
            $this->write('<hr');
            if ($this->color) {
                $this->write(' color="' . $this->color . '"');
            }
            $this->write('>');
        }
        
    }
}

?>
