<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet"
		href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
		crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<title>CARDIGAN Project - <?= $title ?></title>
</head>

<body>
	<!--Nav Bar-->
	<nav class='navbar navbar-expand-lg navbar-dark bg-primary fixed-top' id="navbar">
		<style media="screen">
		</style>
		<a class='navbar-brand'
				style='font-family:"Lulo-Clean-W01-One-Bold";
							letter-spacing: 0.2rem; margin-left:12px;'
				href='index'>
			CARDIGAN
		</a>

		<div class="navbar-collapse collapse show" id="navbar-main" style="">
			<ul class='navbar-nav mr-auto' id='navbar-container'>
				<li class="nav-item">
					<a class="nav-link" id='about' href='about'> About </a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id='contact' href='contact'> Contact </a>
				</li>

				<?php if($this->session->userdata('logged_in')) : ?>
					<li class="nav-item">
						<a class="nav-link" id='database' href='database'> Database </a>
					</li>
				<?php endif; ?>
			</ul>


			<ul class='navbar-nav' id='navbar-container'>
				<?php if(!$this->session->userdata('logged_in')) : ?>

					<li class="nav-item">
						<a class="nav-link" id='login' href='login'> Log In </a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id='register' href='register'> Register </a>
					</li>

				<?php else : ?>

					<?php if($this->session->userdata('user_type') == 'Admin') : ?>
						<li class="nav-item">
							<a class="nav-link" id='users' href='view_all_users'>
								All Users
							</a>
						</li>
					<?php endif; ?>

					<li class="nav-item">
						<a class="nav-link" id='logout' href='change_password'>
							<?php echo $this->session->userdata('forename') ?>
						</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" id='logout' href='logout'> Log Out </a>
					</li>

				<?php endif; ?>
			</ul>
		</div>
	</nav>

	<div class="bg-primary nav-bg" id="top"><br></div>

	<div class="">
		<?php
			// Flash Messages:
            if($this->session->flashdata('user_success')){
				echo '<p class="alert alert-success" style="border-radius: 0">'
					. $this->session->flashdata('user_success') . '</p>';
            }

            if($this->session->flashdata('user_warning')){
                echo '<p class="alert alert-warning" style="border-radius: 0">'
					. $this->session->flashdata('user_warning') . '</p>';
            }

            if($this->session->flashdata('user_failed')){
                echo '<p class="alert alert-danger" style="border-radius: 0">'
					. $this->session->flashdata('user_failed') . '</p>';
            }
		?>
		<br>
