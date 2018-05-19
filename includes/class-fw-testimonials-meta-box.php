<?php
 /** 
 * This class creates a meta box for our custom post type.
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
class FW_Testimonials_Meta_Box {
    
    function __construct()  {
        
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );

    }
    
    /**
     * Load meta box.
     *
     * @since  0.9.1
     */
    public function add_meta_box() {

        add_meta_box(
            FW_TESTIMONIALS_POST_TYPE_META_BOX_ID,
            'Testimonial Details',
            array( $this, 'render_meta_box' ),
            FW_TESTIMONIALS_POST_TYPE_ID,
            'normal',
            'high'
        );

    }

    /**
     * Callback from add_meta_box() to render our meta box.
     *
     * @since  0.9.1
     */
    public function render_meta_box() {

        global $post;

        $this->meta_box_detail_fields( $post->ID );

    }

    /**
     * Display our meta box fields.
     *
     * @since  0.9.1
     *
     * @param  int  $post_id   Post id.
     */
    private function meta_box_detail_fields( $post_id ) {

        $name     = get_post_meta( $post_id, FW_TESTIMONIALS_POST_TYPE_META_FIELD_NAME_ID, true );
        $company  = get_post_meta( $post_id, FW_TESTIMONIALS_POST_TYPE_META_FIELD_COMPANY_ID, true );
        $websites = get_post_meta( $post_id, FW_TESTIMONIALS_POST_TYPE_META_FIELD_WEBSITES_ID, true );

        // Init website links, if empty.
        if ( empty( $websites ) ) {
            $websites = array(
                array(
                    'label' => '',
                    'url'   => ''
                ),
                array(
                    'label' => '',
                    'url'   => ''
                ),
                array(
                    'label' => '',
                    'url'   => ''
                )
            );
        }

        ?>
        <?php wp_nonce_field( 'fw_testimonial_save', 'fw_testimonial_meta_box_nonce' ); ?>

        <table class="form-table">
            <tr>
                <th><label>Name</label></th>
                <td>
                    <input type="text" id="fw_testimonials_name" name="fw_testimonials_name" 
                           value="<?php echo esc_attr($name); ?>" />
                </td>
            </tr> 
            <tr>
                <th><label>Company</label></th>
                <td>
                    <input type="text" id="fw_testimonials_company" name="fw_testimonials_company" 
                           value="<?php echo esc_attr($company); ?>" />
                </td>
            </tr> 

            <tr>
                <th><label>Websites</label></th>
                <td>
                    <!-- Don't change the class names in this table. JavaScript events are
                         attached to them. -->
                    <table class="fw-testimonials-websites">
                        <tbody>
                            <tr>
                                <th>Label</th>
                                <th>URL</th>
                            </tr>

                            <?php
                            foreach ( $websites as $website ) : ?>
                                <tr class="fw-testimonials-website">
                                    <td><input type="text" class="fw-testimonials-website-label"
                                               name="fw-testimonials-website-label[]" 
                                               value="<?php echo esc_attr( $website['label'] ); ?>"
                                               placeholder="Website name"
                                               maxlength="300" />
                                    </td>
                                    <td><input type="text" class="fw-testimonials-website-url"
                                               name="fw-testimonials-website-url[]"
                                               value="<?php echo esc_attr( $website['url'] ); ?>"
                                               placeholder="https://mywebsite.com"
                                               maxlength="300" /></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
             </tr>

        </table>

        <?php

    }

    /**
     * Save our meta box fields.
     *
     * @since  0.9.1
     *
     * @param  int       $post_id   Post id.
     * @param  WP_Post   $post      Post object (https://developer.wordpress.org/reference/classes/wp_post/)
     */
    public function save_meta_box( $post_id, $post ) {
        
        if ( ! isset( $_POST['fw_testimonial_meta_box_nonce'] ) ||
             ! wp_verify_nonce( $_POST['fw_testimonial_meta_box_nonce'], 'fw_testimonial_save' ) ) {
            return;
        }

        if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
             ( defined( 'DOING_AJAX') && DOING_AJAX ) ||
               isset( $_REQUEST['bulk_edit'] ) ) {
            return;
        }

        if ( isset( $post->post_type ) && 'revision' == $post->post_type ) {
            return;
        }

        if ( ! current_user_can( 'edit_posts', $post_id ) ) {
            return;
        }

        // Save meta data
        if ( isset( $_POST['fw_testimonials_name'] ) ) {

            $value = sanitize_text_field( trim( $_POST['fw_testimonials_name'] ) );

            update_post_meta( $post_id, FW_TESTIMONIALS_POST_TYPE_META_FIELD_NAME_ID, $value );

        }

        if ( isset( $_POST['fw_testimonials_company'] ) ) {

            $value = sanitize_text_field( trim( $_POST['fw_testimonials_company'] ) );

            update_post_meta( $post_id, FW_TESTIMONIALS_POST_TYPE_META_FIELD_COMPANY_ID, $value );

        }

        // Save the websites.
        if ( isset( $_POST[ 'fw-testimonials-website-label' ] ) &&
             isset( $_POST[ 'fw-testimonials-website-url' ] ) ) {

            $website_labels = array_map( 'sanitize_text_field', $_POST['fw-testimonials-website-label'] );
            $website_urls   = array_map( 'sanitize_text_field', $_POST['fw-testimonials-website-url'] );

            // We'll consolidate our label and url arrays into one for easier storage.
            $websites = array();

            for ($i = 0, $website_count = count( $website_labels ); $i < $website_count; $i++ ) {
                $label = trim( $website_labels[$i] );
                $url   = trim( $website_urls[$i] );

                $websites[] = array(
                    'label' => $label,
                    'url'   => $url
                );

            }

            update_post_meta( $post_id, FW_TESTIMONIALS_POST_TYPE_META_FIELD_WEBSITES_ID, $websites );
        }

    }

}