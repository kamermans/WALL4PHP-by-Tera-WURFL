<?php

if (!defined('WALL_INCLUDED')) {

    # We explicitly tell wurfl_class to use cache.php.
    # From PHP_Tools by Andrea. 
    # WARNING: This fails with:
    #   Notice: Constant LOG_LEVEL already defined in [..path..]/wurfl/wurfl_class.php on line 89
    ### define('LOG_LEVEL', 0);
    
    # Shall we use classic PHP Tools or Tera WURFL?
    define('WALL_USE_TERA_WURFL', true);
    # For Tera-WURFL 1.x use '1', for 2.x use '2'
    # For the Tera-WURFL Remote Webservice, use 'webservice'
    define('TERA_WURFL_VERSION', 2);
    # If you are using the Tera-WURFL Remote Webservice, set this to the URL of the webservice
    define('TERA_WURFL_WEBSERVICE_URL','http://localhost/Tera-Wurfl/webservice.php');

    # Configuration of PHP Tools or Tera WURFL by Steve Kamerman
    if (defined('WALL_USE_TERA_WURFL') && WALL_USE_TERA_WURFL) {
    	switch(TERA_WURFL_VERSION){
    		case 1:
	 	       require_once(dirname(__FILE__) . '/tera-wurfl/tera_wurfl_config.php');
	 	       break;
    		case 2:
	    		// For Tera-WURFL 2.x we just need to load the Tera-WURFL class
	    		// TODO: Set this to the location of your TeraWurfl.php file.
	    		require_once(dirname(__FILE__) . '../../Tera-WURFL/TeraWurfl.php');
	    		break;
    		case 'webservice':
    			// For the Tera-WURFL 2.x Remote Webservice we just need to load the remote client
	    		// TODO: Set this to the location of your TeraWurflRemoteClient.php file.
	    		require_once(dirname(__FILE__) . '/TeraWurflRemoteClient.php');
	    		break;
    	}
    } else {
        require_once(dirname(__FILE__) . '/wurfl/wurfl_config.php');
    }
    
    # These capabilities are used by WALL4PHP
    $GLOBALS['WALLWurflCapabilities'] = array(
        "basic_authentication_support",
        "built_in_back_button_support",
        "card_title_support",
        "chtml_table_support",
        "ctml_make_phone_call_string",
        "flash_lite",
        "gif",
        "imode_region",
        "menu_with_select_element_recommended",
        "opwv_wml_extensions_support",
        "opwv_xhtml_extensions_support",
        "preferred_markup",
        "resolution_width",
        "softkey_support",
        "wbmp",
        "wml_1_3",
        "wml_make_phone_call_string",
        "xhtml_document_title_support",
        "xhtml_format_as_attribute",
        "xhtml_format_as_css_property",
        "xhtml_make_phone_call_string",
        "xhtml_marquee_as_css_property",
        "xhtmlmp_preferred_mime_type",
        "xhtml_nowrap_mode",
        "xhtml_preferred_charset",
        "xhtml_readable_background_color1",
        "xhtml_readable_background_color2",
        "xhtml_supports_css_cell_table_coloring",
        "xhtml_supports_table_for_layout",
        "xhtml_table_support"
	);
    
    # For debug purporses. If you see following line uncommented, delete it or comment
    # it out. This is needed only, if Wall.php and Wall directory resides outside
    # include_path (current folder is NOT in include path by default).
    #set_include_path('.');
    
    # We parse HTTP request's Accept header to make sure, that unknown
    # devices get markup which best matches to what they expect.
    define('WALL_PARSE_HTTP_ACCEPT', true);

    # Let's define that WALL has already been included.
    define('WALL_INCLUDED', true);

    # And following lines are ALL you need :)
    require_once('Wall.php');
    $wall = new Wall();
    
}
?>