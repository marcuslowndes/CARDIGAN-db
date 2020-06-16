<div class="row">
	<div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle"
					data-toggle="dropdown" style="width:50%">
				Select <?= $selected_type ?> data category
			</button>
            <div class="dropdown-menu">
			<?php
				foreach($subtypes as $subtype) {
					if ($subtype['Weekly'] == 1)
						$is_weekly = '(per Visitation)';
					else
						$is_weekly = '(Overall)';

            		echo '<a class="dropdown-item" href="database/' . $selected_type
					 	. '/' . $subtype['idData_Type'] . '"> ' . $subtype['Subtype']
						. ' ' . $subtype['Walk_Type'] . ' <small class="small-Precon'
						. 'sultation">' . $is_weekly . '</small>' . '</a>';
				}
			?>
            </div>
        </div>
    </div>
</div>
<br><br>
