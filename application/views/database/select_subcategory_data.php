<div class="row">
    <div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle"
                    data-toggle="dropdown" style="width:50%">
                Select Data From Subcategory
            </button>
            <div class="dropdown-menu">
            <?php   foreach($attributes as $id => $attribute) : ?>
                <a class="dropdown-item" href="database/Clinical/<?=
                        $selected_subtype_ID ?>/<?= $selected_entity_ID
                        ?>/<?= $attribute['idAttribute'] ?>">
                    <?= $attribute['Name'] ?>
                </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div> <br>
