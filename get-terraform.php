#!/usr/bin/env php
<?php
require 'vendor/autoload.php';

$token='';
$org="TrullyLollipop";

$client = new \Github\Client();
$client->authenticate($token, null, Github\Client::AUTH_HTTP_TOKEN);

$repos = $client->repositories()->org(
    $org,
    ['limit' => 20000]
);

var_export($repos);

//require_once 'templates/repos.php';

/*
$users = [];
$page = 0;
var_export($client->repositories()->collaborators()->all($org, 'project-alpha'));

*/

$teams_in_repo = array();
$users_in_teams = array();

foreach ($repos as $repo) {
    $teams_in_repo[$repo['name']]['teams'] = $client->repository()->teams($org, $repo['name']);
}

var_export($teams_in_repo);
