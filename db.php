<?php

// connect to the mysql database
$link = mysqli_connect($databaseHost,
                       $databaseUser,
                       $databasePass,
                       $databaseName);
mysqli_set_charset($link,'utf8');
