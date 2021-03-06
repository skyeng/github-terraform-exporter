<?php echo "============== Teams and team repos resources ============== " ?>

<?php
foreach ($org_teams as $team) { ?>
    resource "github_team" "team_<?= $team['slug'] ?>" {
    name               = "<?= $team['name'] ?>"
    description        = "<?= $team['description'] ?>"
    privacy            = "<?= $team['privacy'] ?>"
    <?php if ($team['parent'] !== null) { ?>
        parent_team_id = "<?= $team['parent']['id'] ?>"
    <?php } ?>
    }
<?php } ?>
<?php foreach ($team_repositories as $team => $value) {
    foreach ($value as $repo => $permissions) { ?>
        resource "github_team_repository" "team_<?= $team ?>_repo_<?= $repo ?>" {
        team_id    = "${github_team.team_<?= $team ?>.id}"
        repository = "${github_repository.<?= $repo ?>.name}"
        <?php if ($permissions['admin'] === true) { ?>
            permission = "admin"
            }
            <?php continue ?>
        <?php } ?>
        <?php if ($permissions['push'] === true) { ?>
            permission = "push"
            }
            <?php continue ?>
        <?php } ?>

        <?php if (($permissions['push'] === false) && ($permissions['admin'] === false)) { ?>
            permission = "pull"
            }
            <?php continue ?>
        <?php } ?>
    <?php } ?>
<?php } ?>

<?php echo "============== Team outputs ============== " ?>
<?php
foreach ($org_teams as $team) { ?>
    output "team_<?= $team['slug'] ?>_id" {
    value = "${github_team.<?= $team['slug'] ?>.id}"
    }
<?php } ?>
