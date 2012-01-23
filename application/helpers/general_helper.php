<?php
/**
 * Dump a variable, wrapped in <pre> tags.
 * @param mixed $var The variable to dump.
 * @param string $label (optional) A label to prepend the dump with.  
 * @param boolean $echo (optional) Whether to echo the variable or return it
 * @global
 * @return mixed Return if $echo is passed as FALSE  
 * @author Joost van Veen
 */
function dump ($var, $label = 'Dump', $echo = TRUE)
{
    // Store dump in variable 
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    
    // Add formatting
    $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
    $output = '<pre style="background: #e6e7e8; color: #000; border: 1px solid #ccc; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
    
    // Output
    if ($echo == TRUE) {
        echo $output;
    }
    else {
        return $output;
    }
}
