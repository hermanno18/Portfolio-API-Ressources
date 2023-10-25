<?php
function seed_tools() {
    $json_data = file_get_contents(plugin_dir_path(__FILE__) . 'tools.json');
    $tools_data = json_decode($json_data, true);

    if ($tools_data && is_array($tools_data)) {
        foreach ($tools_data as $tool) {
            $new_tool_id = wp_insert_post(array(
                'post_type' => 'tool',
                'post_title' => $tool['title'],
                'post_status' => 'publish',
            ));

            update_post_meta($new_tool_id, 'tool_img_link', $tool['img_link']);
            update_post_meta($new_tool_id, 'tool_percentage', $tool['percentage']);
            update_post_meta($new_tool_id, 'tool_color', $tool['color']);
            update_post_meta($new_tool_id, 'tool_group_title', $tool['group_title']);
            update_post_meta($new_tool_id, 'tool_description', $tool['description']);
            update_post_meta($new_tool_id, 'tool_group_title_slug', sanitize_title($tool['group_title']));
            // Ajoutez les autres champs personnalisés ici...
        }
    }
}
