<?php
function seed_contacts() {
    $json_data = file_get_contents(plugin_dir_path(__FILE__) . 'contacts.json');
    $contacts_data = json_decode($json_data, true);

    if ($contacts_data && is_array($contacts_data)) {
        foreach ($contacts_data as $contact) {
            $new_contact_id = wp_insert_post(array(
                'post_type' => 'contact',
                'post_title' => $contact['title'],
                'post_status' => 'publish',
            ));

            // update_post_meta($new_contact_id, 'contact_href', $contact['href']);
            // update_post_meta($new_contact_id, 'contact_icon', $contact['icon']);
            update_post_meta($new_contact_id, 'contact_value', $contact['value']);
            // Ajoutez les autres champs personnalisés ici...
        }
    }
}
