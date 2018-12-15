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

$repos = $client->repositories()->org($org, ['limit' => 300]);

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
 * parent_team_id - how to get this?
 */
$org_teams = array();
$org_teams = $client->organization()->teams()->all($org);
//TODO: get list of nested teams using $org_teams
/**
 * github_repository_collaborator
 * repository
 * username
 * permission
 */
$collaborators_in_repo = array();
foreach ($repos as $repo) {
    $collaborators_in_repo[$repo['name']] = $client->repositories()->collaborators()->all($org, $repo['name']);
}

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
$org_team_membership = array();
foreach ($org_user_members as $user) {
    foreach ($org_teams as $team) {
        $org_team_membership[$team['slug']] = array($user['name'] =>
            $client->organization()->teams()->check($team['id'], $user['name'])['role']);
    }
}

//$members_in_team = array();
//$member_role_in_team = array();
//
//foreach ($teams_in_repo as $key => $value) {
//    foreach ($value as $team) {
////get all members in a team
//        $members_in_team[$team['slug']] = $client->organization()->teams()->members($team['id']);
//    }
//}
////get member team role (member or maintainer)
//
//foreach ($teams_in_repo as $key => $value) {
//    foreach ($value as $team) {
//        foreach ($members_in_team as $k => $v) {
//            foreach ($v as $member) {
//                if ($member['login'] != null) {
//                    $member_role_in_team[$team['slug']] = $client->organization()->teams()->check(
//                        $team['id'],
//                        $member['login']
//                    );
//                }
//            }
//        }
//    }
//}

//require_once 'templates/repos.php';
//require_once 'templates/teams.php';
//require_once 'templates/repo-collaborators.php';
//require_once 'templates/team-members.php';
