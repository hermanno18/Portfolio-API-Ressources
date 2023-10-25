<?php

require_once plugin_dir_path(__FILE__) . '_formatters.php';

// Enregistrement du Custom Post Type pour les Entreprises (`company`) :
function register_company_post_type() {
    $labels = array(
        'name'               => _x('Companies', 'Post type general name', 'portfolio-api-ressources'),
        'singular_name'      => _x('Company', 'Post type singular name', 'portfolio-api-ressources'),
        'menu_name'          => _x('Companies', 'Admin Menu text', 'portfolio-api-ressources'),
        'name_admin_bar'     => _x('Company', 'Add New on Toolbar', 'portfolio-api-ressources'),
        'add_new'            => _x('Add New', 'Company', 'portfolio-api-ressources'),
        'add_new_item'       => __('Add New Company', 'portfolio-api-ressources'),
        'new_item'           => __('New Company', 'portfolio-api-ressources'),
        'edit_item'          => __('Edit Company', 'portfolio-api-ressources'),
        'view_item'          => __('View Company', 'portfolio-api-ressources'),
        'all_items'          => __('All Companies', 'portfolio-api-ressources'),
        'search_items'       => __('Search Companies', 'portfolio-api-ressources'),
        'parent_item_colon'  => __('Parent Companies:', 'portfolio-api-ressources'),
        'not_found'          => __('No companies found.', 'portfolio-api-ressources'),
        'not_found_in_trash' => __('No companies found in Trash.', 'portfolio-api-ressources')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Description.', 'portfolio-api-ressources'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'companies'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'taxonomies'         => array(),
        'menu_icon'          => 'dashicons-building' // Utilisez l'icône de votre choix
    );

    register_post_type('company', $args);
}

add_action('init', 'register_company_post_type');

// Ajout des champs personnalisés pour Company
function add_company_custom_fields() {
    add_meta_box(
        'company_custom_fields',
        __('Company Meta informations', 'portfolio-api-ressources'),
        'render_company_custom_fields',
        'company',
        'normal',
        'default'
    );
    add_meta_box(
      'company_projects',
      __('Select the projects of this company', 'portfolio-api-ressources'),
      'render_company_projects_field',
      'company',
      'normal',
      'default'
  );

  add_meta_box(
      'company_tools',
      __('Select the tools you used here', 'portfolio-api-ressources'),
      'render_company_tools_field',
      'company',
      'normal',
      'default'
  );
}

function render_company_projects_field($post) {
  $selected_projects = get_post_meta($post->ID, 'company_projects', true);

  // Récupérer la liste des projets
  $args = array(
      'post_type' => 'project',
      'posts_per_page' => -1
  );

  $projects = get_posts($args);

  // Récupérer les outils sélectionnés
    if (!empty($selected_projects) && is_array($selected_projects)) {
        $selected_projects = array_map('intval', $selected_projects);
    } else {
        $selected_projects = array();
    }

    // Afficher les outils avec cases à cocher
    echo '<div class="grid grid-cols-6 my-3 gap-4">';
    foreach ($projects as $project) {
        echo '<div>';
            $checked = (in_array($project->ID, $selected_projects)) ? 'checked' : '';
            echo '<input type="checkbox" name="company_projects[]" id="project_'.$project->ID.'" value="' . $project->ID . '" ' . $checked . '/> <label for="project_'.$project->ID.'"> ' . $project->post_title . '</label>';
        echo '</div>';
    }
    echo '</div>';
    }

function render_company_tools_field($post) {
  $selected_tools = get_post_meta($post->ID, 'company_tools', true);

  // Récupérer la liste des outils
  $args = array(
      'post_type' => 'tool',
      'posts_per_page' => -1
  );

  $tools = get_posts($args);

  // Récupérer les outils sélectionnés
  if (!empty($selected_tools) && is_array($selected_tools)) {
      $selected_tools = array_map('intval', $selected_tools);
  } else {
      $selected_tools = array();
  }

  // Afficher les outils avec cases à cocher
  echo '<div class="grid grid-cols-7 my-3 gap-4">';
  foreach ($tools as $tool) {
      echo '<div>';
          $checked = (in_array($tool->ID, $selected_tools)) ? 'checked' : '';
          echo '<input type="checkbox" name="company_tools[]" id="tool_'.$tool->ID.'" value="' . $tool->ID . '" ' . $checked . '/> <label for="tool_'.$tool->ID.'"> ' . $tool->post_title . '</label>';
      echo '</div>';
  }
  echo '</div>';
}

