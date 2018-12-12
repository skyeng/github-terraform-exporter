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
$member_role_in_team = array();

foreach ($repos as $repo) {
    $teams_in_repo[$repo['name']] = $client->repository()->teams($org, $repo['name']);
    $collaborators_in_repo[$repo['name']] = $client->repositories()->collaborators()->all($org, $repo['name']);
}

foreach ($teams_in_repo as $key => $value) {
    foreach ($value as $team) {
//get all members in a team
        $members_in_team[$team['slug']] = $client->organization()->teams()->members($team['id']);
    }
}
//get member team role (member or maintainer)

foreach ($teams_in_repo as $key => $value) {
    foreach ($value as $team) {
        foreach ($members_in_team as $k => $v) {
            foreach ($v as $member) {
                if ($member['login'] != null) {
                    $member_role_in_team[$team['slug']] = $client->organization()->teams()->check(
                        $team['id'],
                        $member['login']
                    );
                }
            }
        }
    }
}

//require_once 'templates/repos.php';
//require_once 'templates/teams.php';
//require_once 'templates/repo-collaborators.php';
//require_once 'templates/team-members.php';

//var_dump($client->organization()->teams()->check('3027401', 'slastique'));
