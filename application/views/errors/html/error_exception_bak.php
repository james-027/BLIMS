<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon" />

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js" ></script>

    <script src="https://kit.fontawesome.com/ed7ebf3abf.js" crossorigin="anonymous"></script>
    <script src="<?=base_url('assets/js/bootstrap-input-spinner.js')?>" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?=base_url('assets/css/lobibox/lobibox.min.css')?>">
    <script src="<?=base_url('assets/js/lobibox/notifications.min.js')?>"></script>

    <script src="<?=base_url('assets/js/moment/moment.js')?>"></script>
    <link rel="stylesheet" href="<?=base_url('assets/css/tempusdominus-datetimepicker/tempusdominus-bootstrap-4.min.css')?>">
    <script src="<?=base_url('assets/js/tempusdominus-datepicker/tempusdominus-bootstrap-4.min.js')?>"></script>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>">

    <title>Chooks-to-Go Delivery - Error Messagesss</title>
    
</head>
    <body>
        <input type="hidden" value="<?=base_url()?>" id="base_url">
        <nav class="navbar navbar-expand-md sticky-top py-0 navbar-menu">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img class="text-center" src="<?=base_url('assets/img/logo.png')?>" height="65px" alt="placeholder-image">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars text-light"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto" style="font-size: 17px;">
                        <li class="nav-item">
                            <a class="nav-link text-light" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="<?= base_url('customer/order') ?>">Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="<?= base_url('customer/track-order') ?>">Track Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="<?= base_url('customer/check-out') ?>">Check Out</a>
                        </li>
                    </ul>
                    
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
		    <div class="d-flex align-items-center justify-content-center h-50 my-5">
		        <div class="align-middle text-center">
		            <img src="<?= base_url('assets/img/svg/bug_fixing.svg') ?>" height="200vh" alt="in_transit"><br>
		            <h3 class="text-muted py-3">Oppsss... their is something wrong. We will fix it.</h3>
		            <a href="<?= base_url() ?>" class="btn btn-danger btn-radius">Go to Product List</a>
		        </div>
		    </div>
		    <div class="py-2"></div>
		</div><br /><br />

        <footer class="footer py-3 w-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8 align-middle">
                        <span class="text-light">© <?=date('Y')?> Bounty Agro Ventures, Inc. (BAVI). All Rights Reserved.</span>
                    </div>
                    <div class="col-md-4">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link text-light" href="https://www.facebook.com/chookstogo/" target="_blank" rel="noreferer">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="https://twitter.com/chookstogoph" target="_blank" rel="noreferer">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="https://www.instagram.com/chookstogoph/" target="_blank" rel="noreferer">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="https://www.youtube.com/channel/UC1mn-pF58NABjDwMoaLeeyQ" target="_blank" rel="noreferer">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <script src="<?=base_url('assets/js/user_script.js')?>"></script>
    </body>
</html>-->

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>An uncaught Exception was encountered</h4>

<p>Type: <?php echo get_class($exception); ?></p>
<p>Message: <?php echo $message; ?></p>
<p>Filename: <?php echo $exception->getFile(); ?></p>
<p>Line Number: <?php echo $exception->getLine(); ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach ($exception->getTrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			File: <?php echo $error['file']; ?><br />
			Line: <?php echo $error['line']; ?><br />
			Function: <?php echo $error['function']; ?>
			</p>
		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>