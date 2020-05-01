<?php

/*
 * PHP PGSQL - How to insert rows into PostgreSQL table
 */
 
// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=alr user=postgres password=1234")
        or die('Could not connect: ' . pg_last_error());

//perform the insert using pg_query
$result = pg_query($dbconn, "select * from ln9p_prov");

//dump the result object
var_dump($result);

// Closing connection
pg_close($dbconn);
?>