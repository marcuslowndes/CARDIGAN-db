<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#load-btn").click(function() {
				var attr = $(this).attr('href');
				if (typeof attr !== typeof undefined && attr !== false){
					// disable button
					$(this).prop("disabled", true);
					// add spinner to button
					$(this).html('<span class="spinner-border" role="status"'
						+ ' aria-hidden="true"></span>');
				}
			});
		});
	</script>
	<title>CARDIGAN Project - <?= $title ?></title>
</head>

<body>
	<!--Nav Bar-->
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top"
	 		id="navbar" style="border-bottom: solid 3px #D55342;
				-webkit-filter: drop-shadow(0 1px 2px rgba(0,0,0,0.5));
				filter: drop-shadow(0 1px 2px rgba(0,0,0,0.5));">
		<a class="navbar-brand brand" style="margin-left:12px;
				font-size:100% !important;" href="index">
			CARDIGAN
		</a>

		<img src="assets/images/logos/CARDIGAN_logo_icon.jpg" alt="CARDIGAN Logo"
		 	title="CARDIGAN Logo" style="height:3rem; margin-right: 1rem" class="rounded-circle">

		<div class="navbar-collapse collapse show" id="navbar-main" style="">
			<ul class='navbar-nav mr-auto' id='navbar-container'>
				<li class="nav-item">
					<a class="nav-link" id='about' href='about'> About </a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id='gallery' href='gallery'> Gallery </a>
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

	<div class="bg-primary nav-bg"><br></div>

	<div class="main" id="top">
		<?php
			// Flash Messages:
            if($this->session->flashdata('user_success')){
				echo '<p class="alert alert-success">'
					. $this->session->flashdata('user_success') . '</p>';
            }

            if($this->session->flashdata('user_warning')){
                echo '<p class="alert alert-warning">'
					. $this->session->flashdata('user_warning') . '</p>';
            }

            if($this->session->flashdata('user_failed')){
                echo '<p class="alert alert-danger">'
					. $this->session->flashdata('user_failed') . '</p>';
            }
		?>
