<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Creative - Start Bootstrap Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url();?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <?php echo link_tag('vendor/font-awesome/css/font-awesome.min.css'); ?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Theme CSS -->
    <link href="<?php echo base_url();?>css/index.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="<?php echo base_url();?>vendor/jquery/jquery.min.js"></script>

</head>

<body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">Decision Tool</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <?php if(!isset($email)) { ?>
                        <a href="<?php echo base_url();?>PublicCon/searchtool">Search Tool</a>
                        <?php } else { ?>
                        <a href="<?php echo base_url();?>PrivateCon/searchtool">Search Tool</a>
                        <?php } ?>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">About</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header>
        <div class="header-content">
            <div class="header-content-inner">
                <h1 id="homeHeading">Mobile Cross-platform development decision tool</h1>
                <hr>
                <p>Use our decision wizard tool to find out which cross-platform mobile development tools are best suited for your next mobile project. Not interested in the wizard? Simply browse the list of tracked cross-platform tools and find out which features they have to offer.</p>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-1 col-md-offset-2 col-md-4 text-center">
                            <?php if(!isset($email)) { ?>
                            <a class="btn btn-cover" href="<?php echo base_url();?>PublicCon/searchtool">
                            <?php } else { ?>
                            <a class="btn btn-cover" href="<?php echo base_url();?>PrivateCon/searchtool">
                            <?php } ?>
                                <i class="fa fa-search fa-5x"></i>
                                <p>Just let me browse...</p>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 text-center">
                            <a class="btn btn-cover" href="<?php echo base_url();?>PublicCon/decisionwizard">
                                <i class="fa fa-magic fa-5x"></i>
                                <p class="">Wizard Tool</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <h2 class="section-heading">Let's Get In Touch!</h2>
                    <hr class="primary">
                    <p>Ready to start your next project with us? That's great! Give us a call or send us an email and we will get back to you as soon as possible!</p>
                </div>
                <div class="col-lg-4 col-lg-offset-2 text-center">
                    <i class="fa fa-phone fa-3x sr-contact"></i>
                    <p>123-456-6789</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fa fa-envelope-o fa-3x sr-contact"></i>
                    <p><a href="mailto:your-email@your-domain.com">feedback@startbootstrap.com</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="<?php echo base_url();?>vendor/scrollreveal/scrollreveal.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="<?php echo base_url();?>js/index.js"></script>

</body>

</html>
