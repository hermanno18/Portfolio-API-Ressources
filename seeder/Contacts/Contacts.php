<?php
function seed_contacts() {
    $json_data = file_get_contents(plugin_dir_path(__FILE__) . 'contacts.json');
    $contacts_data = json_decode($json_data, true);

    if ($contacts_data && is_array($contacts_data)) {
        foreach ($contacts_data as $contact) {
            $new_contact_id = wp_insert_post(array(
                'post_type' => 'contact',
                'post_title' => $contact['title_fr'],
                'post_status' => 'publish',
            ));

            update_post_meta($new_contact_id, 'contact_value_fr', $contact['value_fr']);
            update_post_meta($new_contact_id, 'contact_value_en', $contact['value_en']);
            // Ajoutez les autres champs personnalisés ici...
        }
    }
}
