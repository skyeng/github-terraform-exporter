<?php
foreach ($members_in_team as $key => $value) { ?>
    <?php foreach ($value as $member) { ?>
    resource "github_team_membership" "<?= $key ?>_membership" {
        team_id  = "${github_team.<?= $key ?>.id}"
        username = "<?= $member['name'] ?>"
        role     = "<?= $member['role'] ?>"
    }

    <?php } ?>
<?php } ?>

