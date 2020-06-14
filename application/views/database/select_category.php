<div class="row">
	<div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle"
					data-toggle="dropdown" style="width:50%">
				Select <?= $selected_type ?> Data Category
			</button>
            <div class="dropdown-menu">
			<?php
				foreach($subtypes as $subtype) {
					if ($subtype['Weekly'] == 1)
						$is_weekly = '(per Visitation)';
					else
						$is_weekly = '(Overall)';

            		echo '<a class="dropdown-item" href="database/Clinical/'
						. $subtype['idData_Type'] . '"> ' . $subtype['Subtype']
						. ' <small class="small-Preconsultation">'
						. $is_weekly . '</small>' . $subtype['Walk_Type'] . '</a>';
				}
			?>
            </div>
        </div>
    </div>
</div> <br>
