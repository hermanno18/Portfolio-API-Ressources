<?php
function seed_tools() {
    $json_data = file_get_contents(plugin_dir_path(__FILE__) . 'tools.json');
    $tools_data = json_decode($json_data, true);

    if ($tools_data && is_array($tools_data)) {
        foreach ($tools_data as $tool) {
            $new_tool_id = wp_insert_post(array(
                'post_type' => 'tool',
                'post_title' => $tool['title_fr'],
                'post_status' => 'publish',
            ));

            update_post_meta($new_tool_id, 'tool_img_link', $tool['img_link']);
            update_post_meta($new_tool_id, 'tool_percentage', $tool['percentage']);
            // Ajoutez les autres champs personnalisés ici...
        }
    }
}
