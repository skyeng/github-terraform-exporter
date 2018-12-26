#!/usr/bin/env php
<?php
require 'vendor/autoload.php';

$token = '';
$org = "skyeng";

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

$paginator = new Github\ResultPager($client);
$org_repos_requst_parameters = array($org);
$org_repositories = $paginator->fetchAll($client->api('organization'), 'repositories', $org_repos_requst_parameters);

/**
 * github_membership:
 * username - login
 * role - member, admin
 */
$org_user_members_parameters = array($org, null, 'all', 'member');
$org_user_members = $paginator->fetchAll($client->api('members'), 'all', $org_user_members_parameters);
$org_user_admins_parameters = array($org, null, 'all', 'admin');
$org_user_admins = $paginator->fetchAll($client->api('members'), 'all', $org_user_admins_parameters);

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
$paginator_preview = new Github\ResultPager($client_preview);
$parameters = array($org);
$org_teams = $paginator_preview->fetchAll($client_preview->api('teams'), 'all', $parameters);

/**
 * github_repository_collaborator
 * repository
 * username
 * permission
 */
$collaborators_in_repo = array();
foreach ($org_repositories as $repo) {
    $collaborators_in_repo[$repo['name']] = $client->repositories()->collaborators()->all($org, $repo['name']);
}

/**
 * github_team_repository:
 * team_id
 * repository
 * permission [0:[permissions:pull,permissions:push,permissions:admin],1:[]]
 */

$team_repositories = array();
$team_repositories_with_permission = array();
foreach ($org_teams as $team) {
    foreach ($paginator->fetchAll($client->api('teams'), 'repositories', $team['id']) as $rep) {
        $team_repositories_with_permission += array($rep['name'] => $rep['permissions']);
        echo "terraform import github_team_repository.team_" . $team['slug'] .
            "_repo_" . $rep['name'] . " " . $team['id'] . ":" . $rep['name'] . "\n";
    }
    $team_repositories[$team['slug']] = $team_repositories_with_permission;
    $team_repositories_with_permission = array();
}

$org_team_members = array();
$org_team_maintainers = array();
$team_maintainers = array();
$team_members = array();
/**
 * These two foreach will not work without the following hack in vendor/knplabs/github-api/lib/Github/Api/Organization/Teams.php
 *     public function members($team, $role)
 * {
 * $params['role'] = $role;
 * return $this->get('/teams/'.rawurlencode($team).'/members', $params);
 * //        return $this->get('/teams/'.rawurlencode($team).'/members');
 * }
 * I've done it as members method doesn't support role filters: https://developer.github.com/v3/teams/members/#list-team-members
 */
foreach ($org_teams as $team) {
    try {
        $parameters = array($team['id'], 'member');
        $team_members += $paginator_preview->fetchAll($client_preview->api('teams'), 'members', $parameters);
    } catch (\Github\Exception\RuntimeException $exception) {
//            echo $user['login'] . " not in a " . $team['name'] . "\n";
    }
    $org_team_members[$team['slug']] = $team_members;
    $team_members = array();
}

foreach ($org_teams as $team) {
    try {
        $parameters = array($team['id'], 'maintainer');
        $team_maintainers = $paginator_preview->fetchAll($client_preview->api('teams'), 'members', $parameters);
    } catch (\Github\Exception\RuntimeException $exception) {
//            echo $user['login'] . " not in a " . $team['name'] . "\n";
    }
    $org_team_maintainers[$team['slug']] = $team_maintainers;
    $team_maintainers = array();
}

//minify/cleanup $org_team_maintainers
$tmp_team_members = array();
foreach ($org_team_maintainers as $team => $users) {
    foreach ($users as $user) {
        array_push($tmp_team_members, $user['login']);
    }
    $team_maintainers[$team] = $tmp_team_members;
    $tmp_team_members = array();

}

foreach ($org_teams as $team) {
    echo "terraform import github_team.team_" . $team['slug'] . " " .
        $team['id'] . "\n";
}

foreach ($org_repositories as $repo) {
    echo "terraform import github_repository." . $repo['name'] . " " .
        $repo['name'] . "\n";
}

foreach ($collaborators_in_repo as $repo => $value) {
    foreach ($value as $collaborator) {
        echo "terraform import github_repository_collaborator." . $repo . "_" .
            $collaborator['login'] . "_collaborator " . $repo . ":" . $collaborator['login'] . "\n";
    }
}

foreach ($org_user_admins as $admin) {
    echo "terraform import github_membership.membership_for_" . $admin['login'] . " " .
        $org . ":" . $admin['login'] . "\n";
}

foreach ($org_user_members as $user) {
    echo "terraform import github_membership.membership_for_" . $user['login'] . " " .
        $org . ":" . $user['login'] . "\n";
}

foreach ($org_team_members as $team => $users) {
    foreach ($users as $user) {
        echo "terraform import github_team_membership.member " . $team . ":" . $user . "\n";
    }
}

foreach ($team_maintainers as $team => $users) {
    foreach ($users as $user) {
        echo "terraform import github_team_membership.member " . $team . ":" . $user . "\n";
    }
}

require_once 'templates/repos.php';
require_once 'templates/repo-collaborators.php';
require_once 'templates/org-users.php';
require_once 'templates/teams.php';
require_once 'templates/team-members.php';
