<?php

require_once plugin_dir_path(__FILE__) . '_formatters.php';

function register_contact_post_type() {
  $labels = array(
      'name'               => _x('Contacts', 'Post type general name', 'portfolio-api-ressources'),
      'singular_name'      => _x('Contact', 'Post type singular name', 'portfolio-api-ressources'),
      'menu_name'          => _x('Contacts', 'Admin Menu text', 'portfolio-api-ressources'),
      'name_admin_bar'     => _x('Contact', 'Add New on Toolbar', 'portfolio-api-ressources'),
      'add_new'            => _x('Add New', 'Contact', 'portfolio-api-ressources'),
      'add_new_item'       => __('Add New Contact', 'portfolio-api-ressources'),
      'new_item'           => __('New Contact', 'portfolio-api-ressources'),
      'edit_item'          => __('Edit Contact', 'portfolio-api-ressources'),
      'view_item'          => __('View Contact', 'portfolio-api-ressources'),
      'all_items'          => __('All Contacts', 'portfolio-api-ressources'),
      'search_items'       => __('Search Contacts', 'portfolio-api-ressources'),
      'parent_item_colon'  => __('Parent Contacts:', 'portfolio-api-ressources'),
      'not_found'          => __('No contacts found.', 'portfolio-api-ressources'),
      'not_found_in_trash' => __('No contacts found in Trash.', 'portfolio-api-ressources')
  );

  $args = array(
      'labels'             => $labels,
      'description'        => __('Description.', 'portfolio-api-ressources'),
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'contacts'),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'supports'           => array('title', 'editor', 'thumbnail'),
      'taxonomies'         => array(),
      'menu_icon'          => 'dashicons-id' // Utilisez l'icône de votre choix
  );

  register_post_type('contact', $args);
}

add_action('init', 'register_contact_post_type');

// Ajout des champs personnalisés pour Contact
function add_contact_custom_fields() {
  add_meta_box(
      'contact_custom_fields',
      __('Contact Custom Fields', 'portfolio-api-ressources'),
      'render_contact_custom_fields',
      'contact',
      'normal',
      'default'
  );
}

function render_contact_custom_fields($post) {
  $value = get_post_meta($post->ID, 'contact_value', true);
  $icon = get_post_meta($post->ID, 'contact_icon', true);
  $isMain = get_post_meta($post->ID, 'contact_isMain', true);
  $href = get_post_meta($post->ID, 'contact_href', true);
  $description = get_post_meta($post->ID, 'contact_description', true);
  ?>
  <label for="contact_value"><?php _e('Value:', 'portfolio-api-ressources'); ?></label>
  <input type="text" id="contact_value" name="contact_value" value="<?php echo esc_attr($value); ?>"><br>

  <label for="contact_icon"><?php _e('Icon:', 'portfolio-api-ressources'); ?></label>
  <input type="text" id="contact_icon" name="contact_icon" value="<?php echo esc_attr($icon); ?>"><br>

  <label for="contact_isMain"><?php _e('Is Main:', 'portfolio-api-ressources'); ?></label>
  <input type="checkbox" id="contact_isMain" name="contact_isMain" <?php checked($isMain, 1); ?>><br>

  <label for="contact_href"><?php _e('Href:', 'portfolio-api-ressources'); ?></label>
  <input type="text" id="contact_href" name="contact_href" value="<?php echo esc_url($href); ?>"><br>

  <label for="contact_description"><?php _e('Description:', 'portfolio-api-ressources'); ?></label>
  <textarea id="contact_description" name="contact_description"><?php echo esc_textarea($description); ?></textarea>
  <?php
}

function save_contact_custom_fields($post_id) {
  if (array_key_exists('contact_value', $_POST)) {
      update_post_meta(
          $post_id,
          'contact_value',
          sanitize_text_field($_POST['contact_value'])
      );
  }
  if (array_key_exists('contact_icon', $_POST)) {
      update_post_meta(
          $post_id,
          'contact_icon',
          sanitize_text_field($_POST['contact_icon'])
      );
  }
  if (array_key_exists('contact_isMain', $_POST)) {
      update_post_meta(
          $post_id,
          'contact_isMain',
          $_POST['contact_isMain'] === 'on' ? 1 : 0
      );
  }
  if (array_key_exists('contact_href', $_POST)) {
      update_post_meta(
          $post_id,
          'contact_href',
          esc_url($_POST['contact_href'])
      );
  }
  if (array_key_exists('contact_description', $_POST)) {
      update_post_meta(
          $post_id,
          'contact_description',
          sanitize_text_field($_POST['contact_description'])
      );
  }
}



function register_contacts_api_routes() {
  register_rest_route('par/v1', 'contacts', array(
      'methods' => 'GET',
      'callback' => 'get_contacts'
  ));
}

function get_contacts($request) {
  $args = array(
      'post_type' => 'contact',
      'posts_per_page' => -1
  );

  $contacts = get_posts($args);

  $formatted_contacts = array();

  foreach ($contacts as $contact) {

      $formatted_contacts[] = format_contact($contact);
  }

  return $formatted_contacts;
}




add_action('rest_api_init', 'register_contacts_api_routes');
add_action('add_meta_boxes', 'add_contact_custom_fields');
add_action('save_post', 'save_contact_custom_fields');