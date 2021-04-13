<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="<?= $this->security->get_csrf_token_name(); ?>" content="<?= $this->security->get_csrf_hash(); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/favicon.png') ?>">

    <title><?= $this->lang->line('log_in') ?></title>

    <link href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/toastr-master/css/toastr.min.css') ?>" rel="stylesheet">

    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/auth.css') ?>" rel="stylesheet">
</head>

<body data-flash-type="<?= !empty($this->session->flashdata('data')['type']) ? $this->session->flashdata('data')['type'] : null ?>" data-flash-message="<?= !empty($this->session->flashdata('data')['message']) ? $this->session->flashdata('data')['message'] : null ?>">

    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>

    <section id="wrapper">

        <div class="login-register absolute">

            <div class="text-center logo">
            </div>

            <div class="login-box card">
                <div class="card-body">
                    <div id="messages"></div>

                    <input class="form-control" type='hidden' name="user_id" id='user_id' />
                    <input class="form-control" type='hidden' name="user_salt" id='user_salt' />

                    <form class="form-horizontal form-material check-username" id="login-form" action="<?= site_url('auth/authenticate') ?>" method="post" data-first-action="<?= base_url("auth/validate_username") ?>">
                        <h3 class="box-title mb-3 text-center">Log In</h3>
                        <div class="form-group ">
                            <div class="col-xs-12 input-group">
                                <div class="input-group-append">
                                    <button class="btn border-icon" type="button">
                                        <i class="ti-user"></i>
                                    </button>
                                </div>
                                <input class="form-control" id="username" name="username" type="text" placeholder="Username" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group hide-password hide">
                            <div class="col-xs-12 input-group">
                                <div class="input-group-append">
                                    <button class="btn border-icon" type="button">
                                        <i class="ti-lock"></i>
                                    </button>
                                </div>
                                <input class="form-control with-icon" id="password" name="password" type="password" placeholder="Password" autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn border-icon" type="button">
                                        <i class="fa fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group hide-password hide">
                            <div class="col-xs-12">
                                <div class="checkbox checkbox-success float-left pt-0">
                                    <input id="checkbox-signup" type="checkbox">
                                    <label for="checkbox-signup">&nbsp;<?= $this->lang->line('remember_me') ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center mt-3">
                            <div class="col-xs-12">
                                <button type="submit" id="btn-submit-login" class="btn btn-rounded btn-outline-success btn-lg btn-block text-uppercase waves-effect waves-light">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <script>
        baseUrl = (path) => {
            var url = '<?= base_url() ?>';
            return url + path;
        }
    </script>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/jquery/jquery.backstretch.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/jquery-validate/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/waves/waves.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/toastr-master/js/toastr.min.js') ?>"></script>

    <script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
    <script src="<?= base_url('assets/js/custom.js') ?>"></script>
    <script src="<?= base_url('assets/js/func.js') ?>"></script>
    <script src="<?= base_url('assets/js/auth.js') ?>"></script>

</body>

</html>