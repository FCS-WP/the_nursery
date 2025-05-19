<?php
namespace Woolentor\Modules\RenameLabel;
use WooLentor\Traits\ModuleBase;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Rename_label{
    use ModuleBase;

    /**
     * Class Constructor
     */
    public function __construct(){

        // Include Nessary file
        $this->include();

        // initialize
        $this->init();

    }

    /**
     * Load Required File
     *
     * @return void
     */
    public function include(){
        if( self::$_enabled ){
            require_once( __DIR__. "/includes/classes/Frontend.php" );
        }
    }

    /**
     * Module Initilize
     *
     * @return void
     */
    public function init(){

        if( self::$_enabled ){
            // For Frontend
            if ( $this->is_request( 'frontend' ) ) {
                Frontend::instance();
            }
        }
        
    }

}