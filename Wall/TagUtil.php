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

class TagUtil {
    
    function isUplink($ua) {
        return (strpos($ua, 'UP.Link') !== false);
    }
    
    function normalizeHref($href, $markup) {
        $h = $href;
        if (strpos($markup, 'chtml') !== false) {
            $h = str_replace('&amp;', '&', $h);
        } else {
            $h = str_replace('&', '&amp;', $h);
            $h = str_replace('&amp;amp;', '&amp;', $h);
        }
        
//        $h = str_replace('&amp;', '&amp;amp;', $h);
        $h = str_replace('&amp;amp;', '&amp;', $h);
        return $h;
    }

    function debuglog($str) {
        if (getenv('REMOTE_ADDR') == '83.223.131.104') {
            $t = date('Y-m-d H:i:s');
       	    $t = "[WALL4PHP] [$t] $str";
	    error_log($t);
	}
    }
    
    function getWallMarkup($prefmarkup) {
//    return $prefmarkup;
        if (isset($_GET['markup']) && in_array($_GET['markup'], Array('wml', 'xhtmlmp', 'chtml'))) {
            return($_GET['markup']);
	}
        if (strpos($prefmarkup, 'html_wi_oma_xhtmlmp') !== false ||
            strpos($prefmarkup, 'html_wi_w3_xhtmlbasic') !== false) {
            return('xhtmlmp');
        } else if (strpos($prefmarkup, 'html_wi_imode') !== false ||
                   strpos($prefmarkup, 'html_web') !== false) {
            return('chtml');
        } else if (strpos($prefmarkup, 'wml') !== false) {
            return('wml');
	} else if (defined('WALL_PARSE_HTTP_ACCEPT') && WALL_PARSE_HTTP_ACCEPT) {
            #return 'wml';
            #return 'xhtmlmp';
            
            # Let's try to guess best suitable markup via ACCEPT parameter
            
            if ($accept = getenv('HTTP_ACCEPT')) {
                $a = Array();
                //echo $accept . "\n";
                # Create array with content types and qualities
                preg_match_all('/([\*a-z0-9\.\-\/\+]+)(;q=([\d\.]+))?,?/is', $accept, $tokens);
                for ($i = 0; $i < count($tokens[0]); $i++) {
                    $token = $tokens[1][$i];
                    $quality = $tokens[3][$i] ? $tokens[3][$i] : 1;
                    $a[] = Array('t'=>$token, 'q'=>$quality);
                }
                
                # Reorder content types based on qualities
                for ($i = 1; $i < count($a); $i++) {
                    if ($a[$i-1]['q'] < $a[$i]['q']) {
                        $tmp = $a[$i-1];
                        $a[$i-1] = $a[$i];
                        $a[$i] = $tmp;
                        $i = 0;
                    }
                }
                
		# Well, let's see, what is that thing, our client wants most?
                
                $ret = false;
		foreach ($a as $v) {
                    switch ($v['t']) {
                        case 'text/xml' :
                        case 'application/xml' :
                        case 'application/xhtml+xml' :
                        case 'application/vnd.wap.html+xml' :
			case '*/*' :
                            $ret = 'xhtmlmp';
                            break;
                        case 'text/vnd.wap.wml' :
                        case 'application/wml+xml' :
                            $ret = 'wml';
                            break;
		    }
                    if ($ret) {
                        return ($ret);
                    }
                    
                }

                # IF EVERYTHING FAILS, RETURN WML
            } else {
                
                return 'wml';
            
            }
                
        } else {
            return 'wml'; 
        }
    }
    
    function getEmoji($s, $s1) {
        
        $emojis = Array(
            'eu' => Array(
                '0' => '&#59115;',
                '1' => '&#59106;',
                '2' => '&#59107;',
                '3' => '&#59108;',
                '4' => '&#59109;',
                '5' => '&#59110;',
                '6' => '&#59111;',
                '7' => '&#59112;',
                '8' => '&#59113;',
                '9' => '&#59114;'
            ),
            'ja' => Array(
                '0' => '&#63888;',
                '1' => '&#63879;',
                '2' => '&#63880;',
                '3' => '&#63881;',
                '4' => '&#63882;',
                '5' => '&#63883;',
                '6' => '&#63884;',
                '7' => '&#63885;',
                '8' => '&#63886;',
                '9' => '&#63887;'
            )
        );
        
        return isset($emojis[$s][$s1]) ? $emojis[$s][$s1] : false;
        
    }
    
}

?>
