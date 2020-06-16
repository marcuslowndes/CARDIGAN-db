<div class="row">
    <div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle"
                    data-toggle="dropdown" style="width:50%">
                Select <?= $selected_subtype['name'] ?>
                <?= $selected_type ?> data subcategory
            </button>
            <div class="dropdown-menu">
            <?php   foreach($entities as $entity) : ?>
                <a class="dropdown-item" href="database/<?= $selected_type
                        ?>/<?= $selected_subtype['id'] ?>/<?= $entity['idEntity'] ?>">
                    <?= $entity['Name'] ?>
                </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<br><br>
