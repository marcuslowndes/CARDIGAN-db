<div class="container-fluid bg-success">
	<div class="container" >
		<br>
		<div class="row">
			<div class="col-md-6">
				<br>
				<h2> <?= $title ?> </h2>

				<div style='color: #D55342 !important'>
					<?php echo validation_errors(); ?>
				</div>

				<?php echo form_open('login'); ?>
				<br>

				<div class="form-group">
					<input type="text" class="form-control" name="email"
					placeholder="Email" required autofocus>
				</div>

				<div class="form-group">
					<input type="password" class="form-control" name="password"
						placeholder="Password" required autofocus>
				</div>

				<button class="btn btn-primary btn-lg btn-block" type="submit" id="load-btn">
					Log In
				</button>

				<?php echo form_close(); ?>
			</div>
			<div class="col-md-6 col-login">
				<br>
				<h3 style="margin-bottom: 1rem">Don't have an account?</h3>
				<p> Click <a href="register">Register</a> to create one.</p> <br>
				<p> <b>Why would I want an account?</b> </p>
				<p>
					Having an account with the CARDIGAN Project allows you to
					stay up to date with the project and review all the data.
				</p>
				<p>
					Once you have created an account, you cannot access the
					database until you have confirmed with an admin that you mean
					to use the data for legitimate research purposes. This can be
					done via the <a href="contact">Contact Us</a> page, or by
					sending one of the project team a direct email.
				</p>
				<br>
			</div>
		</div>
	</div>
	<br><br><br><br>
</div>
