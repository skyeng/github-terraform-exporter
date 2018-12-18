<?php echo "============== Repo Collaborators list ============== " ?>

<?php
foreach ($collaborators_in_repo as $repo => $value) { ?>
    <?php foreach ($value as $collaborator) { ?>
    resource "github_repository_collaborator" "<?= $repo ?>_<?= $collaborator['login'] ?>_collaborator" {
        repository = "<?= $repo ?>"
        username   = "<?= $collaborator['login'] ?>"
        <?php if ($collaborator['permissions']['admin'] === true) { ?>
            permission = "admin"
            }
            <?php continue ?>
        <?php } ?>
        <?php if ($collaborator['permissions']['push'] === true) { ?>
            permission = "push"
            }
            <?php continue ?>
        <?php } ?>
        <?php if (($collaborator['permissions']['push'] === false) && ($collaborator['permissions']['admin'] === false)) { ?>
            permission = "pull"
            }
            <?php continue ?>
        <?php } ?>
    }
    <?php } ?>
<?php } ?>

