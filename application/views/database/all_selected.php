<div class="col">
<div style="height: 530px; overflow: scroll; overflow-x: hidden;">
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
        <?php for ($i=0; $i<5 ; $i++) {
            echo '<td> <br> </td>';
        }?>
    </tr>
	</tbody>
</table>
</div>
</div>
<br><br>
