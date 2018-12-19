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

$paginator  = new Github\ResultPager($client);
//$parameters = array($org);
//$repos    = $paginator->fetchAll($client->api('organization'), 'repositories', $parameters);

/**
 * github_membership:
 * username - login
 * role - member, admin
 */
//$parameters = array($org, null, 'all', 'member');
//$org_user_members = $paginator->fetchAll($client->api('members'), 'all', $parameters);
//$parameters = array($org, null, 'all', 'admin');
//$org_user_admins = $paginator->fetchAll($client->api('members'), 'all', $parameters);

/**
 * github_team:
 * name
 * description
 * privacy
 * parent_team_id
 */
//$org_teams = array();
//// i need to create a new client object to use different api version as list child teams is an experimental feature
//$client_preview = new \Github\Client(null, 'hellcat-preview', null);
//$client_preview->authenticate($token, null, Github\Client::AUTH_HTTP_TOKEN);
//$paginator_preview  = new Github\ResultPager($client_preview);
//$parameters = array($org);
//$org_teams = $paginator_preview->fetchAll($client_preview->api('teams'), 'all', $parameters);

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

//$team_repositories = array();
//$team_repositories_with_permission = array();
//foreach ($org_teams as $team) {
//    foreach ($paginator->fetchAll($client->api('teams'), 'repositories', $team['id']) as $rep) {
//        $team_repositories_with_permission += array($rep['name'] => $rep['permissions']);
//        echo "terraform import github_team_repository.team_" . $team['slug'] .
//            "_repo_" . $rep['name'] . " " . $team['id'] . ":" . $rep['name'] . "\n";
//    }
//    $team_repositories[$team['slug']] = $team_repositories_with_permission;
//    $team_repositories_with_permission = array();
//}

/**
 * просто перечисление пользователей
 * github_team_membership:
 * team_id
 * username
 * role
 */

//$org_team_membership = array();
//$org_users = array_merge($org_user_members, $org_user_admins);
//$team_users_and_their_roles = array();

//TODO: переделать этот кусок. Он слишком часто обращается к api и выжигает все лимиты. Это стало проблемой после перехода на паджинацию
//foreach ($org_teams as $team) {
//    foreach ($org_users as $user) {
//        try {
//            $team_users_and_their_roles += array($user['login'] =>
//                $client->organization()->teams()->check($team['id'], $user['login'])['role']);
//            echo "terraform import github_team_membership." . "team_" .
//                $team['slug'] . "_" . $user['login'] . "_membership " . $team['id'] . ":" . $user['login'] . "\n";
//        } catch (\Github\Exception\RuntimeException $exception) {
////            echo $user['login'] . " not in a " . $team['name'] . "\n";
//        }
//    }
//    $org_team_membership[$team['slug']] = $team_users_and_their_roles;
//    $team_users_and_their_roles = array();
//}
$org_team_members = array();
$org_team_maintainers = array();
$team_maintainers = array();
$team_members = array();

//foreach ($org_teams as $team) {
//    try {
//        $parameters = array($team['id'], 'member');
//        $team_members += $paginator_preview->fetchAll($client_preview->api('teams'), 'members', $parameters);
//    } catch (\Github\Exception\RuntimeException $exception) {
////            echo $user['login'] . " not in a " . $team['name'] . "\n";
//    }
//    $org_team_members[$team['slug']] = $team_members;
//    $team_members = array();
//}

//foreach ($org_teams as $team) {
//    try {
//        $parameters = array($team['id'], 'maintainer');
//        $team_maintainers = $paginator_preview->fetchAll($client_preview->api('teams'), 'members', $parameters);
//    } catch (\Github\Exception\RuntimeException $exception) {
////            echo $user['login'] . " not in a " . $team['name'] . "\n";
//    }
//    $org_team_maintainers[$team['slug']] = $team_maintainers;
//    $team_maintainers = array();
//}
//


