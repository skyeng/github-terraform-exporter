<?php foreach ($repos as $repo) {?>
resource "github_repository" "<?=$repo['name'] ?>" {
    name               = "<?=$repo['name'] ?>"
    description        = "<?=$repo['description'] ?>"


    has_issues         = <?=json_encode($repo['has_issues']) ?>
    has_projects       = <?=json_encode($repo['has_projects']) ?>
    has_wiki           = <?=json_encode($repo['has_wiki']) ?>
    has_issues         = <?=json_encode($repo['has_issues']) ?>
    has_downloads      = <?=json_encode($repo['has_downloads']) ?>
}

<?php }?>
