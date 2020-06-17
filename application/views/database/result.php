<br>
<!-- <h2 class="text-center"> <?= $title ?> </h2> -->
<br>
<div  style="margin:10px;">
<table class="table table-hover">
	<thead>
		<tr class="table-primary">
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
        foreach($all_results as $patient => $attributes) :
		$i++;
		foreach ($attributes as $attribute => $results) :
		if (isset($results['Attribute']['Name'])) :
			if($i % 2 === 0)
				$table_colour = 'table-light';
			else
				$table_colour = 'table-secondary';
			?>

            <tr class="<?= $table_colour ?>"  id="<?= $attribute ?>">
            	<td scope="row"> <?= $results['Attribute']['Name']
					?> <?= $results['Attribute']['Units'] ?> </td>

                <td scope="row"> <?= $patient ?> </td>

                <?php
				foreach($visits as $visit) {
					echo '<td scope="row">';

					foreach($results['Values'] as $visitID => $value)
						if ($visit['idVisitation'] == $visitID) {
							if ($results['Attribute']['Value_Type'] == 'BOOL') {
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

    <?php endif; endforeach; endforeach; ?>
	</tbody>
</table>
</div><br>
