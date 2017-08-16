<?php

// connect to the mysql database
$databaseHost = 'stgdb.junemedia.com';
$databaseUser = 'r4lapp01';
$databasePass = '2%%s2dfTEVjs';
$databaseName = 'r4l_stage';
$link = mysqli_connect('stgdb.junemedia.com',
                       'r4lapp01',
                       '2%%s2dfTEVjs',
                       'r4l_stage');
mysqli_set_charset($link,'utf8');
