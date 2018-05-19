<?php
 /** 
 * Bootstrapping class.
 *
 * All of our plugin dependencies are initalized here.
 *
 * @package    FreshWeb_Testimonials
 * @subpackage Functions
 * @copyright  Copyright (c) 2017, freshwebstudio.com
 * @link       https://freshwebstudio.com
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since      0.9.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Class wrapper for all methods.
 *
 * @since 0.9.1
 */
class FW_Testimonials {
    
    function __construct()  { 
    }

    /**
     * Run our initialization.
     *
     * @since 0.9.1
     */
    public function run() {

        $this->setup_constants();
        $this->includes();

    }

    /**
     * Setup plugin constants.
     *
     * @since  0.9.1
     * @access private
     */
    private function setup_constants() {

        /*
         * Set true if plugin is to be detected by theme writers as activated.
         *
         * Theme writers: Use this defined variable to determine if plugin is installed
         * and activated. False means No, True means yes.
         */
        if ( ! defined( 'FW_TESTIMONIALS_IS_ACTIVATED' ) ) {
            define( 'FW_TESTIMONIALS_IS_ACTIVATED', true );
        }     

        // Plugin version.
        if ( ! defined( 'FW_TESTIMONIALS_VERSION' ) ) {
            define( 'FW_TESTIMONIALS_VERSION', '0.9.1' );
        }

        // Plugin Folder Path (without trailing slash)
        if ( ! defined( 'FW_TESTIMONIALS_PLUGIN_DIR' ) ) {
            define( 'FW_TESTIMONIALS_PLUGIN_DIR', dirname( __DIR__ ) );
        }

        // Includes Folder Path (without trailing slash)
        if ( ! defined( 'FW_TESTIMONIALS_INCLUDES_DIR' ) ) {
            define( 'FW_TESTIMONIALS_INCLUDES_DIR', FW_TESTIMONIALS_PLUGIN_DIR . '/includes' );
        }

        // Plugin Folder URL (without trailing slash)
        if ( ! defined( 'FW_TESTIMONIALS_PLUGIN_URL' ) ) {
            define( 'FW_TESTIMONIALS_PLUGIN_URL', untrailingslashit( plugin_dir_url( __DIR__ ) ) );
        }

        // Includes Folder URL (without trailing slash)
        if ( ! defined( 'FW_TESTIMONIALS_INCLUDES_URL' ) ) {
            define( 'FW_TESTIMONIALS_INCLUDES_URL', FW_TESTIMONIALS_PLUGIN_URL . '/includes' );
        }

        // Admin CSS Folder URL (without trailing slash)
        if ( ! defined( 'FW_TESTIMONIALS_ADMIN_CSS_URL' ) ) {
            define( 'FW_TESTIMONIALS_ADMIN_CSS_URL', FW_TESTIMONIALS_PLUGIN_URL . '/admin/css' );
        }

        /*
         * Define CPT and taxonomy names in one place globally.
         */
        if ( ! defined( 'FW_TESTIMONIALS_POST_TYPE_ID' ) ) {
            define( 'FW_TESTIMONIALS_POST_TYPE_ID', 'fw_testimonials' );
        }

        if ( ! defined( 'FW_TESTIMONIALS_TAXONOMY_GROUP_ID' ) ) {
            define( 'FW_TESTIMONIALS_TAXONOMY_GROUP_ID', 'fw_testimonials_group' );
        }

        if ( ! defined( 'FW_TESTIMONIALS_POST_TYPE_META_BOX_ID' ) ) {
            define( 'FW_TESTIMONIALS_POST_TYPE_META_BOX_ID', 'fw_testimonial' );
        }

        /*
         * Define meta field names.
         */
        if ( ! defined( 'FW_TESTIMONIALS_POST_TYPE_META_FIELD_NAME_ID' ) ) {
            define( 'FW_TESTIMONIALS_POST_TYPE_META_FIELD_NAME_ID', '_fw_testimonials_name' );
        }

        if ( ! defined( 'FW_TESTIMONIALS_POST_TYPE_META_FIELD_COMPANY_ID' ) ) {
            define( 'FW_TESTIMONIALS_POST_TYPE_META_FIELD_COMPANY_ID', '_fw_testimonials_company' );
        }

        if ( ! defined( 'FW_TESTIMONIALS_POST_TYPE_META_FIELD_WEBSITES_ID' ) ) {
            define( 'FW_TESTIMONIALS_POST_TYPE_META_FIELD_WEBSITES_ID', '_fw_testimonials_websites' );
        }

    }

    /**
     * Include required files.
     *
     * @since  0.9.1
     * @access private
     */
    private function includes() {

        if ( is_admin() )  {
            require_once FW_TESTIMONIALS_INCLUDES_DIR . '/class-fw-testimonials-admin.php';
            $admin = new FW_Testimonials_Admin;
        }
        else {
            require_once FW_TESTIMONIALS_INCLUDES_DIR . '/class-fw-testimonials-front.php';
            $front = new FW_Testimonials_Front;
        }

        require_once FW_TESTIMONIALS_INCLUDES_DIR . '/class-fw-testimonials-post-types.php';
        $post_types = new FW_Testimonials_Post_Types;

        require_once FW_TESTIMONIALS_INCLUDES_DIR . '/class-fw-testimonials-meta-box.php';
        $meta_boxes = new FW_Testimonials_Meta_Box;

    }

}