<div class="row">
    <div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-danger dropdown-toggle"
                    data-toggle="dropdown">
                Select data item from subcategory
            </button>
            <div class="dropdown-menu">
            <?php   foreach($attributes as $id => $attribute) : ?>
                <a class="dropdown-item" href="database/<?= $selected_type
                        ?>/<?= $selected_subtype_ID ?>/<?= $selected_entity_ID
                        ?>/<?= $attribute['idAttribute'] ?>">
                    <?= $attribute['Name'] ?>
                </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<br><br>
