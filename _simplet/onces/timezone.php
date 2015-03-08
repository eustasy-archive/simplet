<?php

////	Set the Default Timezone.
// Note: GMT is deprecated. Use UTC instead.
// TODO Add $Sitewide['Timezone'] and honor it.
date_default_timezone_set('UTC');

$Time['Now']    = time();
$Time['15mins'] = $Time['Now'] + 900;
$Time['1hour']  = $Time['Now'] + 3600;
$Time['1month'] = $Time['Now'] + 2419200;