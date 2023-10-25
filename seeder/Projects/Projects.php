<?php
function seed_projects() {
    $json_data = file_get_contents(plugin_dir_path(__FILE__) . 'projects.json');
    $projects_data = json_decode($json_data, true);

    if ($projects_data && is_array($projects_data)) {
        foreach ($projects_data as $project) {
            $new_project_id = wp_insert_post(array(
                'post_type' => 'project',
                'post_title' => $project['title'],
                'post_content' => $project['content'],
                'post_status' => 'publish',
            ));

            update_post_meta($new_project_id, 'project_source_link', $project['source_link']);
            update_post_meta($new_project_id, 'project_view_link', $project['view_link']);
            update_post_meta($new_project_id, 'project_color', $project['color']);
            update_post_meta($new_project_id, 'project_tags', $project['tags']);
            update_post_meta($new_project_id, 'project_date', $project['date']);
            update_post_meta($new_project_id, 'project_is_public', $project['is_public']);
            // Ajoutez les autres champs personnalisés ici...
        }
    }
}
