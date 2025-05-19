<?php
if ( ! class_exists( 'WooLentorBlocks' ) ) :

	/**
	 * Main WooLentorBlocks Class
	 */
	final class WooLentorBlocks{

		/**
		 * [$pattern_info]
		 * @var array
		 */
		public static $pattern_info = [];

		/**
		 * [$_instance]
		 * @var null
		 */
		private static $_instance = null;

		/**
		 * [instance] Initializes a singleton instance
		 * @return [Actions]
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * The Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			add_action( 'plugins_loaded', [ $this, 'init' ] );
		}

		/**
		 * Initialize
		 */
		public function init(){
			// Pattern Remote Info
			if( woolentorBlocks_gutenberg_edit_screen() ){
				if( is_admin() && class_exists('\Woolentor_Template_Library_Manager') ){
					self::$pattern_info = \Woolentor_Template_Library_Manager::instance()->get_gutenberg_patterns_info();
				}
			}
			$this->dependency_class_instance();
		}

		/**
		 * Define the required plugin constants
		 *
		 * @return void
		 */
		public function define_constants() {
			$this->define( 'WOOLENTOR_BLOCK_FILE', __FILE__ );
			$this->define( 'WOOLENTOR_BLOCK_PATH', __DIR__ );
			$this->define( 'WOOLENTOR_BLOCK_URL', plugins_url( '', WOOLENTOR_BLOCK_FILE ) );
			$this->define( 'WOOLENTOR_BLOCK_DIR', plugin_dir_path( WOOLENTOR_BLOCK_FILE ) );
			$this->define( 'WOOLENTOR_BLOCK_ASSETS', WOOLENTOR_BLOCK_URL . '/assets' );
			$this->define( 'WOOLENTOR_BLOCK_TEMPLATE', trailingslashit( WOOLENTOR_BLOCK_DIR . 'includes/templates' ) );
		}

		/**
	     * Define constant if not already set
	     *
	     * @param  string $name
	     * @param  string|bool $value
	     */
	    private function define( $name, $value ) {
	        if ( ! defined( $name ) ) {
	            define( $name, $value );
	        }
	    }

		/**
		 * Load required file
		 *
		 * @return void
		 */
		private function includes() {
			include( WOOLENTOR_BLOCK_PATH . '/vendor/autoload.php' );
		}

		/**
		 * Load dependency class
		 *
		 * @return void
		 */
		private function dependency_class_instance() {
			WooLentorBlocks\Scripts::instance();
			WooLentorBlocks\Manage_Styles::instance();
			WooLentorBlocks\Actions::instance();
			WooLentorBlocks\Blocks_init::instance();
			if( class_exists('\WooLentorBlocks\Block_Patterns_init') ){
				\WooLentorBlocks\Block_Patterns_init::instance();
			}
		}


	}
	
endif;

/**
 * The main function for that returns woolentorblocks
 *
 */
function woolentorblocks() {
	if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) {
		return;
	}elseif( class_exists( 'Classic_Editor' ) ){
		return;
	}else{
		return WooLentorBlocks::instance();
	}
}
woolentorblocks();
