<div class="row">
	<div class="col" style="max-width:25% !important;"><br></div>
	    <div class="col" style="max-width:50% !important; color: white;
					background-color: rgba(0, 0, 0, 0.4);
					border-radius: 2rem; padding: 1rem;
					padding-left: 1.5rem; padding-right:1.5rem">
				Selected: <?php
					if ($selected_type != '')
						echo $selected_type;
					else
						echo 'None';
					if ($selected_subtype != '')
						echo ' / ' . $selected_subtype;
					if ($selected_entity != '')
						echo ' / ' . $selected_entity;
					if ($selected_attribute != '')
						echo ' / ' . $selected_attribute . ' data';
				?>
		</div>
	<div class="col" style="max-width:25% !important;"><br></div>
</div>
<br><br>
