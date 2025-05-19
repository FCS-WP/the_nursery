<?php
namespace WooLentor\MultiLanguage;
use WooLentor\Traits\Singleton;
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Languages {
    use Singleton;
    
    /**
     * [$language_code]
     * @var string
     */
    public static $language_code;

    /**
     * Translator Name
     * @var 
     */
    public static $translator_name;

    /**
     * [__construct] Class constructor
    */
    public function __construct() {
        $this->set_language_code();
        add_filter( 'woolentor_current_language_code', [$this, 'get_language_code'] );
    }

    /**
     * [set_language_code]
     * @return [void]
    */
    public static function set_language_code() {
        
        if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
            self::$language_code = apply_filters( 'wpml_current_language', 'en' );
            self::$translator_name = 'wpml';
            
        } elseif ( function_exists( 'pll_current_language' ) ) {
            self::$language_code = pll_current_language();
            self::$translator_name = 'polylang';
        }
        
    }

    /**
     * [get_language_code]
     * @var $language_code
     * @return [string]
    */
    public static function get_language_code( $language_code ) {
        if ( self::$language_code ) {
            return self::$language_code;
        }
        return $language_code;
    }

    /**
     * Manage Single Text translate
     * @param mixed $name
     * @param mixed $value
     * @return mixed
     */
    public static function translator( $name, $value ){
        if ( 'polylang' === self::$translator_name && function_exists('pll_translate_string') ) {
            return pll_translate_string( $value, self::$translator_name );
        } elseif ( 'wpml' === self::$translator_name ) {
            return apply_filters( 'wpml_translate_single_string', $value, 'ShopLentor Module', $name );
        }
        return $value;
    }

}
Languages::instance();