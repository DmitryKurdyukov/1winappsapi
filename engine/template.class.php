<?php

class template {
    
    function load_html($path_to_file){
        $var = file_get_contents($path_to_file);
        return $var;
    }
    
    function set($macros, $set_var, $var){
        $var = str_ireplace($macros, $set_var, $var);
        return $var;
    }

    function get_block($string){
        $pos1 = strpos($string, '[{');
        $pos2 = strpos($string, '}]');
        $block = substr($string, $pos1+2, $pos2-$pos1 - 2);
        return $block;
    }

    function get_block_custom($string, $macros1, $macros2){
        $pos1 = strpos($string, $macros1);
        $pos2 = strpos($string, $macros2);
        $block = substr($string, $pos1+strlen($macros1), $pos2-$pos1 - strlen($macros2));
        return $block;
    }

    function delete($var_name){
        unset($GLOBALS[$var_name]);
    }

}


?> 