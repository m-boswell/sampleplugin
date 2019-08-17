<?php // sampleplugin - Core Functionality



// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/*
 * - - - - - - - - -
 * Table of Contents |
 * - - - - - - - - -
 * #Custom Post type and Taxonomies
 * #Add Meta Boxes
 * #Register REST Fields
 *
 */

/*
 * #Custom Post type and Taxonomies
 * ---------------------------------
 *
 */

// add custom post type
function sampleplugin_add_custom_post_type() {

    //  Wordpress Custom Post Type Configuration Args
    $args = array(
        'labels'             => array( 'name' => esc_html__('Host', 'sampleplugin') ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => esc_html__('Hosts', 'sampleplugin') ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true,                       // Enables REST API, also needed to enable Gutenburg Editor
        'supports'           => array( 'title', 'editor'), // add support features to the post type.
    );

    register_post_type( esc_html__('host', 'sampleplugin'), $args );

}
add_action( 'init', 'sampleplugin_add_custom_post_type' );




/*
 * #Add Meta Boxes
 * ---------------------------------
 *
 */


// register meta box
function sampleplugin_add_meta_box() {

    $post_types = array( 'host' );

    foreach ( $post_types as $post_type ) {

        add_meta_box(
            'sampleplugin_meta_box',                                                 // Unique ID of meta box
            esc_html__('sampleplugin Meta Box', 'sampleplugin'),            // Title of meta box
            'sampleplugin_display_meta_box',                                    // Callback function
            $post_type                                                                  // Post type
        );
    }
}
add_action( 'add_meta_boxes', 'sampleplugin_add_meta_box' );



// display meta box
function sampleplugin_display_meta_box( $post ) {

    $value = get_post_meta( $post->ID, '_sampleplugin_somedata_meta_key', true );

    wp_nonce_field( basename( __FILE__ ), 'sampleplugin_meta_box_nonce' ); //set a nonce for security.

    esc_html_e( 'Some data:', 'sampleplugin') . '<br>';
    echo '<input type="text" name="somedata" value="'.$value.'"><br>';

}



// save meta box
function sampleplugin_save_meta_box( $post_id ) {

    $is_autosave = wp_is_post_autosave( $post_id ); //check if post is autosaved
    $is_revision = wp_is_post_revision( $post_id ); // check if post is a revision.

    $is_valid_nonce = true;
    // Security: check if nonce is set
    if ( isset( $_POST[ 'sampleplugin_meta_box_nonce' ] ) ) {
        // Security: check if nonce is correct
        if ( wp_verify_nonce( $_POST[ 'sampleplugin_meta_box_nonce' ], basename( __FILE__ ) ) ) {
            // set boolean flag to true.
            $is_valid_nonce = true;
        }

    }

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;

    if ( array_key_exists( 'somedata', $_POST ) ) {

        update_post_meta(
            $post_id,                                            // Post ID
            '_sampleplugin_somedata_meta_key',                       // Meta key
            sanitize_text_field( $_POST[ 'somedata' ] ) // Meta value
        );

    }

}
add_action( 'save_post', 'sampleplugin_save_meta_box' );






/*
 * #Register REST Fields
 * ---------------------------------
 *
 */
function sampleplugin_register_meta_data(){
    register_rest_field(
            'host', // post type
               'somedata', // field name or label
            array(
                'get_callback'    => 'sampleplugin_get_somedata',
                'update_callback' => 'sampleplugin_update_somedata',
                'schema'          => null,
            )
    );
}
add_action('rest_api_init', 'sampleplugin_register_meta_data');


//For a given id in the post id get the field for 'somedata', set the value to display.
function sampleplugin_get_somedata( $object, $field_name, $request ) {
    $field_name = '_sampleplugin_somedata_meta_key';
    return get_post_meta( $object['id'], $field_name, true );
}