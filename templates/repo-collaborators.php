<?php
foreach ($collaborators_in_repo as $repo => $value) { ?>
    <?php foreach ($value as $collaborator) { ?>
    resource "github_repository_collaborator" "<?= $repo ?>_<?= $collaborator['login'] ?>_collaborator" {
        repository = "<?= $repo ?>"
        username   = "<?= $collaborator['login'] ?>"
        <?php if ($collaborator['permissions']['admin'] === true) { ?>
            permission = "admin"
            }
            <?php break ?>
        <?php } ?>
        <?php if ($collaborator['permissions']['push'] === true) { ?>
            permission = "push"
            }
            <?php //break ?>
        <?php } ?>

        <?php if (($collaborator['permissions']['push'] === false) && ($collaborator['permissions']['admin'] === false)) { ?>
            permission = "pull"
            }
            <?php //break ?>
        <?php } ?>
    }
    <?php } ?>
<?php } ?>

