<div class="row">
    <div class="col" style="max-width:50% !important; color: white;
				background-color: rgba(0, 0, 0, 0.4);
				border-radius: 1rem; padding: 1rem 1.5rem;
				margin-left: auto; margin-right: auto;">
			Search: <?php
				if ($selected_type != '')
					echo $selected_type . ' Data';
				else
					echo 'None';
				if ($selected_subtype != '')
					echo ' / ' . $selected_subtype;
				if ($selected_entity != '')
					echo ' / ' . $selected_entity;
				if ($selected_attribute != '')
					echo ' / ' . $selected_attribute;
			?>
	</div>
</div>
<br><br>
