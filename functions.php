<?php 
/**
 * Some Karst globals
 */
define('KARST_PATH', dirname(__FILE__));
define('KARST_URL', get_stylesheet_directory_uri());


/**
 * Debug function - display a variable content
 * 
 * @since 1.0
 */
if(!function_exists('print_var')){
    function print_var($var, $die = false){
        echo '<pre>';
        if (!empty($var))
            print_r($var);
        else
            var_dump($var);
        echo '</pre>';
        
        if($die)
            die;
    }
}