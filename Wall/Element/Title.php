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

class WallElementTitle extends WallElement {
    
    var $tag = 'title';
    
    var $enforce_title = false;
    
    var $document = false;
    
    function WallElementTitle (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
        $this->document =& $this->getAncestorByClassName('wallelementdocument');
        
    }
    
    function doStartTag() {
        parent::doStartTag();

        if (!$this->getAncestorByClassName('wallelementdocument') ||
            !$this->getAncestorByClassName('wallelementhead')) {
            trigger_error("tag 'title' must be nested inside 'head' and 'document' tags", E_USER_ERROR);
        }

        $this->_wall->enforce_title = $this->enforce_title;
        //echo $this->_wall->enforce_title;

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false ||
            strpos($this->preferred_markup, 'chtml') !== false) {
            
            $this->write('<title>');
            //return WALL_SKIP_BODY;//$this->_wall->skip_body = true;
        } else if (strpos($this->preferred_markup, 'wml') !== false) {

            $this->write('<meta name="Generator" content="Wall4PHP"/>');

            return WALL_SKIP_BODY;//$this->_wall->skip_body = true;
        
        }
        
    }
    
    function doEndTag() {
        parent::doEndTag();

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false ||
            strpos($this->preferred_markup, 'chtml') !== false) {
            
            $this->write('</title>');
            
        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            
            $this->_wall->skip_body = true;
        
        }
        $this->document->title = isset($this->cdata) ? $this->cdata : false;

    }
    
}

?>