<?php
foreach ($org_teams as $team) { ?>
    resource "github_teams" "team_<?= $team['slug'] ?>" {
    name               = "<?= $team['name'] ?>"
    description        = "<?= $team['description'] ?>"
    permissions        = "<?= $team['permission'] ?>"
    privacy            = "<?= $team['privacy'] ?>"
    <?php if ($team['parent'] !== null) { ?>
        parent_team_id = "<?= $team['parent']['id'] ?>"
    <?php } ?>
    }
<?php } ?>
<?php foreach ($team_repositories as $team => $value) {
    foreach ($value as $repo => $permissions) { ?>
        resource "github_team_repository" "team_<?= $team ?>_repo" {
        team_id    = "${github_team.team_<?= $team ?>.id}"
        repository = "${github_repository.<?= $repo ?>.name}"
        <?php if ($permissions['admin'] === true) { ?>
            permission = "admin"
            }
            <?php break ?>
        <?php } ?>
        <?php if ($permissions['push'] === true) { ?>
            permission = "push"
            }
            <?php break ?>
        <?php } ?>

        <?php if (($permissions['push'] === false) && ($permissions['admin'] === false)) { ?>
            permission = "pull"
            }
            <?php break ?>
        <?php } ?>
    <?php } ?>
<?php } ?>
