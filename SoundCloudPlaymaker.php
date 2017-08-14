<?php
  /*
   Plugin Name: Soundcloud Auto Playmaker
   Plugin URI: http://www.fergalmoran.com/code/wp-soundcloud-auto-playmaker
   Description: Automatically generates soundcloud players for your site. 
   Version: 0.8
   Author: Fergal Moran
   Author URI: http://fergalmoran.com/
  /* ----------------------------------------------*/
   ###Class: 

    require_once('includes/_scAdmin.php');
    require_once('includes/_scWidget_Interface.php');

    function initPaginate() {
        $pluginDir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
        $pluginDirRelative = '/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
        wp_enqueue_script('jquery');
        wp_enqueue_script('pager', $pluginDir . '/js/pager.js');
        wp_enqueue_script('widgetloader', $pluginDir . '/js/widget.js');
    }
    function registerStyles(){
        //inject the necessary style sheets
        $pluginDir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
        $pluginDirRelative = '/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
        $widgetStyle = $pluginDir . '/css/soundcloud-auto-playmaker.css';
        $pagerStyle = $pluginDir . '/css/Pager.css';
        wp_register_style('sc-widget', $widgetStyle);
        wp_enqueue_style( 'sc-widget');
        wp_register_style('sc-pager', $pagerStyle);
        wp_enqueue_style( 'sc-pager');
    }
    function registerAdminStyles(){
        //inject the necessary style sheets and javascript
        $pluginDir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
        wp_register_style('sc-farbtastic', $pluginDir . '/css/farbtastic.css');
        wp_enqueue_style('sc-farbtastic');
    }
        
    function registerAdminScripts(){
        $pluginDir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
        wp_enqueue_script('jquery');
        wp_enqueue_script('sc-admin-farb', $pluginDir . '/js/farbtastic.js');
        wp_enqueue_script('sc-admin-loader', $pluginDir . '/js/admin.js');
    }

    function registerGlobalOptionsMenu(){
        //register the global options form
        register_setting('sc-playmaker-global-options-base', 'sc-playmaker-global-options');
        $scAdminPage = add_theme_page(
            'Soundcloud Playmaker Options', 
            'Soundcloud Playmaker', 
            'manage_options', 
            __FILE__,  
            'scPluginOptions');
        add_action("admin_print_scripts-$scAdminPage", "registerAdminScripts");
        add_action("admin_print_styles-$scAdminPage", "registerAdminStyles");
    }
 
   function scPluginOptions(){
        if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        if (isset($_POST['sc-global-save'])){
            sc_PlaymakerAdmin::SaveOptions($_POST);
        }

        sc_PlaymakerAdmin::CreateOptionsMenu();
    }
    add_action('widgets_init', 'initPaginate');
    add_action('widgets_init', 'registerStyles');
    add_action('widgets_init', create_function('', 'return register_widget("sc_PlaymakerWidget");'));
    add_action('admin_menu', 'registerGlobalOptionsMenu');
?>
