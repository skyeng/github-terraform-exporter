#!/usr/bin/env php
<?php
require 'vendor/autoload.php';

$token = '';
$org = "TrullyLollipop";

$client = new \Github\Client();
$client->authenticate($token, null, Github\Client::AUTH_HTTP_TOKEN);

$repos = $client->repositories()->org(
    $org,
    ['limit' => 20000]
);

$collaborators_in_repo = array();
$teams_in_repo = array();
$members_in_team = array();

foreach ($repos as $repo) {
    $teams_in_repo[$repo['name']] = $client->repository()->teams($org, $repo['name']);
    $collaborators_in_repo[$repo['name']] = $client->repositories()->collaborators()->all($org,$repo['name']);
}

foreach ($teams_in_repo as $key=>$value){
    foreach ($value as $team){
        $members_in_team[$team['slug']] = $client->team()->members($team['name'])->all($org);
    }
}

$client->teams()->members('SomeTeam');

require_once 'templates/repos.php';
require_once 'templates/teams.php';
require_once 'templates/repo-collaborators.php';

