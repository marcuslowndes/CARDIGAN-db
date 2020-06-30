<div class="container-fluid bg-success">
	<div class="container">
		<br>
		<h2><?= $title ?></h2>
		<br>
		<div style='color: #D55342 !important'>
			<?php echo validation_errors(); ?>
		</div>
		<br>

		<?php echo form_open('contact'); ?>

		<div class="row">
			<div class="col">
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" class="form-control" name="name"
					 	value="<?= $name ?>" required autofocus>
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" name="email"
					 	value="<?= $email ?>" required autofocus>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="subject">Subject</label>
			<input type="text" class="form-control" name="subject"
				required autofocus>
		</div>

		<div class="form-group">
			<label for="message">Ask us anything</label>
			<textarea class="form-control" name="message" rows="3"
				required autofocus></textarea>
		</div>

		<div class="form-group captcha">
		    <label for="user_captcha"><?php echo $captcha['image']; ?></label>
		    <br>
		    <input type="text" autocomplete="off" name="user_captcha"
				placeholder="Enter above text" class="form-control"
				value="<?php if(!empty($user_captcha)) echo $user_captcha; ?>" />
		</div>

		<button class="btn btn-primary btn-lg" type="submit" id="load-btn"
				style="width: 10rem"> Send </button>

		<?php echo form_close(); ?>
		<br><br>
	</div>
</div>
