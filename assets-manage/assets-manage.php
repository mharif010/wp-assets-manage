<?php
/**
 * Plugin Name:       AssetsManage
 * Plugin URI:        
 * Description:       Handle assets path with this plugin.
 * Version:           1.10.3
 * Author:            mhArif
 * Author URI:        https://mharif.com/
 * License:           GPL v2 or later
 * Text Domain:       assetsmanage
 * Domain Path:       /languages
 */

define("ASM_DIR", plugin_dir_url(__FILE__));
define("ASM_PUBLIC_ASSETS", plugin_dir_url(__FILE__)."public/");
define("ASM_ADMIN_ASSETS", plugin_dir_url(__FILE__)."admin/");

class AssetsManage{

    private $version;

    function __construct(){

        $this->version = time();

        add_action('plugins_loaded',array($this,'load_textdomain'));
        add_action('wp_enqueue_scripts',array($this,'load_front_assets'));
        add_action('admin_enqueue_scripts',array($this,'load_admin_assets'));

        add_action('init', array($this,'asm_init'));

    }

    function load_textdomain(){
        load_plugin_textdomain('assetsmanage', false, ASM_DIR."/languages");
    }

    function load_front_assets(){
        wp_enqueue_style('asm-main-css', ASM_PUBLIC_ASSETS."css/main.css", null, $this->version);
        wp_enqueue_script('asm-main-js', ASM_PUBLIC_ASSETS."js/main.js", array('jquery', 'asm-other-js'), $this->version, true);
        wp_enqueue_script('asm-other-js', ASM_PUBLIC_ASSETS."js/other.js", array('jquery', 'asm-more-js'), $this->version, true);
        wp_enqueue_script('asm-more-js', ASM_PUBLIC_ASSETS."js/more.js", array('jquery'), $this->version, true);

        //js all files enqueue by array
        // $js_files = array(
        //     'asm-main-js' => array('path'=>ASM_PUBLIC_ASSETS."js/main.js", 'dep'=>array('jquery', 'asm-other-js')),
        //     'asm-other-js' => array('path'=>ASM_PUBLIC_ASSETS."js/other.js", 'dep'=>array('jquery', 'asm-more-js')),
        //     'asm-more-js' => array('path'=>ASM_PUBLIC_ASSETS."js/more.js", 'dep'=>array('jquery')),
        // );
        // foreach($js_files as $handle=>$fileinfo){
        //     wp_enqueue_script($handle,$fileinfo['path'],$fileinfo['dep'],$this->version,true);
        // }

        $localData = array(
            'name' => 'john doe',
            'address'  => 'london'
        );
        wp_localize_script('asm-main-js', 'userdata', $localData);
    }

    function load_admin_assets($screen){
        $_screen = get_current_screen();   
        if('edit.php' == $screen && 'page' == $_screen->post_type ){
            wp_enqueue_script('asm-admin-js', ASM_ADMIN_ASSETS."js/admin.js", array(), $this->version, true );
        }
    }

    function asm_init(){
        wp_deregister_style('font-awesome-css');
        wp_register_style('font-awesome-css','//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css');

        //wp_deregister_script('asm-other-js');
        //wp_register_script('asm-other-js', '//cdnjs.cloudflare.com/ajax/libs/exams.js', null,'1.0',true);
    }


}
new AssetsManage();