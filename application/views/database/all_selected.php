<div class="row">
<div style="width: 70%;/*height:calc(43px * 6);*/ overflow: scroll;
    overflow-x: hidden; font-size:90%; margin-left: auto; margin-right: auto">
<style media="screen">
    .table td, .table th {
        padding: 0.5rem;
    }
</style>
<table class="table table-hover">
	<thead>
		<tr class="table-primary">
            <th>Data Type</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Data Item</th>
            <th>Remove</th>
		</tr>
	</thead>
	<tbody>
    <?php
        $i = 0;
        foreach($all_selected as $selected) :
		if (isset($selected['type'])) :
			$i++;
			if($i % 2 === 0)
				$table_colour = 'table-secondary';
			else
				$table_colour = 'table-light';
			?>

            <tr class="<?= $table_colour ?>"  id="<?=
			 		$selected['attribute_ID'] ?>">

            	<td scope="row"> <?= $selected['type'] ?> </td>

                <td scope="row"> <?= $selected['subtype'] ?> </td>

                <td scope="row"> <?= $selected['entity'] ?> </td>

                <td scope="row"> <?= $selected['attribute'] ?> </td>

                <td scope="row"> <a class="btn btn-danger" href="database_remove/<?=
                    $selected['attribute_ID'] ?>"> 🗑 </a> </td>

            </tr>

    <?php endif; endforeach; ?>
    <tr class="table-primary">
        <td> <br> </td>
        <td> <br> </td>
        <td> <br> </td>
        <td> <br> </td>
        <td>
    		<a class="btn  btn-<?= $add_btn_style ?>" <?= $add_btn_enable ?>> + </a>
        </td>
    </tr>
	</tbody>
</table>
</div>
</div>
<br><br>
