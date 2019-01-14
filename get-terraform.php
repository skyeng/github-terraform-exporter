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
$org_repos_request_parameters = array($org);
$org_repositories = $paginator->fetchAll($client->api('organization'), 'repositories', $org_repos_request_parameters);

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


// Topics for a repository
$repo_topics = array();
foreach ($org_repositories as $repo) {
    $parameters = array($org, $repo['name']);
    foreach ($paginator_preview->fetchAll($client_preview->api('repositories'), 'topics', $parameters) as $topic) {
        $repo_topics[$repo['name']] = $topic;
    }
}
/*
 * To get protected branches I have modified Repo\brances() method
 *     public function branches($username, $repository, $branch = null, array $params = [])
    {
        $url = '/repos/'.rawurlencode($username).'/'.rawurlencode($repository).'/branches';
        if (null !== $branch) {
            $url .= '/'.rawurlencode($branch);
        }

        return $this->get($url, $params);
    }
 */
// TODO: add these data to repo template
$protected_branches = array();
foreach ($org_repositories as $repo) {
    $parameters = array($org, $repo['name'], null, array("protected" => "true"));
    $protected_branches[$repo['name']] = $paginator->fetchAll($client->api('repositories'), 'branches', $parameters);
}

// TODO: add github branch protection. See https://www.terraform.io/docs/providers/github/r/branch_protection.html
// TODO: add webhooks for a repo: https://www.terraform.io/docs/providers/github/r/repository_webhook.html
/**
 * github_repository_collaborator
 * repository
 * username
 * permission
 */
$collaborators_in_repo = array();
$collaborator_api = $client->repositories()->collaborators();
foreach ($org_repositories as $repo) {
    $parameters = array($org, $repo['name'], array("affiliation" => "direct"));
    $collaborators_in_repo[$repo['name']] = $paginator->fetchAll($collaborator_api, 'all', $parameters);
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
    $parameters = array($team['id']);
    foreach ($paginator->fetchAll($client->api('teams'), 'repositories', $parameters) as $rep) {
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
 * These two foreach will not work without the following hack in
 * vendor/knplabs/github-api/lib/Github/Api/Organization/Teams.php
 *     public function members($team, $role)
 * {
 * $params['role'] = $role;
 * return $this->get('/teams/'.rawurlencode($team).'/members', $params);
 * //        return $this->get('/teams/'.rawurlencode($team).'/members');
 * }
 * I've done it as members method
 * doesn't support role filters: https://developer.github.com/v3/teams/members/#list-team-members
 */
foreach ($org_teams as $team) {
    try {
        $parameters = array($team['id'], 'member');
        $team_members += $paginator->fetchAll($client->api('teams'), 'members', $parameters);
    } catch (\Github\Exception\RuntimeException $exception) {
//            echo $user['login'] . " not in a " . $team['name'] . "\n";
    }
    $org_team_members[$team['slug']] = $team_members;
    $team_members = array();
}

foreach ($org_teams as $team) {
    try {
        $parameters = array($team['id'], 'maintainer');
        $team_maintainers = $paginator->fetchAll($client->api('teams'), 'members', $parameters);
    } catch (\Github\Exception\RuntimeException $exception) {
//            echo $user['login'] . " not in a " . $team['name'] . "\n";
    }
    $org_team_maintainers[$team['slug']] = $team_maintainers;
    $team_maintainers = array();
}

$file = '/home/rmamaev/workspace/github-terraform-exporter/tf-commands.txt';

foreach ($org_teams as $team) {
    $command = "terraform import github_team.team_" . $team['slug'] . " " .
        $team['id'] . "\n";
    file_put_contents($file, $command, FILE_APPEND);
}

foreach ($org_repositories as $repo) {
    $command = "terraform import github_repository." . $repo['name'] . " " .
        $repo['name'] . "\n";
    file_put_contents($file, $command, FILE_APPEND);
}

foreach ($collaborators_in_repo as $repo => $value) {
    foreach ($value as $collaborator) {
        $command = "terraform import github_repository_collaborator." . $repo . "_" .
            $collaborator['login'] . "_collaborator " . $repo . ":" . $collaborator['login'] . "\n";
        file_put_contents($file, $command, FILE_APPEND);
    }
}

foreach ($org_user_admins as $admin) {
    $command = "terraform import github_membership.membership_for_" . $admin['login'] . " " .
        $org . ":" . $admin['login'] . "\n";
    file_put_contents($file, $command, FILE_APPEND);
}

foreach ($org_user_members as $user) {
    $command = "terraform import github_membership.membership_for_" . $user['login'] . " " .
        $org . ":" . $user['login'] . "\n";
    file_put_contents($file, $command, FILE_APPEND);
}

foreach ($team_members as $team => $users) {
    foreach ($users as $user) {
        $command = "terraform import github_team_membership.member " . $team . ":" . $user . "\n";
        file_put_contents($file, $command, FILE_APPEND);
    }
}

foreach ($team_maintainers as $team => $users) {
    foreach ($users as $user) {
        $command = "terraform import github_team_membership.member " . $team . ":" . $user . "\n";
        file_put_contents($file, $command, FILE_APPEND);
    }
}

require_once 'templates/repos.php';
require_once 'templates/repo-collaborators.php';
require_once 'templates/org-users.php';
require_once 'templates/teams.php';
require_once 'templates/team-members.php';
