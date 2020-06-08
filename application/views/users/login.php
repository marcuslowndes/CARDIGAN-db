<!--Login Form-->
<br><br>
<div class="container">
	<div class="form-login jumbotron">

		<h3 class="text-center" style="margin-bottom: 1rem"> <?= $title ?> </h3>

		<div color='red'><?php echo validation_errors(); ?></div>
		<?php echo form_open('login'); ?>

		<div class="form-group">
			<input type="text" class="form-control" name="email" placeholder="Email" required autofocus>
		</div>

		<div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required autofocus>
		</div>
		<button class="btn btn-primary btn-lg btn-block" type="submit">Log In</button>

		<?php echo form_close(); ?>
	</div>
</div>

<br><br><br><br>
