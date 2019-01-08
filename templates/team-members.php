<?php echo "============== Teams members resource ============== " ?>

<?php
foreach ($org_team_members as $team => $users) {
    foreach ($users as $member) { ?>
        resource "github_team_membership" "team_<?= $team ?>_<?= $member['login'] ?>_membership" {
        team_id  = "${github_team.team_<?= $team ?>.id}"
        username = "<?= $member['login'] ?>"
        role     = "member"
        }
    <?php } ?>
<?php } ?>

<?php
foreach ($org_team_maintainers as $team => $users) {
    foreach ($users as $maintainer) { ?>
        resource "github_team_membership" "team_<?= $team ?>_<?= $maintainer['login'] ?>_membership" {
        team_id  = "${github_team.team_<?= $team ?>.id}"
        username = "<?= $maintainer['login'] ?>"
        role     = "maintainer"
        }
    <?php } ?>
<?php } ?>