function render_company_custom_fields($post) {
    $start_date = get_post_meta($post->ID, 'company_start_date', true);
    $end_date = get_post_meta($post->ID, 'company_end_date', true);
    $location = get_post_meta($post->ID, 'company_location', true);
    $occupation = get_post_meta($post->ID, 'company_occupation', true);
    $tags = get_post_meta($post->ID, 'company_tags', true);
    $baner_url = get_post_meta($post->ID, 'company_baner_url', true);

    ?>
    <div class="grid grid-cols-6 my-3 ">
        <label for="company_start_date"><?php _e('Start Date:', 'portfolio-api-ressources'); ?></label>
        <input type="date" id="company_start_date" name="company_start_date" value="<?php echo esc_attr($start_date); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="company_end_date"><?php _e('End Date:', 'portfolio-api-ressources'); ?></label>
        <input type="date" id="company_end_date" name="company_end_date" value="<?php echo esc_attr($end_date); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="company_location"><?php _e('Location:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="company_location" name="company_location" value="<?php echo esc_attr($location); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="company_occupation"><?php _e('Occupation:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="company_occupation" name="company_occupation" value="<?php echo esc_attr($occupation); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="company_tags"><?php _e('Tags:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="company_tags" name="company_tags" value="<?php echo esc_attr($tags); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="company_baner_url"><?php _e('Banner URL:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="company_baner_url" name="company_baner_url" value="<?php echo esc_url($baner_url); ?>"><br>
    </div>

    <?php
    // render_company_projects_field($post);
    // render_company_tools_field($post);
}

function save_company_custom_fields($post_id) {
    if (array_key_exists('company_start_date', $_POST)) {
        update_post_meta(
            $post_id,
            'company_start_date',
            sanitize_text_field($_POST['company_start_date'])
        );
    }

    if (array_key_exists('company_end_date', $_POST)) {
        update_post_meta(
            $post_id,
            'company_end_date',
            sanitize_text_field($_POST['company_end_date'])
        );
    }

    if (array_key_exists('company_location', $_POST)) {
        update_post_meta(
            $post_id,
            'company_location',
            sanitize_text_field($_POST['company_location'])
        );
    }

    if (array_key_exists('company_occupation', $_POST)) {
        update_post_meta(
            $post_id,
            'company_occupation',
            sanitize_text_field($_POST['company_occupation'])
        );
    }

    if (array_key_exists('company_tags', $_POST)) {
        update_post_meta(
            $post_id,
            'company_tags',
            sanitize_text_field($_POST['company_tags'])
        );
    }

    if (array_key_exists('company_baner_url', $_POST)) {
        update_post_meta(
            $post_id,
            'company_baner_url',
            esc_url($_POST['company_baner_url'])
        );
    }
    if (array_key_exists('company_projects', $_POST)) {
        update_post_meta(
            $post_id,
            'company_projects',
            $_POST['company_projects']
        );
    }

    if (array_key_exists('company_tools', $_POST)) {
        update_post_meta(
            $post_id,
            'company_tools',
            $_POST['company_tools']
        );
    }
}

// Enregistrement des routes de l'API pour les Companies :
function register_companies_api_routes() {
    register_rest_route('par/v1', 'companies', array(
        'methods' => 'GET',
        'callback' => 'get_companies'
    ));
}

function get_companies($request) {
    $args = array(
        'post_type' => 'company',
        'posts_per_page' => -1
    );

    $companies = get_posts($args);

    $formatted_companies = array();

    foreach ($companies as $company) {

        $formatted_companies[] = format_company($company);
    }

    return $formatted_companies;
}

add_action('rest_api_init', 'register_companies_api_routes');
add_action('add_meta_boxes', 'add_company_custom_fields');
add_action('save_post', 'save_company_custom_fields');
