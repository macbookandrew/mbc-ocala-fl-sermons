<?php

if ( ! defined( 'ABSPATH' ) ) {
    define( 'SHORTINIT', true );
    require( '../../../../wp-load.php' );
}

// Open mysql connection
if (!($mysqli_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, 'memorial_memorial'))) {
    echo "ERROR connecting to host.";
}
