<div class="row">
	<div class="col text-center">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" style="width:50%"> Select <?= $selected_type ?> Data Category </button>
            <div class="dropdown-menu">
			<?php 	foreach($subtypes as $subtype) {
						if ($subtype['Weekly'] == 1) $is_weekly = ' (Visitation) ';
	            		else $is_weekly = ' (Preconsultation) ';
	            		echo '<a class="dropdown-item" href="database/Clinical/'  . $subtype["idData_Type"] . '"> ' . $is_weekly . $subtype["Subtype"] . ' ' . $subtype['Walk_Type'] . '</a>';
	            	} ?>
            </div>
        </div>
    </div>
</div> <br>
