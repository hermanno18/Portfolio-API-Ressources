<?php

require_once plugin_dir_path(__FILE__) . '_formatters.php';

// Enregistrement du Custom Post Type pour les Projets (`project`) :
function register_project_post_type() {
    $labels = array(
        'name'               => _x('Projects', 'Post type general name', 'portfolio-api-ressources'),
        'singular_name'      => _x('Project', 'Post type singular name', 'portfolio-api-ressources'),
        'menu_name'          => _x('Projects', 'Admin Menu text', 'portfolio-api-ressources'),
        'name_admin_bar'     => _x('Project', 'Add New on Toolbar', 'portfolio-api-ressources'),
        'add_new'            => _x('Add New', 'Project', 'portfolio-api-ressources'),
        'add_new_item'       => __('Add New Project', 'portfolio-api-ressources'),
        'new_item'           => __('New Project', 'portfolio-api-ressources'),
        'edit_item'          => __('Edit Project', 'portfolio-api-ressources'),
        'view_item'          => __('View Project', 'portfolio-api-ressources'),
        'all_items'          => __('All Projects', 'portfolio-api-ressources'),
        'search_items'       => __('Search Projects', 'portfolio-api-ressources'),
        'parent_item_colon'  => __('Parent Projects:', 'portfolio-api-ressources'),
        'not_found'          => __('No projects found.', 'portfolio-api-ressources'),
        'not_found_in_trash' => __('No projects found in Trash.', 'portfolio-api-ressources')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Description.', 'portfolio-api-ressources'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'projects'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'taxonomies'         => array(),
        'menu_icon'          => 'dashicons-portfolio' // Utilisez l'icône de votre choix
    );

    register_post_type('project', $args);
}

add_action('init', 'register_project_post_type');

// Ajout des champs personnalisés pour Project
function add_project_custom_fields() {
    add_meta_box(
        'project_custom_fields',
        __('Project meta informations', 'portfolio-api-ressources'),
        'render_project_custom_fields',
        'project',
        'normal',
        'default'
    );
    add_meta_box(
        'project_company',
        __('Select Company whre you built this project', 'portfolio-api-ressources'),
        'render_project_company_field',
        'project',
        'normal',
        'default'
    );

    add_meta_box(
        'project_tools',
        __('Select the Tools you used for this project', 'portfolio-api-ressources'),
        'render_project_tools_field',
        'project',
        'normal',
        'default'
    );
}

function render_project_company_field($post) {
    $selected_company = get_post_meta($post->ID, 'project_company', true);

    // Récupérer la liste des entreprises
    $args = array(
        'post_type' => 'company',
        'posts_per_page' => -1
    );

    $companies = get_posts($args);

    // Afficher la liste déroulante des entreprises
    echo '<div class="grid grid-cols-4 my-3 gap-4">';
        echo '<select id="project_company" name="project_company">';
        foreach ($companies as $company) {
            $selected = ($company->ID == $selected_company) ? 'selected' : '';
            echo '<option value="' . $company->ID . '" ' . $selected . '>' . $company->post_title . '</option>';
        }
        echo '</select><br>';
    echo '</div>';
}

function render_project_tools_field($post) {
    $selected_tools = get_post_meta($post->ID, 'project_tools', true);

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
            echo '<input type="checkbox" name="project_tools[]" id="tool_'.$tool->ID.'" value="' . $tool->ID . '" ' . $checked . '/> <label for="tool_'.$tool->ID.'"> ' . $tool->post_title . '</label>';
        echo '</div>';
    }
    echo '</div>';
}

