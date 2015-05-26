<?php

defined( 'ABSPATH' ) or die();

register_activation_hook( EMAIL_LOGIN_AUTH_PLUGIN_FILE, 'email_login_auth_plugin_install' );
if ( is_admin() ) {
	add_action( 'admin_init', 'register_email_login_auth_plugin_settings' );
	add_action( 'admin_menu', 'add_email_login_auth_plugin_menu' );
}

function email_login_auth_plugin_install() {
	add_option( 'email-login-auth-email', true );
}

function register_email_login_auth_plugin_settings() {
	register_setting( 'email-login-auth-option-group', 'email-login-auth-email' );
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
				</td>
			</tr>
			',
			email_login_auth_email_checked()
		);
	printf( '<tr>
				<th>%1$s</th>
				<td>%2$s</td>
			</tr>
			',
			__('Status:'),
			email_login_auth_email_status()
		);
	print( '</table>' );
	submit_button();
	print( '</form>' );
	print( '</div>' );
}

function email_login_auth_email_checked() {
	if ( get_option( 'email-login-auth-email' ) ) {
		return ' checked';
	} else {
		return '';
	}
}

function email_login_auth_email_status() {
	if ( get_option( 'email-login-auth-email' ) ) {
		return __('Enabled');
	} else {
		return __('Disabled');
	}
}

