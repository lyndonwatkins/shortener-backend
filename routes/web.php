<?php

$app->post('/', 'LinkController@store');
$app->get('/', 'LinkController@show');
$app->get('/stats', 'LinkStatsController@show');
