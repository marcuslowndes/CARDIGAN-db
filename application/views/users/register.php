<div class="container-fluid bg-success">
	<div class="container container-form" >
		<br><br>
		<h2> <?= $title ?> </h2>

		<div style='color: #D55342 !important'>
			<?php echo validation_errors(); ?>
		</div>
		<?php echo form_open('register'); ?> <br>

		<div class="form-group">
			<label for="forename">Forename</label>
			<input type="text" class="form-control" name="forename"
				placeholder="" required autofocus>
		</div>

		<div class="form-group">
			<label for="surname">Surname</label>
			<input type="text" class="form-control" name="surname"
				placeholder="" required autofocus>
		</div>

		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" class="form-control" name="email"
				required autofocus>
		</div>

		<div class="form-group">
			<label for="password">Set Password</label>
			<input type="password" class="form-control" name="password"
				placeholder="Use both letters and numbers" required autofocus>
		</div>

		<div class="form-group">
			<label for="password2">Confirm Password</label>
			<input type="password" class="form-control" name="password2"
				required autofocus>
		</div>

		<br>
		<button type="submit" class="btn btn-primary btn-lg" id="load-btn">Submit</button>

		<?php echo form_close(); ?>
	</div>
	<br><br>
</div>
