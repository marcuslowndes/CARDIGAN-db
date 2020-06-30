<div class="container-fluid bg-success">
	<div class="container">
		<br>
		<h2><?= $title ?></h2>
		<br><br>
		<h3>Set 1</h3>
		<small style="padding:4px">Click a photo to zoom in.</small>
		<div class="row-gallery">
			<div class="col-gallery">
				<?php
				$col_size = (sizeof($photos_dir1) + 3) / 4;
				$i = 1;
				foreach ($photos_dir1 as $photo) {
					if ($i == 16)
						echo '<a href="assets/images/FOTOS/'
							. $photos_dir1[sizeof($photos_dir1) - 1]
						 	. '"><img loading="lazy" src="assets/images/FOTOS/'
							. $photos_dir1[sizeof($photos_dir1) - 1]
							. '" id="'. $i . '"></a></div> <div class="col-gallery">';
					else if ($i == 63)
						echo '';
					else if ($i % $col_size != 0)
						echo '<a href="assets/images/FOTOS/'. $photo
						 	. '"><img loading="lazy" src="assets/images/FOTOS/' . $photo
						 	. '"  id="' . $i . '"></a>';
					else
						echo '</div> <div class="col-gallery">';
					$i++;
				}
				?>
			</div>
		</div>
		<br>
		<h3>Set 2</h3>
		<small style="padding:4px">Click a photo to zoom in.</small>
		<div class="row-gallery">
			<div class="col-gallery">
				<?php
				$col_size = (sizeof($photos_dir2)) / 4;
				foreach ($photos_dir2 as $photo) {
					if ($i == 65)
						echo '<a href="assets/images/Pictures + Video (Mireya)/'
						 	. $photos_dir2[sizeof($photos_dir2) - 1]
							. '"><img loading="lazy" src="assets/images/Pictures + Video (Mireya)/'
							. $photos_dir2[sizeof($photos_dir2) - 1]
							. '" id="'. $i . '"></div> <div class="col-gallery"></a>';
					else if ($i == 75)
						echo '<a href="assets/images/Pictures + Video (Mireya)/'
						 	. $photos_dir2[sizeof($photos_dir2) - 2]
							. '"><img loading="lazy" src="assets/images/Pictures + Video (Mireya)/'
							. $photos_dir2[sizeof($photos_dir2) - 2]
							. '" id="'. $i . '"></div> <div class="col-gallery"></a>';
					else if ($i > 78)
						echo '';
					else
					if ($i % $col_size != 0)
						echo '<a href="assets/images/Pictures + Video (Mireya)/'. $photo
						 	. '"><img loading="lazy" src="assets/images/Pictures + Video (Mireya)/' . $photo
						 	. '" id="' . $i . '"></a>';
					else
						echo '</div> <div class="col-gallery">';
					$i++;
				}
				?>
			</div>
		</div>
	</div>
</div>
