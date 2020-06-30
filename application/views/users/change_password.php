<div class="container-fluid bg-success">
	<div class="container container-form" >
		<br><br>
		<h2> <?= $title ?> </h2>
		<br>
		<div class="">
			<img class="img-fluid float-left" style="height: 66px; margin:0 1.25rem 0 0.5rem"
				src="assets/images/iconfinder_user_5925654.svg"
				alt="User icon" title="User icon">
			<h4> <?= $forename ?> <?= $surname ?> </h4>
			<?= $email ?>
		</div>

		<div color='red'><?php echo validation_errors(); ?></div>
		<?php echo form_open('change_password'); ?> <br>

		<div class="form-group">
			<label for="password">Confirm Current Password</label>
			<input type="password" class="form-control" name="password"
				required autofocus>
		</div>

		<div class="form-group">
			<label for="password2">New Password</label>
			<input type="password" class="form-control" name="password2"
				placeholder="Use both letters and numbers" required autofocus>
		</div>

		<div class="form-group">
			<label for="password3">Confirm New Password</label>
			<input type="password" class="form-control" name="password3"
				required autofocus>
		</div>

		<div class="form-group captcha">
		    <label for="user_captcha"><?php echo $captcha['image']; ?></label>
		    <br>
		    <input type="text" autocomplete="off" name="user_captcha"
				placeholder="Enter above text" class="form-control"
				value="<?php if(!empty($user_captcha)) echo $user_captcha; ?>" />
		</div>

		<button class="btn btn-primary btn-lg" type="submit"
			id="load-btn">Change Password</button>

		<?php echo form_close(); ?>

		<br><br>

		<button style="" class="btn btn-danger"
				type="button" name="watchVideo" data-toggle="modal"
				data-target="#confirm-delete">Delete Account</button>
	</div>
	<br><br><br>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="confirm-delete"
 		aria-labelledby="confirm-delete-title" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content bg-dark" style="color:white">
			<div class="modal-header">
				<h5 class="modal-title" id="confirm-delete-title">
					Are you sure?
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>This will permanently delete your account.</p>
			</div>
			<div class="modal-footer">
		        <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Cancel</button>
		        <a class="btn btn-lg btn-danger" href="delete_account/<?= $id ?>">Confirm</a>
			</div>
		</div>
	</div>
</div>
