<?php echo "============== Users list ============== " ?>
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

<?php echo "============== Users list short (for users/vars.tf) ============== " ?>
variable "github_admins" {
description = "List with admin users in GitHub Organization"
type        = "list"
default     = [<?php echo $github_admin_logins ?>]
}

variable "github_users" {
description = "List with limited users in GitHub Organization"
type        = "list"
default     = [<?php echo $github_user_logins ?>]
}
