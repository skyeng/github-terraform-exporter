<?php echo "============== Teams members resource ============== " ?>

<?php
foreach ($org_team_members as $team => $login) { ?>
        resource "github_team_membership" "team_<?= $team ?>_<?= $member ?>_membership" {
        team_id  = "${github_team.team_<?= $team ?>.id}"
        username = "<?= $member ?>"
        role     = "member"
        }
    <?php } ?>

<?php
foreach ($team_maintainers as $team => $login) { ?>
        resource "github_team_membership" "team_<?= $team ?>_<?= $login ?>_membership" {
        team_id  = "${github_team.team_<?= $team ?>.id}"
        username = "<?= $login ?>"
        role     = "maintainer"
        }
    <?php } ?>
