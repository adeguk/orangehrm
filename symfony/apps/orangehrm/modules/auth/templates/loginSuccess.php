<?php $imagePath = theme_path("images/login"); ?>
<style type="text/css">

    /*  body {
          background-color: #FFFFFF;
          height: 700px;
      }
  */
    /*  img {
          border: none;
      }*/
    /*#btnLogin {
        padding: 0;
    }*/
    /* input:not([type="image"]) {
         background-color: transparent;
         border: none;
     }

     input:focus, select:focus, textarea:focus {
         background-color: transparent;
         border: none;
     }

     .textInputContainer {
         font-family: Arial, Helvetica, sans-serif;
         font-size: 11px;
         color: #666666;
     }
 */
    /*#divLogin {
        background: transparent url(<?php echo "{$imagePath}/login.png"; ?>) no-repeat center top;
        height: 520px;
        width: 100%;
        border-style: hidden;
        margin: auto;
        padding-left: 10px;
    }*/

    /*#divUsername {
        padding-top: 153px;
        padding-left: 50%;
    }

    #divPassword {
        padding-top: 35px;
        padding-left: 50%;
    }

    #txtUsername {
        width: 240px;
        border: 0px;
        background-color: transparent;
    }

    #txtPassword {
        width: 240px;
        border: 0px;
        background-color: transparent;
    }

    #txtUsername, #txtPassword {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #666666;
        vertical-align: middle;
        padding-top:0;
    }
    */
    #divLoginHelpLink {
        width: 270px;
        background-color: transparent;
        height: 2px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
        margin-left: 50%;
    }

    /*#forgotPasswordLink {
        width: 270px;
        background-color: transparent;
        height: 20px;
        margin-top: 12px;
        margin-right: 0px;
        margin-bottom: 0px;
        margin-left: 50%;
    }

    #divLoginButton {
        padding-top: 10px;
        padding-left: 49.3%;
        float: left;
        width: 350px;
    }*/

    /*#btnLogin {
        background: url(<?php echo "{$imagePath}/Login_button.png"; ?>) no-repeat;
        cursor:pointer;
        width: 94px;
        height: 26px;
        border: none;
        color:#FFFFFF;
        font-weight: bold;
        font-size: 13px;
    }*/

    /*#divLink {
        padding-left: 230px;
        padding-top: 105px;
        float: left;
    }*/

    #divLogo {padding-top: 20px;padding-bottom: 50px;}
    #spanMessage {
        background: transparent url(<?php echo "{$imagePath}/mark.png"; ?>) no-repeat;
        padding-left: 18px;
        padding-top: 0px;
        color: #DD7700;
        font-weight: bold;
    }

    /* #logInPanelHeading{
         position:absolute;
         padding-top:100px;
         padding-left:49.5%;
         font-family:sans-serif;
         font-size: 15px;
         color: #544B3C;
         font-weight: bold;
     }
     */
    .form-hint {
        color: #878787;
        padding: 4px 8px;
        position: relative;
        left:-254px;
    }

    .loginSuccessMessage {
        font-size: 15px;
        font-weight: bold;
        padding-left: 55px;
        width: 100%;
    }

    body {background: #DCDDDF url(https://cssdeck.com/uploads/media/items/7/7AF2Qzt.png);}
    h1{ font-size:28px;}
    h1{ color:#563D64;}
    small{ font-size:10px;}
    b, strong{ font-weight:bold;}
    a{ text-decoration: none; }
    a:hover{ text-decoration: underline; }
    .left { float:left; }
    .right { float:right; }
    .alignleft { float: left; margin-right: 15px; }
    .alignright { float: right; margin-left: 15px; }
    .clearfix:after,
    form:after {
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden;
    }
    .container { /*margin: 25px auto; position: relative;width: 900px;*/ }
    #loginContent {
        background: #f9f9f9;
        background: -moz-linear-gradient(top,  rgba(248,248,248,1) 0%, rgba(249,249,249,1) 100%);
        background: -webkit-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        background: -o-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        background: -ms-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        background: linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f8f8f8', endColorstr='#f9f9f9',GradientType=0 );
        -webkit-box-shadow: 0 1px 0 #fff inset;
        -moz-box-shadow: 0 1px 0 #fff inset;
        -ms-box-shadow: 0 1px 0 #fff inset;
        -o-box-shadow: 0 1px 0 #fff inset;
        box-shadow: 0 1px 0 #fff inset;
        border: 1px solid #c4c6ca;
        margin: 10% auto 0;
        padding: 25px 0 0;
        position: relative;
        text-align: center;
        text-shadow: 0 1px 0 #fff;
        width: 400px;
    }
    #loginContent h1 {
        color: #7E7E7E;
        font: bold 25px Helvetica, Arial, sans-serif;
        letter-spacing: -0.05em;
        line-height: 20px;
        margin: 10px 0 30px;
    }
    #loginContent h1:before,#loginContent h1:after {
        content: "";
        height: 1px;
        position: absolute;
        top: 10px;
        width: 27%;
    }
    #loginContent h1:after {
        background: rgb(126,126,126);
        background: -moz-linear-gradient(left,  rgba(126,126,126,1) 0%, rgba(255,255,255,1) 100%);
        background: -webkit-linear-gradient(left,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        background: -o-linear-gradient(left,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        background: -ms-linear-gradient(left,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        background: linear-gradient(left,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        right: 0;
    }
    #loginContent h1:before {
        background: rgb(126,126,126);
        background: -moz-linear-gradient(right,  rgba(126,126,126,1) 0%, rgba(255,255,255,1) 100%);
        background: -webkit-linear-gradient(right,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        background: -o-linear-gradient(right,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        background: -ms-linear-gradient(right,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        background: linear-gradient(right,  rgba(126,126,126,1) 0%,rgba(255,255,255,1) 100%);
        left: 0;
    }
    #loginContent:after,#loginContent:before {
        background: #f9f9f9;
        background: -moz-linear-gradient(top,  rgba(248,248,248,1) 0%, rgba(249,249,249,1) 100%);
        background: -webkit-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        background: -o-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        background: -ms-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        background: linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f8f8f8', endColorstr='#f9f9f9',GradientType=0 );
        border: 1px solid #c4c6ca;
        content: "";
        display: block;
        height: 100%;
        left: -1px;
        position: absolute;
        width: 100%;
    }
    #loginContent:after {
        -webkit-transform: rotate(2deg);
        -moz-transform: rotate(2deg);
        -ms-transform: rotate(2deg);
        -o-transform: rotate(2deg);
        transform: rotate(2deg);
        top: 0;	z-index: -1;
    }
    #loginContent:before {
        -webkit-transform: rotate(-3deg);
        -moz-transform: rotate(-3deg);
        -ms-transform: rotate(-3deg);
        -o-transform: rotate(-3deg);
        transform: rotate(-3deg);
        top: 0;	z-index: -2;
    }
    #loginContent form { margin: 0 20px; position: relative }
    #loginContent form input[type="text"],#loginContent form input[type="password"] {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        -o-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 0 #fff, 0 -2px 5px rgba(0,0,0,0.08) inset;
        -moz-box-shadow: 0 1px 0 #fff, 0 -2px 5px rgba(0,0,0,0.08) inset;
        -ms-box-shadow: 0 1px 0 #fff, 0 -2px 5px rgba(0,0,0,0.08) inset;
        -o-box-shadow: 0 1px 0 #fff, 0 -2px 5px rgba(0,0,0,0.08) inset;
        box-shadow: 0 1px 0 #fff, 0 -2px 5px rgba(0,0,0,0.08) inset;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -ms-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
        background: #eae7e7 url(https://cssdeck.com/uploads/media/items/8/8bcLQqF.png) no-repeat;
        border: 1px solid #c8c8c8;
        color: #777;
        font: 13px Helvetica, Arial, sans-serif;
        margin: 0 0 10px;
        padding: 15px 10px 15px 40px;
        width: 80%;
    }
    #loginContent form input[type="text"]:focus,#loginContent form input[type="password"]:focus {
        -webkit-box-shadow: 0 0 2px #ed1c24 inset;
        -moz-box-shadow: 0 0 2px #ed1c24 inset;
        -ms-box-shadow: 0 0 2px #ed1c24 inset;
        -o-box-shadow: 0 0 2px #ed1c24 inset;
        box-shadow: 0 0 2px #ed1c24 inset;
        background-color: #fff;
        border: 1px solid #ed1c24;
        outline: none;
    }
    #txtUsername {background-position: 10px 10px !important}
    #txtPassword {background-position: 10px -53px !important}
    #loginContent form input[type="submit"] {
        background: rgb(254,231,154);
        background: -moz-linear-gradient(top,  rgba(254,231,154,1) 0%, rgba(254,193,81,1) 100%);
        background: -webkit-linear-gradient(top,  rgba(254,231,154,1) 0%,rgba(254,193,81,1) 100%);
        background: -o-linear-gradient(top,  rgba(254,231,154,1) 0%,rgba(254,193,81,1) 100%);
        background: -ms-linear-gradient(top,  rgba(254,231,154,1) 0%,rgba(254,193,81,1) 100%);
        background: linear-gradient(top,  rgba(254,231,154,1) 0%,rgba(254,193,81,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fee79a', endColorstr='#fec151',GradientType=0 );
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        -ms-border-radius: 30px;
        -o-border-radius: 30px;
        border-radius: 30px;
        -webkit-box-shadow: 0 1px 0 rgba(255,255,255,0.8) inset;
        -moz-box-shadow: 0 1px 0 rgba(255,255,255,0.8) inset;
        -ms-box-shadow: 0 1px 0 rgba(255,255,255,0.8) inset;
        -o-box-shadow: 0 1px 0 rgba(255,255,255,0.8) inset;
        box-shadow: 0 1px 0 rgba(255,255,255,0.8) inset;
        border: 1px solid #D69E31;
        color: #85592e;
        cursor: pointer;
        float: left;
        font: bold 15px Helvetica, Arial, sans-serif;
        height: 35px;
        margin: 20px 0 35px 15px;
        position: relative;
        text-shadow: 0 1px 0 rgba(255,255,255,0.5);
        width: 120px;
    }
    #loginContent form input[type="submit"]:hover {
        background: rgb(254,193,81);
        background: -moz-linear-gradient(top,  rgba(254,193,81,1) 0%, rgba(254,231,154,1) 100%);
        background: -webkit-linear-gradient(top,  rgba(254,193,81,1) 0%,rgba(254,231,154,1) 100%);
        background: -o-linear-gradient(top,  rgba(254,193,81,1) 0%,rgba(254,231,154,1) 100%);
        background: -ms-linear-gradient(top,  rgba(254,193,81,1) 0%,rgba(254,231,154,1) 100%);
        background: linear-gradient(top,  rgba(254,193,81,1) 0%,rgba(254,231,154,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fec151', endColorstr='#fee79a',GradientType=0 );
    }
    #loginContent form div a {
        color: #004a80;
        float: right;
        font-size: 12px;
        margin: 30px 15px 0 0;
        text-decoration: underline;
    }
    .button {
        background: rgb(247,249,250);
        background: -moz-linear-gradient(top,  rgba(247,249,250,1) 0%, rgba(240,240,240,1) 100%);
        background: -webkit-linear-gradient(top,  rgba(247,249,250,1) 0%,rgba(240,240,240,1) 100%);
        background: -o-linear-gradient(top,  rgba(247,249,250,1) 0%,rgba(240,240,240,1) 100%);
        background: -ms-linear-gradient(top,  rgba(247,249,250,1) 0%,rgba(240,240,240,1) 100%);
        background: linear-gradient(top,  rgba(247,249,250,1) 0%,rgba(240,240,240,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7f9fa', endColorstr='#f0f0f0',GradientType=0 );
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.1) inset;
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.1) inset;
        -ms-box-shadow: 0 1px 2px rgba(0,0,0,0.1) inset;
        -o-box-shadow: 0 1px 2px rgba(0,0,0,0.1) inset;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1) inset;
        -webkit-border-radius: 0 0 5px 5px;
        -moz-border-radius: 0 0 5px 5px;
        -o-border-radius: 0 0 5px 5px;
        -ms-border-radius: 0 0 5px 5px;
        border-radius: 0 0 5px 5px;
        border-top: 1px solid #CFD5D9;
        padding: 15px 0;
    }
    .button a {
        background: url(https://cssdeck.com/uploads/media/items/8/8bcLQqF.png) 0 -112px no-repeat;
        color: #7E7E7E;
        font-size: 17px;
        padding: 2px 0 2px 40px;
        text-decoration: none;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -ms-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }
    .button a:hover {background-position: 0 -135px;color: #00aeef;}
    #footer {margin-top: 75px;}
</style>

<!--div>
    <input type="text" class="loginSuccessMessage" id="loginSuccessMessage" value="" readonly="readonly"/>
</div-->

<div class="container">
    <section id="loginContent">
        <div id="divLogo">
            <img src="<?php echo "{$imagePath}/logo.png"; ?>" />
        </div>
        <form id="frmLogin" method="post" action="<?php echo url_for('auth/validateCredentials'); ?>">
            <input type="hidden" name="actionID"/>
            <input type="hidden" name="hdnUserTimeZoneOffset" id="hdnUserTimeZoneOffset" value="0" />
            <?= $form->renderHiddenFields(); /* rendering csrf_token */ ?>

            <h1><?php echo __('LOGIN Panel'); ?></h1>
            <div>
                <?php echo $form['Username']->render(); ?>
                <span class="form-hint" ><?php echo __('Username'); ?></span>
            </div>
            <div>
                <?php echo $form['Password']->render(); ?>
                <span class="form-hint" ><?php echo __('Password'); ?></span>
            </div>
            <div id="divLoginHelpLink">
                <?php  include_component('core', 'ohrmPluginPannel', array(
                    'location' => 'login-page-help-link',
                ));  ?>
            </div>
            <div>
                <input type="submit" name="Submit" id="btnLogin" value="<?php echo __('LOGIN'); ?>" />
                <a href="<?php echo url_for('auth/requestPasswordResetCode'); ?>"><?php echo __('Forgot your password?'); ?></a>
            </div>
            <?php if (!empty($message)) : ?>
                <span id="spanMessage"><?php echo __($message); ?></span>
            <?php endif; ?>
        </form><!-- form -->
        <div class="button">
            <a href="#">or <strong>Apply to Become a Citizen</strong></a>
        </div><!-- button -->
    </section><!-- content -->
</div><!-- container -->

<div style="text-align: center">
    <?php include_component('core', 'ohrmPluginPannel', array(
        'location' => 'other-login-mechanisms',
    )); ?>
</div>

<?php include_partial('global/footer_copyright_social_links'); ?>

<script type="text/javascript">
    function calculateUserTimeZoneOffset() {
        var myDate = new Date();
        var offset = (-1) * myDate.getTimezoneOffset() / 60;
        return offset;
    }

    function addHint(inputObject, hintImageURL) {
        if (inputObject.val() == '') {
            inputObject.css('background', "url('" + hintImageURL + "') no-repeat 10px 3px");
        }
    }

    function removeHint() {
        $('.form-hint').css('display', 'none');
    }

    function showMessage(message) {
        if ($('#spanMessage').size() == 0) {
            $('<span id="spanMessage"></span>').insertAfter('#btnLogin');
        }
        $('#spanMessage').html(message);
    }

    function validateLogin() {
        var isEmptyPasswordAllowed = false;

        if ($('#txtUsername').val() == '') {
            showMessage('<?php echo __('Username cannot be empty'); ?>');
            return false;
        }

        if (!isEmptyPasswordAllowed) {
            if ($('#txtPassword').val() == '') {
                showMessage('<?php echo __('Password cannot be empty'); ?>');
                return false;
            }
        }
        return true;
    }

    function refreshSession() {
        setTimeout(function() {
            location.reload();
        }, 20 * 60 * 1000);
    }

    $(document).ready(function() {
        if ($('#installation').val())  {
            var login = $('#installation').val();
            $("#loginSuccessMessage").attr("value", login);
        }

        refreshSession();

        /*Set a delay to compatible with chrome browser*/
        setTimeout(checkSavedUsernames,100);

        $('#txtUsername').focus(function() {
            removeHint();
        });
        $('#txtPassword').focus(function() {
            removeHint();
        });
        $('.form-hint').click(function(){
            removeHint();
            $('#txtUsername').focus();
        });
        $('#hdnUserTimeZoneOffset').val(calculateUserTimeZoneOffset().toString());

        $('#frmLogin').submit(validateLogin);
    });

    function checkSavedUsernames(){
        if ($('#txtUsername').val() != '') {
            removeHint();
        }
    }
    if (window.top.location.href != location.href) {
        window.top.location.href = location.href;
    }
</script>
