<?php echo "============== Repos list ============== " ?>

<?php foreach ($org_repositories as $repo) { ?>
    resource "github_repository" "<?= $repo['name'] ?>" {
    name               = "<?= $repo['name'] ?>"

    description        = "<?= $repo['description'] ?>"

    <?php if ($repo['homepage'] === "") { ?>
    homepage_url       = ""
    <?php } else { ?>
    homepage_url       = "<?= $repo['homepage'] ?>"
    <?php } ?>

    has_projects       = <?= json_encode($repo['has_projects']) ?>

    has_wiki           = <?= json_encode($repo['has_wiki']) ?>

    has_issues         = <?= json_encode($repo['has_issues']) ?>

    has_downloads      = <?= json_encode($repo['has_downloads']) ?>

    private            = <?= json_encode($repo['private']) ?>

    archived           = <?= json_encode($repo['archived']) ?>

    topics             = [  <?= $repo_topics_as_str[$repo['name']] ?> ]
    }
<?php } ?>

<?php echo "============== repository outputs ============== " ?>
<?php
foreach ($org_repositories as $repo) { ?>
    output "repo_<?= $repo['name'] ?>_name" {
    value = "${github_repository.<?= $repo['name'] ?>.name}"
    }
<?php } ?>
