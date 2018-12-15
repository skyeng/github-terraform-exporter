<?php foreach ($org_user_admins as $admin) { ?>
    resource "github_membership" "membership_for_<?= $admin['login'] ?>" {
        username = "<?= $admin['login'] ?>"
        role     = "admin"
    }
<?php } ?>
<?php foreach ($org_user_members as $user) { ?>
    resource "github_membership" "membership_for_<?= $user['login'] ?>" {
        username = "<?= $user['login'] ?>"
        role     = "member"
    }
<?php } ?>

