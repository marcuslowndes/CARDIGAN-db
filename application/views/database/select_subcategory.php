<div class="row">
    <div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-danger dropdown-toggle"
                    data-toggle="dropdown">
                Select <?= $selected_subtype ?>
                <?= $selected_type ?> data subcategory
            </button>
            <div class="dropdown-menu">
            <?php   foreach($entities as $entity) : ?>
                <a class="dropdown-item" href="database/<?= $selected_type
                        ?>/<?= $selected_subtype_ID ?>/<?= $entity['idEntity'] ?>">
                    <?= $entity['Name'] ?>
                </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<br><br>
