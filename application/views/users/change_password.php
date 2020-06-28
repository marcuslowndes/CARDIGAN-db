<div class="container-fluid bg-success">
	<div class="container container-form" >
		<br><br>
		<h2> <?= $title ?> </h2>

		<div color='red'><?php echo validation_errors(); ?></div>
		<?php echo form_open('change_password'); ?> <br>

		<div class="form-group">
			<label for="password">Confirm Current Password</label>
			<input type="password" class="form-control" name="password" required autofocus>
		</div>

		<div class="form-group">
			<label for="password2">New Password</label>
			<input type="password" class="form-control" name="password2"
				placeholder="Use both letters and numbers" required autofocus>
		</div>

		<div class="form-group">
			<label for="password3">Confirm New Password</label>
			<input type="password" class="form-control" name="password3" required autofocus>
		</div>

		<button class="btn btn-primary btn-lg btn-block" type="submit">Change Password</button>

		<?php echo form_close(); ?>

		<br><br>

		<a class="btn btn-danger" href="delete_account/<?= $id ?>">Delete Account</a>
	</div>
	<br><br><br><br>
</div>