$org_team_maintainers = array(  'billing' =>
    array (
        0 =>
            array (
                'login' => 'KonovalovMaxim',
                'id' => 3023879,
                'node_id' => 'MDQ6VXNlcjMwMjM4Nzk=',
                'avatar_url' => 'https://avatars1.githubusercontent.com/u/3023879?v=4',
                'gravatar_id' => '',
                'url' => 'https://api.github.com/users/KonovalovMaxim',
                'html_url' => 'https://github.com/KonovalovMaxim',
                'followers_url' => 'https://api.github.com/users/KonovalovMaxim/followers',
                'following_url' => 'https://api.github.com/users/KonovalovMaxim/following{/other_user}',
                'gists_url' => 'https://api.github.com/users/KonovalovMaxim/gists{/gist_id}',
                'starred_url' => 'https://api.github.com/users/KonovalovMaxim/starred{/owner}{/repo}',
                'subscriptions_url' => 'https://api.github.com/users/KonovalovMaxim/subscriptions',
                'organizations_url' => 'https://api.github.com/users/KonovalovMaxim/orgs',
                'repos_url' => 'https://api.github.com/users/KonovalovMaxim/repos',
                'events_url' => 'https://api.github.com/users/KonovalovMaxim/events{/privacy}',
                'received_events_url' => 'https://api.github.com/users/KonovalovMaxim/received_events',
                'type' => 'User',
                'site_admin' => false,
            ),
        1 =>
            array (
                'login' => 'deusdeorum0',
                'id' => 11729552,
                'node_id' => 'MDQ6VXNlcjExNzI5NTUy',
                'avatar_url' => 'https://avatars3.githubusercontent.com/u/11729552?v=4',
                'gravatar_id' => '',
                'url' => 'https://api.github.com/users/deusdeorum0',
                'html_url' => 'https://github.com/deusdeorum0',
                'followers_url' => 'https://api.github.com/users/deusdeorum0/followers',
                'following_url' => 'https://api.github.com/users/deusdeorum0/following{/other_user}',
                'gists_url' => 'https://api.github.com/users/deusdeorum0/gists{/gist_id}',
                'starred_url' => 'https://api.github.com/users/deusdeorum0/starred{/owner}{/repo}',
                'subscriptions_url' => 'https://api.github.com/users/deusdeorum0/subscriptions',
                'organizations_url' => 'https://api.github.com/users/deusdeorum0/orgs',
                'repos_url' => 'https://api.github.com/users/deusdeorum0/repos',
                'events_url' => 'https://api.github.com/users/deusdeorum0/events{/privacy}',
                'received_events_url' => 'https://api.github.com/users/deusdeorum0/received_events',
                'type' => 'User',
                'site_admin' => false,
            ),
    ),
  'box-vimbox' =>
  array (
      0 =>
          array (
              'login' => 'slastique',
              'id' => 4417371,
              'node_id' => 'MDQ6VXNlcjQ0MTczNzE=',
              'avatar_url' => 'https://avatars0.githubusercontent.com/u/4417371?v=4',
              'gravatar_id' => '',
              'url' => 'https://api.github.com/users/slastique',
              'html_url' => 'https://github.com/slastique',
              'followers_url' => 'https://api.github.com/users/slastique/followers',
              'following_url' => 'https://api.github.com/users/slastique/following{/other_user}',
              'gists_url' => 'https://api.github.com/users/slastique/gists{/gist_id}',
              'starred_url' => 'https://api.github.com/users/slastique/starred{/owner}{/repo}',
              'subscriptions_url' => 'https://api.github.com/users/slastique/subscriptions',
              'organizations_url' => 'https://api.github.com/users/slastique/orgs',
              'repos_url' => 'https://api.github.com/users/slastique/repos',
              'events_url' => 'https://api.github.com/users/slastique/events{/privacy}',
              'received_events_url' => 'https://api.github.com/users/slastique/received_events',
              'type' => 'User',
              'site_admin' => false,
          ),
      1 =>
          array (
              'login' => 'sergey-safonov',
              'id' => 9696320,
              'node_id' => 'MDQ6VXNlcjk2OTYzMjA=',
              'avatar_url' => 'https://avatars3.githubusercontent.com/u/9696320?v=4',
              'gravatar_id' => '',
              'url' => 'https://api.github.com/users/sergey-safonov',
              'html_url' => 'https://github.com/sergey-safonov',
              'followers_url' => 'https://api.github.com/users/sergey-safonov/followers',
              'following_url' => 'https://api.github.com/users/sergey-safonov/following{/other_user}',
              'gists_url' => 'https://api.github.com/users/sergey-safonov/gists{/gist_id}',
              'starred_url' => 'https://api.github.com/users/sergey-safonov/starred{/owner}{/repo}',
              'subscriptions_url' => 'https://api.github.com/users/sergey-safonov/subscriptions',
              'organizations_url' => 'https://api.github.com/users/sergey-safonov/orgs',
              'repos_url' => 'https://api.github.com/users/sergey-safonov/repos',
              'events_url' => 'https://api.github.com/users/sergey-safonov/events{/privacy}',
              'received_events_url' => 'https://api.github.com/users/sergey-safonov/received_events',
              'type' => 'User',
              'site_admin' => false,
          ),
      2 =>
          array (
              'login' => 'deusdeorum0',
              'id' => 11729552,
              'node_id' => 'MDQ6VXNlcjExNzI5NTUy',
              'avatar_url' => 'https://avatars3.githubusercontent.com/u/11729552?v=4',
              'gravatar_id' => '',
              'url' => 'https://api.github.com/users/deusdeorum0',
              'html_url' => 'https://github.com/deusdeorum0',
              'followers_url' => 'https://api.github.com/users/deusdeorum0/followers',
              'following_url' => 'https://api.github.com/users/deusdeorum0/following{/other_user}',
              'gists_url' => 'https://api.github.com/users/deusdeorum0/gists{/gist_id}',
              'starred_url' => 'https://api.github.com/users/deusdeorum0/starred{/owner}{/repo}',
              'subscriptions_url' => 'https://api.github.com/users/deusdeorum0/subscriptions',
              'organizations_url' => 'https://api.github.com/users/deusdeorum0/orgs',
              'repos_url' => 'https://api.github.com/users/deusdeorum0/repos',
              'events_url' => 'https://api.github.com/users/deusdeorum0/events{/privacy}',
              'received_events_url' => 'https://api.github.com/users/deusdeorum0/received_events',
              'type' => 'User',
              'site_admin' => false,
          ),
  ),
);

