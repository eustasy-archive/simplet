<?php

$Cookie['Prefix'] = str_replace( '.', '_', $Place['host']);
$Cookie['Session'] = $Cookie['Prefix'].'_session';
$Cookie['CSRF'] = $Cookie['Prefix'].'_csrf_protection';