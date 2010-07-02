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

class WallElementFont extends WallElement {
    
    var $tag = 'font';
    
    var $color = false;
    var $size = false;
    var $face = false;
    
    var $style = '';
    var $font = '';
    
    function WallElementFont (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            
            if ($this->color) {
                $this->style .= 'color: ' . $this->color . '; ';
            }
            if ($this->face) {
                $this->style .= 'font-family: ' . $this->face . '; ';
            }
            
            if ($this->size == '+1') {
                $this->style .= 'font-size: larger; ';
            }
            if ($this->size == '-1') {
                $this->style .= 'font-size: smaller; ';
            }
            
            if (strlen($this->style)) {
                $this->write('<span style="' . trim($this->style) . '">');
            }
            
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {

            if ($this->color) {
                $this->font .= ' color="' . $this->color . '"';
            }
            if ($this->face) {
                $this->font .= ' face="' . $this->face . '"'; 
            }
            if ($this->size) {
                $this->font .= ' size="' . $this->size . '"'; 
            }
            
            if (strlen($this->font)) {
                $this->write('<font ' . $this->font . '>');
            }

        } else if (strpos($this->preferred_markup, 'wml') !== false) {

        }
        
    }
    
    function doEndTag() {
        parent::doEndTag();

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            
            if (strlen($this->style)) {
                $this->write('</span>');
            }
            
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {

            if (strlen($this->font)) {
                $this->write('</font>');
            }

        }
    }
}

?>