//TODO: разобраться с этой хренью
//minify/cleanup $org_team_maintainers
$tmp = array();
foreach ($org_team_maintainers as $key => $value) {
    foreach ($value as $user) {
        $tmp += array($user['login']);
    }
    $team_maintainers[$key] = $tmp;
    $tmp = array();
}

foreach ($team_maintainers as $key => $value) {
        print $value . "\n";
}

echo "sss";
//foreach ($org_teams as $team) {
//    echo "terraform import github_team.team_" . $team['slug'] . " " .
//        $team['id'] . "\n";
//}
//
//foreach ($repos as $repo) {
//    echo "terraform import github_repository." . $repo['name'] . " " .
//        $repo['name'] . "\n";
//}
//
//foreach ($collaborators_in_repo as $repo => $value) {
//    foreach ($value as $collaborator) {
//        echo "terraform import github_repository_collaborator." . $repo . "_" .
//            $collaborator['login'] . "_collaborator " . $repo . ":" . $collaborator['login'] . "\n";
//    }
//}
//
//foreach ($org_user_admins as $admin) {
//    echo "terraform import github_membership.membership_for_" . $admin['login'] . " " .
//        $org . ":" . $admin['login'] . "\n";
//}
//
//foreach ($org_user_members as $user) {
//    echo "terraform import github_membership.membership_for_" . $user['login'] . " " .
//        $org . ":" . $user['login'] . "\n";
//}
//
//require_once 'templates/repos.php';
//require_once 'templates/repo-collaborators.php';
//require_once 'templates/org-users.php';
//require_once 'templates/teams.php';
//require_once 'templates/team-members.php';
