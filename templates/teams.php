<?php
foreach ($teams_in_repo as $key => $value) { ?>
    <?php foreach ($value as $team) { ?>
    resource "github_teams" "<?= $team['slug'] ?>" {
        name               = "<?= $team['name'] ?>"
        description        = "<?= $team['description'] ?>"
        permissions         = "<?= $team['permission'] ?>"
    }

    resource "github_team_repository" "<?= $team['slug']."_".$k ?>" {
        team_id    = "${github_team.<?= $team['slug'] ?>.id}"
        repository = "${github_repository.<?= $key ?>.name}"
        permission = "push"
    }

    <?php } ?>
<?php } ?>

