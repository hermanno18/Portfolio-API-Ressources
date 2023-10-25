<?php
function seed_companies() {
    $json_data = file_get_contents(plugin_dir_path(__FILE__) . 'companies.json');
    $companies_data = json_decode($json_data, true);

    if ($companies_data && is_array($companies_data)) {
        foreach ($companies_data as $company) {
            $new_company_id = wp_insert_post(array(
                'post_type' => 'company',
                'post_title' => $company['title'],
                'post_content' => $company['content'],
                'post_status' => 'publish',
            ));

            update_post_meta($new_company_id, 'company_start_date', $company['start_date']);
            update_post_meta($new_company_id, 'company_end_date', $company['end_date']);
            update_post_meta($new_company_id, 'company_location', $company['location']);
            update_post_meta($new_company_id, 'company_occupation', $company['occupation']);
            // Ajoutez les autres champs personnalisés ici...
        }
    }
}
