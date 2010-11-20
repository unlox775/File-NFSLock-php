#!/usr/bin/php -ddisplay_errors=on -derror_reporting=E_ALL
<?php

require_once(dirname(__FILE__) .'/../lib/File_NFSLock.class.php');

###  Read the Command-line options
$script = array_shift( $argv ); # the script name
$file = array_shift( $argv );
$i = abs(floor( array_shift( $argv ) ));
if ( empty( $i ) ) { echo "Usage: $script <filename> <increment>\n"; exit; }

#`touch $file`;
foreach ( range(1,$i) as $count ) {
    ###  Get an exclusive File_NFSLock...
    $db_file_lock = new File_NFSLock($file, LOCK_EX);

    echo "[". getmypid() ."] I win with [$count]\r";

    file_put_contents($file,"$count\n", FILE_APPEND);
    unset( $db_file_lock ); # destoy
}