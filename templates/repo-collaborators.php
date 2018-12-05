<?php
foreach ($collaborators_in_repo as $repo => $value) { ?>
    <?php foreach ($value as $collaborator) { ?>
    resource "github_repository_collaborator" "<?= $repo ?>."_".<?= $collaborator['login'] ?>."_collaborator" {
        repository = "<?= $repo ?>"
        username   = "<?= $collaborator['login'] ?>"
        permission = "<?= json_encode($collaborator['permissions']['admin']) ?>"
        permission = "<?= json_encode($collaborator['permissions']['push']) ?>"
        permission = "<?= json_encode($collaborator['permissions']['pull']) ?>"
    }
    <?php } ?>
<?php } ?>

