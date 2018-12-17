<?php echo "============== Users list ============== " ?>
<?php foreach ($org_user_admins as $admin) { ?>
    resource "github_membership" "membership_for_<?= $admin['login'] ?>" {
        username = "<?= $admin['login'] ?>"
        role     = "admin"
    }
    <?php echo "user import command is: terraform import github_membership.membership_for_" . $admin['login'] . " " .
        $org . ":" . $admin['login'] . "\n"; ?>
<?php } ?>
<?php foreach ($org_user_members as $user) { ?>
    resource "github_membership" "membership_for_<?= $user['login'] ?>" {
        username = "<?= $user['login'] ?>"
        role     = "member"
    }
    <?php echo "user import command is: terraform import github_membership.membership_for_" . $user['login'] . " " .
        $org . ":" . $user['login'] . "\n"; ?>
<?php } ?>

