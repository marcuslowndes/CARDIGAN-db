<div class="row">
	<div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle"
					data-toggle="dropdown" style="width:25rem">
				Select <?= $selected_type ?> data category
			</button>
            <div class="dropdown-menu">
			<?php
				foreach($subtypes as $subtype) {
					if ($subtype['Weekly'] == 1)
						$is_weekly = '(per Visitation)';
					else
						$is_weekly = '(Overall)';

					if (isset($subtype['Walk_Type']))
						$subtype_name = ' - ' . $subtype['Subtype'];
					else
						$subtype_name = $subtype['Subtype'];

            		echo '<a class="dropdown-item" href="database/' . $selected_type
					 	. '/' . $subtype['idData_Type'] . '"> <div class="mr-auto"'
						. 'style="margin-bottom: -0.3rem;">' . $subtype['Walk_Type']
						. ' ' . $subtype_name . '</div><small class="small-Precon'
						. 'sultation">' . $is_weekly . '</small>' . '</a>';
				}
			?>
            </div>
        </div>
    </div>
</div>
<br><br>
