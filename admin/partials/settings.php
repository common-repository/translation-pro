<?php
	if (isset($_SERVER['HTTPS']) &&
	    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
	    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
	    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$protocol = 'https://';
	} else {
		$protocol = 'http://';
	}
	$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url = add_query_arg('login', 'true', $current_url);

    $showLogin = false;
    if(isset($_GET['login']) && $_GET['login'])
        $showLogin = true;

?>

<div class="wrap">
	<div class="main_header">
		<img src="<?php echo plugin_dir_url( __DIR__ ); ?>img/contentlocalized_logo.png"/>
		<h1>
			Translation.Pro			
			<span>
				by Content Localized
			</span>
		</h1>						
	</div>
    <div class="settings_wrapper">
        <h2>Settings</h2>
        <div class="settings">
			<?php
				if ( get_option( 'cltp_api_pass_test' ) == '0' ) {
					?>
                    <div id="login_area"  <?php echo $showLogin ? "" : 'style="display:none"'; ?>>
                        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                            <div id="login_area">
                                <h3>LOGIN</h3>
                                <input type="hidden" name="action" value="cltp_login_form">
                                <input type="text" name="username" size="30" value="" placeholder="Enter your email">
                                <input type="password" name="password" size="30" value="" placeholder="Enter your password">
                                <input type="submit" value="LOGIN"/>
                                <p>-- OR --</p>
                                <input id="create_account" type="button" value="ACTIVATE PLUGIN"/>
                            </div>
                        </form>
                    </div>
                    <div id="registration_area"  <?php echo !$showLogin ? "" : 'style="display:none"'; ?>>
                        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                            <h3>ACTIVATE PLUGIN</h3>
                            <input type="hidden" name="action" value="cltp_signup_form">
                            <input type="hidden" name="url" value="<?php echo $current_url; ?>">
                            <input type="text" name="first_name" size="30" value="" placeholder="First name">
                            <input type="text" name="last_name" size="30" value="" placeholder="Last name">
                            <input type="text" name="email" size="30" value="" placeholder="E-mail address">
                            <input type="password" name="password" size="30" value="" placeholder="Password">
                            <input type="submit" value="ACTIVATE PLUGIN"/>
                            <p>-- OR --</p>
                            <input id="back_to_login" type="button" class="danger" onclick="" value="LOGIN"/>
                        </form>
                    </div>
					<?php
				} else {
					?>
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <input type="hidden" name="action" value="cltp_unlink_login_details">
                        <input type="submit" value="LOGOUT" style="background-color: #f72a2a;"/>
                    </form>
					<?php
				}
			?>
        </div>
    </div>
    <?php require_once plugin_dir_path(dirname( __FILE__ ))."partials/balance.php"; ?>
</div>


