<div class="row">
    <div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" style="width:50%"> Select <?= $selected_subtype ?> <?= $selected_type ?> Data Subcategory </button>
            <div class="dropdown-menu">
            <?php   foreach($entities as $entity) : ?>
                <a class="dropdown-item" href="database/Clinical/<?=$selected_subtype_ID?>/<?=$entity["idEntity"]?>"><?=$entity["Name"]?></a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div> <br>
