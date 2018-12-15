#!/usr/bin/env php
<?php
require 'vendor/autoload.php';

$token = '';
$org = "TrullyLollipop";

$client = new \Github\Client();
$client->authenticate($token, null, Github\Client::AUTH_HTTP_TOKEN);

/**
 * github_repository
 * name
 * description
 * private
 * has_issues
 * has_projects
 * has_wiki
 * has_downloads
 * archived
 * topics (map)
 */

$repos = $client->repositories()->org($org, ['limit' => 400]);

/**
 * github_membership:
 * username - login
 * role - member, admin
 */
$org_user_members = $client->organization()->members()->all($org, null, 'all', 'member');
$org_user_admins = $client->organization()->members()->all($org, null, 'all', 'admin');

/**
 * github_team:
 * name
 * description
 * privacy
 * parent_team_id
 */
$org_teams = array();
// i need to create a new client object to use different api version as list child teams is an experimental feature
$client_preview = new \Github\Client(null, 'hellcat-preview', null);
$client_preview->authenticate($token, null, Github\Client::AUTH_HTTP_TOKEN);
$org_teams = $client_preview->organization()->teams()->all($org);
/**
 * github_repository_collaborator
 * repository
 * username
 * permission
 */
//$collaborators_in_repo = array();
//foreach ($repos as $repo) {
//    $collaborators_in_repo[$repo['name']] = $client->repositories()->collaborators()->all($org, $repo['name']);
//}

/**
 * github_team_repository:
 * team_id
 * repository
 * permission [0:[permissions:pull,permissions:push,permissions:admin],1:[]]
 */

$team_repositories = array();
foreach ($org_teams as $team) {
    foreach ($client->teams()->repositories($team['id']) as $rep) {
        $team_repositories[$team['slug']] = array($rep['name'] => $rep['permissions']);
    }
}

/**
 * просто перечисление пользователей
 * github_team_membership:
 * team_id
 * username
 * role
 */
//$org_team_membership = array();
//foreach ($org_user_members as $user) {
//    foreach ($org_teams as $team) {
//        $org_team_membership[$team['slug']] = array($user['login'] =>
//            $client->organization()->teams()->check($team['id'], $user['login'])['role']);
//    }
//}

//require_once 'templates/org-users.php';
//require_once 'templates/repos.php';
require_once 'templates/teams.php';
//require_once 'templates/repo-collaborators.php';
//require_once 'templates/team-members.php';
