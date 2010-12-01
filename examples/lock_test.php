#!/usr/bin/php -ddisplay_errors=on -derror_reporting=E_ALL
<?php

require_once(dirname(__FILE__) .'/../lib/File_NFSLock.class.php');

###  Read the Command-line options
$script = array_shift( $argv ); # the script name
$file = array_shift( $argv );
$i = abs(floor( array_shift( $argv ) ));
if ( empty( $i ) ) { echo "Usage: $script <filename> <increment>\n"; exit; }

foreach ( range(1, $i) as $ii ) {
    ###  Get an exclusive File_NFSLock...
    $lock = new File_NFSLock($file, NFS_LOCK_EX);
    if ( ! $lock->lock_success ) echo "Ouch!\n"; # blocking lock (Exclusive)

    ### read the count and spit it out
    $FH = fopen($file, "r+");
    $count = fread( $FH, 4096 );
    $count++;

    echo "[". getmypid() ."] I win with [$count]\r";

    ###  Update the count
    fseek($FH, 0, SEEK_SET);
    fwrite($FH, $count);
    fclose($FH);
    unset( $lock ); # destroy the lock
}
