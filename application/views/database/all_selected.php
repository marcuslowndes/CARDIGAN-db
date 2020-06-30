<div class="col">
<div style="height: 600px; overflow: scroll; overflow-x: auto;">
<style media="screen">
    .table td, .table th {
        padding: 0.5rem;
    }
</style>
<table class="table table-hover" style="margin:0">
	<thead>
		<tr class="table-primary">
            <th>Data Type</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Data Item</th>
            <th><br></th>
            <th><br></th>
		</tr>
	</thead>
	<tbody>
    <?php
        $i = 0;
        foreach($all_selected as $selected) :
		if (isset($selected['type'])) :
			$i++;
			if($i % 2 === 0)
				$table_colour = 'table-light';
			else
				$table_colour = 'table-info';
			?>

            <tr class="<?= $table_colour ?>"  id="<?=
			 		$selected['attribute_ID'] ?>">

            	<td scope="row"> <?= $selected['type'] ?> </td>

                <td scope="row"> <?= $selected['subtype'] ?> </td>

                <td scope="row"> <?= $selected['entity'] ?> </td>

                <td scope="row"> <?= $selected['attribute'] ?> </td>

                <td scope="row"> <a class="btn btn-success" style="padding:4px 7px 7px 7px"
                        href="database_export/<?= $selected['attribute_ID'] ?>/<?=
                        $selected['subtype_ID'] ?>">
                    <img src="assets/images/iconfinder_icon-57-document-download_314514.svg"
                        style="width:22px;" alt="Download as .CSV" title="Download as .CSV">
                </a> </td>

                <td scope="row"> <a class="btn btn-danger" style="padding:4px 7px 7px 7px"
                        href="database_remove/<?= $selected['attribute_ID'] ?>/<?=
                        $selected['subtype_ID'] ?>">
                    <img src="assets/images/iconfinder_delete_678153.svg"
                        style="width:22px;" alt="Remove" title="Remove">
                </a> </td>

            </tr>

    <?php
        endif; endforeach;
        // echo $i;
        // die();
        if($i < 10)
            for ($n=$i; ($n>=$i && $n<9); $n++){
                echo '<tr class="light">';
                for ($j=0; $j<6 ; $j++)
                    echo '<td style="padding-bottom:calc(56px - 45.8px + 8px)">'
                        . '<br></td> ';
                echo '</tr>';
            }
    ?>
    <tr class="table-primary">
        <?php
            for ($i=0; $i<6 ; $i++) {
                echo '<td> <br> </td>';
            }
        ?>
    </tr>
	</tbody>
</table>
</div>
</div>
<br>
</div>
