<?php
/**
 * Plugin Name: Portfolio API Ressources
 * Version: 1.0.0
 * Description: Supercharge your WordPress backend with our powerful API plugin! This versatile tool empowers developers to seamlessly manage projects, companies, and essential tools directly through the WordPress admin panel. Effortlessly integrate and organize your professional portfolio data, allowing for easy retrieval and presentation on your website or app. Collaborate with companies, showcase your skillset, and highlight your achievements - all with the convenience of WordPress. Elevate your development workflow with our robust API plugin!
 * Author: Hermann FOKOU
 * Author URI: https://hermann-fokou.com 
 * Text Domain: portfolio-api-ressources
 */



 // Oh sorry but somme of my comments are in french
/*
  LE CODE DU PLUGIN COMMENCE ICI
*/

// Tool : {
//   - id
//   - title String required unique
//   - img_link String required unique
//   - percentage Int nullable
//   - color String nullable
//   - group_title_slug String nullable
//   - group_title String nullable
//   - description text nullable
// }

// Contact : {
//   - id
//   - title String required unique
//   - value String required
//   - icon String unique nullable
//   - isMain Boolean required default(false) 
//   - href required unique
//   - description nullable
// // }

// Project : {
//   - id
//   - title          String required unique
//   - content        RichText
//   - source_link    String nullable
//   - view_link      String nullable
//   - tags           Array of string
//   - main_img_link  String or Media required unique
//   - carousel_imgs  Array  of String or Media
//   - company        reference to  Company
//   - date           Date
//   - tools          Array of Tools
//   - is_public ?    Boolean default (false)
// }

// Company : {
//   - id
//   - title          String required unique
//   - content        RichText
//   - start_date     Date
//   - end_date       Date
//   - location       String nullable
//   - occupation     String required
//   - tools          Array or Tool
//   - projects       Array of Project
//   - tags           Array of String
//   - baner_url      String required unique
// }

require_once plugin_dir_path(__FILE__) . 'types/Project.php';
require_once plugin_dir_path(__FILE__) . 'types/Company.php';
require_once plugin_dir_path(__FILE__) . 'types/contact.php';
require_once plugin_dir_path(__FILE__) . 'types/tool.php';

/**
 * Fonctions à exécuter lors du chargement du plugin 
 */
function activate_portfolio_api_resources_plugin() {
  include(plugin_dir_path(__FILE__) . 'seeder/index.php');
}

register_activation_hook(__FILE__, 'activate_portfolio_api_resources_plugin');