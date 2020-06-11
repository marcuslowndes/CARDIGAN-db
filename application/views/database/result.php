<br>
<h2 class="text-center"> <?= $title ?> </h2>
<br>
<div  style="margin:10px;">
<table class="table table-hover">
	<thead>
		<tr class="table-primary">
            <th>Description/Question</th>
            <th>Patient ID</th>
            <?php foreach($visits as $visit) {
				$visit_type =  '';
                if($visit['Preconsultation'] == 1)
					$visit_type = ' <small>'// style="background:rgba(0,0,0,0.2)">'
						. '(Preconsultation)</small>';
                echo '<th scope="col"> Visit ' . $visit['idVisitation']
						. $visit_type . '<br>' . $visit['Date_Start']
						. ' –<br>' . $visit['Date_End'] . '</th>';
            } ?>
		</tr>
	</thead>
	<tbody>
    <?php
        $i = 0;
        foreach($all_results as $patient => $results) :
		if (isset($results['Attribute']['Name'])) :
			$i++;
			if($i % 2 === 0)
				$table_colour = 'table-secondary';
			else
				$table_colour = 'table-light';
			?>

            <tr class="<?= $table_colour ?>"  id="<?= $results['Attribute']['idAttribute'] ?>">
            	<td scope="row"> <?= $results['Attribute']['Name'] ?> <?= $results['Attribute']['Units'] ?> </td>

                <td scope="row"> <?= $patient ?> </td>

                <?php
				foreach($visits as $visit) {
					echo '<td scope="row">';

					foreach($results['Values'] as $result)
						foreach($result as $result_visit => $value)
							if ($visit['idVisitation'] == $result_visit) {
								// PROBLEM: ALL ATTRIBUTES HAVE THE SAME NAME (NOT ALWAYS CORRECT)
								if ($results['Attribute']['Value_Type'] == 'BOOL' ){
									if ($value['Value'] == 1)
                                        echo 'Yes'.'<br>';
									else if ($value['Value'] == 0)
                                        echo 'No'.'<br>';
									else
									   echo $value['Value'].'<br>';
								} else
									echo $value['Value'].'<br>';
							}

					echo '</td>';
				}
				?>
            </tr>

    <?php endif; endforeach; ?>
	</tbody>
</table>
</div><br>
