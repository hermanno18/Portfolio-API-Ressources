<?php

function format_company($company) {

  $company_id = $company->ID;
        
  // Récupérer les projets liés à cette entreprise
  $projects = get_post_meta($company_id, 'company_projects', true);
  $formatted_projects = array();

  foreach ($projects as $project_id) {
      $project = get_post($project_id);

      if($project) $formatted_projects[] = format_project($project);
  }

  // Récupérer les outils liés à cette entreprise
  $tools = get_post_meta($company_id, 'company_tools', true);
  $formatted_tools = array();

  foreach ($tools as $tool_id) {
      $tool = get_post($tool_id);
      $formatted_tools[] = format_tool($tool);
  }

  return array(
      'id' => $company->ID,
      'title' => $company->post_title,
      'start_date' => get_post_meta($company->ID, 'company_start_date', true),
      'end_date' => get_post_meta($company->ID, 'company_end_date', true),
      'location' => get_post_meta($company->ID, 'company_location', true),
      'occupation' => get_post_meta($company->ID, 'company_occupation', true),
      '_tags' => get_post_meta($company->ID, 'company_tags', true),
      'tags' => format_tags(get_post_meta($company->ID, 'company_tags', true)),
      'content' => apply_filters('the_content', $company->post_content), // Ajout du champ content
      'baner_url' => get_post_meta($company->ID, 'company_baner_url', true),
      'featured_image' => get_the_post_thumbnail_url($company->ID, 'full'),
      'projects' => $formatted_projects,
      'tools' => $formatted_tools
  );
}

function format_project($project) {
  $project_id = $project->ID;

  // Récupérer les outils liés à ce projet
  $tools = get_post_meta($project_id, 'project_tools', true);
  $formatted_tools = array();
  if(gettype($tools) != 'string') {
    foreach ($tools as $tool_id) {
        $tool = get_post($tool_id);
        $formatted_tools[] = format_tool($tool);
    }
  }
  $company_id = get_post_meta($project->ID, 'project_company', true);
  $company = get_post($company_id);
  $formatted_company = array(
    'id' => $company->ID,
    'title' => $company->post_title,
    'start_date' => get_post_meta($company->ID, 'company_start_date', true),
    'end_date' => get_post_meta($company->ID, 'company_end_date', true),
    'baner_url' => get_post_meta($company->ID, 'company_baner_url', true),
    'featured_image' => get_the_post_thumbnail_url($company->ID, 'full'),
  );
  return array(
      'id' => $project->ID,
      'title' => $project->post_title,
      'content' => apply_filters('the_content', $project->post_content),
      'source_link' => get_post_meta($project->ID, 'project_source_link', true),
      'view_link' => get_post_meta($project->ID, 'project_view_link', true),
      'tags' => format_tags(get_post_meta($project->ID, 'project_tags', true)),
      'carousel_imgs' => format_tags(get_post_meta($project->ID, 'project_carousel_imgs', true)),
      'company' => $formatted_company,
      'date' => get_post_meta($project->ID, 'project_date', true),
      'featured_image' => get_the_post_thumbnail_url($company->ID, 'full'),
      'tools' => $formatted_tools,
      'is_public' => get_post_meta($project->ID, 'project_is_public', true) === 'on'
  );
}


function format_tool ($tool) {
  return array(
      'id' => $tool->ID,
      'title' => $tool->post_title,
      'img_link' => get_post_meta($tool->ID, 'tool_img_link', true),
      'percentage' => intval(get_post_meta($tool->ID, 'tool_percentage', true)),
      'color' => get_post_meta($tool->ID, 'tool_color', true),
      'group_title_slug' => get_post_meta($tool->ID, 'tool_group_title_slug', true),
      'group_title' => get_post_meta($tool->ID, 'tool_group_title', true),
      'description' => get_post_meta($tool->ID, 'tool_description', true),
  );
}

function format_contact ($contact) {
 return array(
      'id' => $contact->ID,
      'title' => $contact->post_title,
      'value' => get_post_meta($contact->ID, 'contact_value', true),
      'icon' => get_post_meta($contact->ID, 'contact_icon', true),
      'isMain' => get_post_meta($contact->ID, 'contact_isMain', true) === "1",
      'href' => get_post_meta($contact->ID, 'contact_href', true),
      'description' => get_post_meta($contact->ID, 'contact_description', true),
  );
}

function format_tags ($tag_string) {
  return explode(',', $tag_string);
}