function render_project_custom_fields($post) {
    $source_link = get_post_meta($post->ID, 'project_source_link', true);
    $view_link = get_post_meta($post->ID, 'project_view_link', true);
    $tags = get_post_meta($post->ID, 'project_tags', true);
    // $main_img_link = get_post_meta($post->ID, 'project_main_img_link', true);
    $carousel_imgs = get_post_meta($post->ID, 'project_carousel_imgs', true);
    $date = get_post_meta($post->ID, 'project_date', true);
    $is_public = get_post_meta($post->ID, 'project_is_public', true);

    // Ajoutez les autres champs personnalisés ici...

    ?>
    <div class="grid grid-cols-6 my-3">
        <label for="project_source_link"><?php _e('Source Link:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="project_source_link" name="project_source_link" value="<?php echo esc_url($source_link); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="project_view_link"><?php _e('View Link:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="project_view_link" name="project_view_link" value="<?php echo esc_url($view_link); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="project_tags"><?php _e('Tags:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="project_tags" name="project_tags" value="<?php echo esc_attr($tags); ?>"><br>
    </div>

    <!-- <label for="project_main_img_link"><?php // _e('Main Image Link:', 'portfolio-api-ressources'); ?></label>
    <input type="text" id="project_main_img_link" name="project_main_img_link" value="<?php // echo esc_url($main_img_link); ?>"><br> -->
    <div class="grid grid-cols-6 my-3 ">
        <label for="project_carousel_imgs"><?php _e('Carousel Images Links:', 'portfolio-api-ressources'); ?></label>
        <input type="text" id="project_carousel_imgs" name="project_carousel_imgs" value="<?php echo esc_attr($carousel_imgs); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="project_date"><?php _e('Date:', 'portfolio-api-ressources'); ?></label>
        <input type="date" id="project_date" name="project_date" value="<?php echo esc_attr($date); ?>"><br>
    </div>

    <div class="grid grid-cols-6 my-3 ">
        <label for="project_is_public"><?php _e('Is Public:', 'portfolio-api-ressources'); ?></label>
        <input type="checkbox" id="project_is_public" name="project_is_public" <?php checked($is_public, 'on'); ?>><br>
    </div>


    <!-- Ajoutez les champs personnalisés supplémentaires ici... -->
    <?php
}

function save_project_custom_fields($post_id) {
    if (array_key_exists('project_source_link', $_POST)) {
        update_post_meta(
            $post_id,
            'project_source_link',
            esc_url($_POST['project_source_link'])
        );
    }

    if (array_key_exists('project_view_link', $_POST)) {
        update_post_meta(
            $post_id,
            'project_view_link',
            esc_url($_POST['project_view_link'])
        );
    }

    if (array_key_exists('project_tags', $_POST)) {
        update_post_meta(
            $post_id,
            'project_tags',
            sanitize_text_field($_POST['project_tags'])
        );
    }

    // if (array_key_exists('project_main_img_link', $_POST)) {
    //     update_post_meta(
    //         $post_id,
    //         'project_main_img_link',
    //         esc_url($_POST['project_main_img_link'])
    //     );
    // }

    if (array_key_exists('project_carousel_imgs', $_POST)) {
        update_post_meta(
            $post_id,
            'project_carousel_imgs',
            sanitize_text_field($_POST['project_carousel_imgs'])
        );
    }

    if (array_key_exists('project_company', $_POST)) {
        update_post_meta(
            $post_id,
            'project_company',
            sanitize_text_field($_POST['project_company'])
        );
    }

    if (array_key_exists('project_date', $_POST)) {
        update_post_meta(
            $post_id,
            'project_date',
            sanitize_text_field($_POST['project_date'])
        );
    }

    if (array_key_exists('project_tools', $_POST)) {
        update_post_meta(
            $post_id,
            'project_tools',
            $_POST['project_tools']
        );
    }

    if (array_key_exists('project_is_public', $_POST)) {
        update_post_meta(
            $post_id,
            'project_is_public',
            $_POST['project_is_public'] === 'on' ? 'on' : 'off'
        );
    }

    // Ajoutez les autres champs personnalisés ici...
}

// Enregistrement des routes de l'API pour les Projets :
function register_projects_api_routes() {
    register_rest_route('par/v1', 'projects', array(
        'methods' => 'GET',
        'callback' => 'get_projects'
    ));
}

function get_projects($request) {
    $args = array(
        'post_type' => 'project',
        'posts_per_page' => -1
    );

    $projects = get_posts($args);

    $formatted_projects = array();

    foreach ($projects as $project) {
        // if(get_post_meta($project->ID, 'project_is_public', true) === 'on') //On affiche que les projects publiques
            $formatted_projects[] = format_project($project);
    }

    return $formatted_projects;
}

function register_single_project_api_route() {
  register_rest_route('par/v1', 'projects/(?P<id>\d+|[-\w]+)', array(
      'methods' => 'GET',
      'callback' => 'get_single_project'
  ));
}

function get_single_project($request) {
  $id = $request->get_param('id');
  $project = null;

  if (is_numeric($id)) {
      $project = get_post((int)$id);
  } else {
      $project = get_page_by_path($id, OBJECT, 'project');
  }

  if (!$project || $project->post_type !== 'project') {
      return new WP_Error('project_not_found', 'Project not found', array('status' => 404));
  }

  return format_project($project);
}

add_action('rest_api_init', 'register_single_project_api_route');
add_action('rest_api_init', 'register_projects_api_routes');
add_action('add_meta_boxes', 'add_project_custom_fields');
add_action('save_post', 'save_project_custom_fields');

