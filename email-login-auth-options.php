<?php

defined( 'ABSPATH' ) or die();

function email_login_auth_get_default( $option ) {
	$defaults = array(
		'email-login-auth-email' => true,
		'email-login-auth-username' => true,
		'email-login-auth-username-admin' => true,
	);
	if ( isset( $defaults[ $option ] ) ) {
		return $defaults[ $option ];
	} else {
		return false;
	}
}

register_activation_hook( EMAIL_LOGIN_AUTH_PLUGIN_FILE, 'email_login_auth_plugin_install' );
if ( is_admin() ) {
	add_action( 'admin_init', 'register_email_login_auth_plugin_settings' );
	add_action( 'admin_menu', 'add_email_login_auth_plugin_menu' );
}

function email_login_auth_plugin_install() {
	add_option( 'email-login-auth-email', email_login_auth_get_default('email-login-auth-email') );
	add_option( 'email-login-auth-username', email_login_auth_get_default('email-login-auth-username') );
	add_option( 'email-login-auth-username-admin', email_login_auth_get_default('email-login-auth-username-admin') );
}

function register_email_login_auth_plugin_settings() {
	register_setting( 'email-login-auth-option-group', 'email-login-auth-email' );
	register_setting( 'email-login-auth-option-group', 'email-login-auth-username' );
	register_setting( 'email-login-auth-option-group', 'email-login-auth-username-admin' );
}

function add_email_login_auth_plugin_menu() {
	add_options_page(
		get_email_login_auth_plugin_name(),
		get_email_login_auth_plugin_name(),
		'manage_options',
		'email_login_auth',
		'email_login_auth_settings_page'
	);
}

function email_login_auth_settings_page() {
	if ( ! get_option( 'email-login-auth-email', email_login_auth_get_default('email-login-auth-email') ) ) {
		update_option( 'email-login-auth-username', email_login_auth_get_default('email-login-auth-username') );
		update_option( 'email-login-auth-username-admin', email_login_auth_get_default('email-login-auth-username-admin') );
	} else if ( ! get_option( 'email-login-auth-username', email_login_auth_get_default('email-login-auth-username') ) ) {
		update_option( 'email-login-auth-username-admin', false );
	}
	print( '<div class="wrap">' );
	printf( '<h2>%s</h2>', get_email_login_auth_plugin_name() );
	print( '<form method="post" action="options.php">' );
	settings_fields( 'email-login-auth-option-group' );
	do_settings_sections( 'email-login-auth-option-group' );
	print( '<table class="form-table">' );
	printf( '<tr>
				<th><label for="email-login-auth-email">Enable login with e-mail</label></th>
				<td>
					<input type="checkbox" name="email-login-auth-email" id="email-login-auth-email"%s>
					<p class="description">Uncheck to restore default WordPress login authentication (with username).</p>
				</td>
			</tr>
			',
			email_login_auth_checked( 'email-login-auth-email' )
		);
	printf( '<tr>
				<th><label for="email-login-auth-username">Enable login with username</label></th>
				<td>
					<input type="checkbox" name="email-login-auth-username" id="email-login-auth-username"%s>
					<p class="description">If \'Enable login with e-mail\' is off, this will be enabled automatically.</p>
				</td>
			</tr>
			',
			email_login_auth_checked( 'email-login-auth-username' )
		);
	printf( '<tr>
				<th><label for="email-login-auth-username-admin">Enable login with \'admin\' username</label></th>
				<td>
					<input type="checkbox" name="email-login-auth-username-admin" id="email-login-auth-username-admin"%s>
					<p class="description">This option is applied only if both options above are on. Uncheck to enhance security.</p>
				</td>
			</tr>
			',
			email_login_auth_checked( 'email-login-auth-username-admin' )
		);
	print( '</table>' );
	submit_button();
	print( '</form>' );
	print( '</div>' );
}

function email_login_auth_checked( $option ) {
	if ( get_option( $option, email_login_auth_get_default($option) ) ) {
		return ' checked';
	} else {
		return '';
	}
}

