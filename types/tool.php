<?php

require_once plugin_dir_path(__FILE__) . '_formatters.php';

// initialisation du C.P.T Tool
function register_tool_post_type() {
    $labels = array(
        'name'               => _x('Tools', 'Post type general name', 'portfolio-api-ressources'),
        'singular_name'      => _x('Tool', 'Post type singular name', 'portfolio-api-ressources'),
        'menu_name'          => _x('Tools', 'Admin Menu text', 'portfolio-api-ressources'),
        'name_admin_bar'     => _x('Tool', 'Add New on Toolbar', 'portfolio-api-ressources'),
        'add_new'            => _x('Add New', 'Tool', 'portfolio-api-ressources'),
        'add_new_item'       => __('Add New Tool', 'portfolio-api-ressources'),
        'new_item'           => __('New Tool', 'portfolio-api-ressources'),
        'edit_item'          => __('Edit Tool', 'portfolio-api-ressources'),
        'view_item'          => __('View Tool', 'portfolio-api-ressources'),
        'all_items'          => __('All Tools', 'portfolio-api-ressources'),
        'search_items'       => __('Search Tools', 'portfolio-api-ressources'),
        'parent_item_colon'  => __('Parent Tools:', 'portfolio-api-ressources'),
        'not_found'          => __('No tools found.', 'portfolio-api-ressources'),
        'not_found_in_trash' => __('No tools found in Trash.', 'portfolio-api-ressources')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Description.', 'portfolio-api-ressources'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'tools'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'taxonomies'         => array(),
        'menu_icon'          => 'dashicons-hammer' // Utilisez l'icône de votre choix
    );

    register_post_type('tool', $args);
}
add_action('init', 'register_tool_post_type');

// Ajout des champs personnalisés pour Tool
function add_tool_custom_fields() {
    add_meta_box(
        'tool_custom_fields',
        __('Tool Custom Fields', 'portfolio-api-ressources'),
        'render_tool_custom_fields',
        'tool',
        'normal',
        'default'
    );
}
function render_tool_custom_fields($post) {
    $img_link = get_post_meta($post->ID, 'tool_img_link', true);
    $percentage = get_post_meta($post->ID, 'tool_percentage', true);
    $color = get_post_meta($post->ID, 'tool_color', true);
    $group_title_slug = get_post_meta($post->ID, 'tool_group_title_slug', true);
    $group_title = get_post_meta($post->ID, 'tool_group_title', true);
    $description = get_post_meta($post->ID, 'tool_description', true);
    ?>
    <label for="tool_img_link"><?php _e('Image Link:', 'portfolio-api-ressources'); ?></label>
    <input type="text" id="tool_img_link" name="tool_img_link" value="<?php echo esc_url($img_link); ?>"><br>

    <label for="tool_percentage"><?php _e('Percentage:', 'portfolio-api-ressources'); ?></label>
    <input type="number" id="tool_percentage" name="tool_percentage" value="<?php echo esc_attr($percentage); ?>"><br>

    <label for="tool_color"><?php _e('Color:', 'portfolio-api-ressources'); ?></label>
    <input type="text" id="tool_color" name="tool_color" value="<?php echo esc_attr($color); ?>"><br>

    <label for="tool_group_title"><?php _e('Group Title:', 'portfolio-api-ressources'); ?></label>
    <input type="text" id="tool_group_title" name="tool_group_title" value="<?php echo esc_attr($group_title); ?>"><br>

    <label for="tool_description"><?php _e('Description:', 'portfolio-api-ressources'); ?></label>
    <textarea id="tool_description" name="tool_description"><?php echo esc_textarea($description); ?></textarea>
    <?php
}
add_action('add_meta_boxes', 'add_tool_custom_fields');

/* Sauve garde des champs personalisés */
function save_tool_custom_fields($post_id) {
    if (array_key_exists('tool_img_link', $_POST)) {
        update_post_meta(
            $post_id,
            'tool_img_link',
            esc_url($_POST['tool_img_link'])
        );
    }
    if (array_key_exists('tool_percentage', $_POST)) {
        update_post_meta(
            $post_id,
            'tool_percentage',
            intval($_POST['tool_percentage'])
        );
    }
    if (array_key_exists('tool_color', $_POST)) {
        update_post_meta(
            $post_id,
            'tool_color',
            sanitize_text_field($_POST['tool_color'])
        );
    }
    if (array_key_exists('tool_group_title', $_POST)) {
        $group_title = sanitize_text_field($_POST['tool_group_title']);
        update_post_meta(
            $post_id,
            'tool_group_title',
            $group_title
        );
    // Générez le group_title_slug à partir de group_title
        $group_title_slug = sanitize_title($group_title);
        update_post_meta(
            $post_id,
            'tool_group_title_slug',
            $group_title_slug
        );
    }
    if (array_key_exists('tool_description', $_POST)) {
        update_post_meta(
            $post_id,
            'tool_description',
            sanitize_text_field($_POST['tool_description'])
        );
    }
}
add_action('save_post', 'save_tool_custom_fields');



/**
 * 
 *  GESTION DE L'API
 * 
 */


 // -- La route pour avoir toutes les tools
function register_tools_api_routes() {
    register_rest_route('par/v1', 'tools', array(
        'methods' => 'GET',
        'callback' => 'get_tools'
    ));
}
function get_tools($request) {
    $args = array(
        'post_type' => 'tool',
        'posts_per_page' => -1
    );

    $tools = get_posts($args);

    $formatted_tools = array();

    foreach ($tools as $tool) {

        $formatted_tools[] = format_tool($tool);
    }

    return $formatted_tools;
}
add_action('rest_api_init', 'register_tools_api_routes');


 // -- La route qui permet d'avoir un tool par ID
function register_tool_id_route() {
    register_rest_route('par/v1', 'tools/(?P<tool_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_tool_by_id'
    ));
}
function get_tool_by_id($request) {
    $tool_id = $request->get_param('tool_id');

    $tool = get_post($tool_id);

    if (!$tool || $tool->post_type !== 'tool') {
        return new WP_Error('not_found', 'Tool not found', array('status' => 404));
    }

    return format_tool($tool);
}
add_action('rest_api_init', 'register_tool_id_route');


 // -- La route qui permet d'avoir des tools en fonction du slug de leur groupe
 function register_tool_group_title_slug_route() {
    register_rest_route('par/v1', 'tools/group/(?P<group_title_slug>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'get_tools_by_group_title_slug'
    ));
}
function get_tools_by_group_title_slug($request) {
    $group_title_slug = $request->get_param('group_title_slug');

    $args = array(
        'post_type' => 'tool',
        'meta_key' => 'tool_group_title_slug',
        'meta_value' => $group_title_slug,
        'posts_per_page' => -1
    );

    $tools = get_posts($args);

    $formatted_tools = array();

    foreach ($tools as $tool) {
        $formatted_tools[] = format_tool($tool);
    }
    if(count($formatted_tools) > 1) return $formatted_tools;
    else return new WP_Error('not_found', 'group "'.$group_title_slug.'" not found', array('status' => 404));
}
add_action('rest_api_init', 'register_tool_group_title_slug_route');

