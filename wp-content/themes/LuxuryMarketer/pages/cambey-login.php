<?/*
Template Name: Cambey Login
*/
//error_reporting(E_ALL);
//ini_set('display_errors',1);
?>

<?php
$file_root = ld16_get_file_root();
$url_root = get_template_directory_uri();

get_header();

?>

<div  class="section clearfix main galleries">

    <div class="row text">

        <div class="col-lg-12">

            <div class="col-lg-8">

                <div class="col-lg-12">
                    <h1 class="sector category">
                        <a href="/subscriber-login" class="reverse">Log In</a>
                    </h1>
                </div>


                <div class="col-lg-12">


                    <form class="form-horizontal" id="cambey-login" action="<?= $url_root ?>/../../plugins/cambey/login.php" method="POST">
                        <input class="redirect" name="redirect" type="hidden" value="<?php if(array_key_exists('redirect', $_GET)){print $_GET['redirect'];} ?>" />
                        <fieldset>

                            <div class="form-group">
                                <label for="subscriber_email" class="col-lg-2 control-label">Email</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="subscriber_email" name="subscriber_email" placeholder="Email">
                                </div>
                                <span class="hidden" id="email_error">Please enter email</span>
                            </div>

                            <div class="form-group">
                                <label for="subscriber_pass" class="col-lg-2 control-label">Password</label>
                                <div class="col-lg-10">
                                    <input type="password" class="form-control" id="subscriber_pass" placeholder="Password" name="subscriber_pass">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-10 col-lg-offset-2">

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>

                            <div class="form-group login status" style="color: green">
                                

                                <div class="col-lg-10 forgot unlocked">
                                    <a href="https://https://www.cambeywest.com/subscribe2/LXM/?f=pa">Forgot password?</a>
                                </div>

                                <div class="col-lg-10 msg" style="display:none;">

                                </div>

                                <div class="col-lg-10 action">

                                    <a href="https://luxurymarketer.subsmediahub.com/LXM/?f=paid">
                                        Click here to enroll</a>

                                </div>

                            </div>
                        </fieldset>

                    </form>

                </div>

            </div>


            <div class="col-lg-4 headline-list sidebar">

                <?php
                include($file_root . '/inc/sidebar_1.php');
                ?>


            </div>

        </div>

    </div>

</div>
<?php get_footer(); ?>