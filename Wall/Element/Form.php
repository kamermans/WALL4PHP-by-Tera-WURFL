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

class WallElementForm extends WallElement {
    
    var $tag = 'form';
    
    var $action = false;
    var $enable_wml = false;
    var $method = false;
    
    var $fields = Array();
    
    function WallElementForm (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();
        
        if ($this->getAncestorByClassName('wallelementblock')) {
            trigger_error("'form' tag cannot be nested inside a 'block' tag (breaks XHTML validity and will produce an error on some browsers).\n Close or remove the containing 'block' tag.", E_USER_ERROR);
        }

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            
            if (!$this->getAncestorByClassName('wallelementdocument')) {
                trigger_error("tag 'form' (wml enabled) must be nested inside a 'document' tag", E_USER_ERROR);
            }

            $this->write('<form action="' . $this->action . '"');
            if ($this->method) {
                $this->write(' method="' . $this->method . '"');
            }
            $this->write('><p>');
            
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {

            $this->write('<form action="' . $this->action . '"');
            if ($this->method) {
                $this->write(' method="' . $this->method . '"');
            }
            $this->write('>');

        } else if (strpos($this->preferred_markup, 'wml') !== false && $this->enable_wml) {
            
            if (!$this->getAncestorByClassName('wallelementdocument')) {
                trigger_error("tag 'form' (wml enabled) must be nested inside a 'document' tag", E_USER_ERROR);
            }
            $this->write('<p>');
            
        } else {
            $this->write('This page is not available to WAP 1.X devices. If you think this is an error, please contact your service provider.');
            return WALL_SKIP_BODY;
        }
        
        
    }
    
    function doEndTag() {
        parent::doEndTag();

        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            
            $this->write('</p></form>');
            
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {

            $this->write('</form>');

        } else if (strpos($this->preferred_markup, 'wml') !== false && $this->enable_wml) {
            
            $this->write('</p>');
            
        }
        
    }
    
    function addField($field) {
        $this->fields[] = Array(
            'type' => $field->type,
            'name' => $field->name,
            'value' => $field->value,
        );
    }
}

?>