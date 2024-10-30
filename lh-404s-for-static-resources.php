<?php
/**
 * Plugin Name: LH 404s for static resources
 * Plugin URI: https://lhero.org/portfolio/lh-404s-for-static-resources/
 * Description: Add simple 404 page for static files. Means that full page requests are not made if things like images or scripts do not exist.
 * Version: 1.00
 * Text Domain: lh_404s
 * Domain Path: /languages
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
*/

if (!class_exists('LH_404s_for_static_resources_plugin')) {
    

class LH_404s_for_static_resources_plugin {
    
      private static $instance;
     
      
    static function return_plugin_namespace(){
    
        return 'lh_404s';
    
    }
    
    /**
     * Do a 404 response for the files that don't need to be accessed
    */
    public function handle_404_response() {
    
    	if ( ! is_404() ) {
    		return;
    	}
    
    	$file_extension = '';
    	if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
    	    
    		$file_extension = strtolower( pathinfo( $_SERVER['REQUEST_URI'], PATHINFO_EXTENSION ) );
    		
    	}
    
    	$bad_file_types = array(
    		'css',
    		'txt',
    		'jpg',
    		'gif',
    		'rar',
    		'zip',
    		'png',
    		'bmp',
    		'tar',
    		'doc',
    		'xml',
    		'js',
    		'docx',
    		'xls',
    		'xlsx',
    		'svg',
    		'webp',
    	);
    
    	if ( in_array( $file_extension, $bad_file_types, true ) ) {
    	    
    		header( 'HTTP/1.1 404 Not Found' );
    		esc_html_e( '404 error - file does not exist', self::return_plugin_namespace());
    		die();
    		
    	}
    
    }

    public function plugin_init(){
        
        //load translations
        load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 
        
        //intercept requests
        add_filter( 'template_redirect', array($this, 'handle_404_response'),PHP_INT_MAX );
        
        
    }
      
 
    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        
        if (null === self::$instance) {
            
            self::$instance = new self();
            
        }
 
        return self::$instance;
        
    }




	/**
	* Constructor
	*/
	public function __construct() {
	    
    	 //run our hooks on plugins loaded to as we may need checks       
        add_action( 'plugins_loaded', array($this,'plugin_init'));

    }

      
}

$lh_404s_for_static_resources_instance = LH_404s_for_static_resources_plugin::get_instance();


}






?>