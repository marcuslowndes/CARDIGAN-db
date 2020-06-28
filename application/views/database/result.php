
<div style="margin:10px; font-size:80%">
<table class="table table-hover">
	<thead>
		<tr class="table-primary">
			<th> Category <br> <br> </th>
            <th> Description/Question <br> <br> </th>
            <th> Patient ID <br> <br> </th>
            <?php foreach($visits as $visit) {
				$visit_type =  '';
                if($visit['Preconsultation'] == 1)
					$visit_type = ' <small class="small-Preconsultation">'
						. '(Preconsultation)</small>';
                echo '<th scope="col"> Visit ' . $visit['idVisitation']
					. $visit_type . '<br> <small>' . $visit['Date_Start']
					. ' – ' . $visit['Date_End'] . '</small></th>';
            } ?>
		</tr>
	</thead>
	<tbody>
    <?php
        $i = 0;
        foreach($all_results as $patient => $attr_per_data_type) :
		$i++;
		foreach($attr_per_data_type as $data_type => $attributes) :
		foreach ($attributes as $attribute => $results_per_attr) :
		if (isset($results_per_attr['Attribute']['Name'])) :
			if($i % 2 === 0)
				$table_colour = 'table-light';
			else
				$table_colour = 'table-info';
			?>

            <tr class="<?= $table_colour ?>"  id="<?= $attribute ?>">
				<td scope="row"> <?= $results_per_attr['Data_Type']['Type'] ?> / <?=
					$results_per_attr['Data_Type']['Walk_Type'] ?> <?=
					$results_per_attr['Data_Type']['Subtype'] ?>  </td>

            	<td scope="row"> <?= $results_per_attr['Attribute']['Name']
					?> <?= $results_per_attr['Attribute']['Units'] ?> </td>

                <td scope="row"> <?= $patient ?> </td>

                <?php  // TODO: FIX RESULTS VIEW FOR GAIT DATA TO SHOW Walk_Type
				foreach($visits as $visit) {
					echo '<td scope="row">';

					foreach($results_per_attr['Values'] as $visitID => $results)
						foreach ($results as $value)
							if ($visit['idVisitation'] == $visitID) {
								if ($results_per_attr['Attribute']['Value_Type'] == 'BOOL') {
									if ($value == 1)
	                                    echo 'Yes <br>';
									else if ($value == 0)
	                                    echo 'No <br>';
									else
									   echo $value . ' <br>';
								} else
									echo $value . ' <br>';
							}

					echo '</td>';
				}
				?>
            </tr>

    <?php endif; endforeach; endforeach; endforeach; ?>
	</tbody>
</table>
</div>
