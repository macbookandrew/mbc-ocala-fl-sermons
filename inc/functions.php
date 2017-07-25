<?php

defined( 'ABSPATH' ) or die( 'No access allowed' );

/**
 * MySQLize string
 *
 * @lastmodifiedBy pre-2013 site author
 *
 * @param  string $string input string
 * @return string mysq-ized string
 */
function mysqlize($mysqli_connection, $string) {
    if (get_magic_quotes_gpc()) {
        if (ini_get('magic_quotes_sybase')) {
            $string = str_replace("''", "'", $string);
        } else {
            $string = stripslashes($string);
        }
    }

    return mysqli_real_escape_string($mysqli_connection,$string);
}
