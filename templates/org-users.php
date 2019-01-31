<?php echo "============== Admins list ============== " ?>
<?php foreach ($org_user_admins as $admin) { ?>
    resource "github_membership" "membership_for_<?= $admin['login'] ?>" {
        username = "<?= $admin['login'] ?>"
        role     = "admin"
    }
<?php } ?>

<?php echo "============== Admins outputs ============== " ?>
<?php foreach ($org_user_admins as $admin) { ?>
    output "github_<?= $admin['login'] ?>_username" {
        value = "${github_membership.membership_for_<?= $admin['login'] ?>.username}"
    }
<?php } ?>

<?php echo "============== Users list ============== " ?>
<?php foreach ($org_user_members as $user) { ?>
    resource "github_membership" "membership_for_<?= $user['login'] ?>" {
        username = "<?= $user['login'] ?>"
        role     = "member"
    }
<?php } ?>

<?php echo "============== Users outputs ============== " ?>
<?php foreach ($org_user_members as $user) { ?>
    output "github_<?= $user['login'] ?>_username" {
        value = "${github_membership.membership_for_<?= $user['login'] ?>.username}"
    }
<?php } ?>
