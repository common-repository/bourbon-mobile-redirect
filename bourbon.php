<?php

/**

 * @package Bourbon
 * @version 0.1
 */
/*
Plugin Name: Bourbon Mobile Redirect
Description: Add code to your template to enable the redirect to your Bourbon mobile site.
Author: Bourbon
Version: 0.1
Author URI: http://getbourbon.com/
License: GPL2

	Copyright 2012  Bourbon LLC  (email : info@getbourbon.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/



add_action('admin_init', 'bourbon_admin_init');
add_action('admin_menu', 'bourbon_admin_add_page');
add_action('wp_head', 'bourbon_template_output');

function bourbon_admin_add_page() {
	add_options_page('Bourbon Mobile Redirect Settings', 'Bourbon Mobile Redirect', 'manage_options', 'bourbon', 'bourbon_options_page');
}

// display the admin options page
function bourbon_options_page() {
?>
<div class="wrap">
	<h2>Bourbon Mobile Redirect</h2>
	<form action="options.php" method="post">
		<?php settings_fields('bourbon_options_group'); ?>
		<?php do_settings_sections('bourbon'); ?>

		<p class="submit"><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" class="button-primary" /></p>
	</form>
</div>

<?php
}

function bourbon_admin_init(){
	register_setting( 'bourbon_options_group', 'bourbon_options', 'bourbon_options_validate' );
	add_settings_section('bourbon_main', '', 'bourbon_section_text', 'bourbon');
	add_settings_field('bourbon_token', 'Bourbon Token', 'bourbon_setting_string', 'bourbon', 'bourbon_main');
	add_settings_field('bourbon_enabled', 'Enable Redirect?', 'bourbon_setting_checkbox', 'bourbon', 'bourbon_main');
}

function bourbon_section_text() {
echo '<p>You can find your token by visiting <a href="https://manage.getbourbon.com/publish/redirect/" target="_blank">https://manage.getbourbon.com/publish/redirect/</a></p>';
}

function bourbon_setting_checkbox() {
$options = get_option('bourbon_options');
echo "<input id='bourbon_token' type='checkbox' name='bourbon_options[enabled]' value='on'".(empty($options['enabled'])? '': 'checked="checked"')." />";
}

function bourbon_setting_string() {
$options = get_option('bourbon_options');
echo "<input id='bourbon_token' name='bourbon_options[token]' size='5' type='text' value='{$options['token']}' />";
}

function bourbon_options_validate($input) {
	$newinput['token'] = trim($input['token']);
	$newinput['enabled'] = !empty($input['enabled']);
	return $newinput;
}


// output script to template (try to do it before everything else)

function bourbon_template_output() {
	$options = get_option('bourbon_options');
	if (!empty($options['enabled'])) {
		echo '<script type="text/javascript" src="//static.brbn.co/'.$options['token'].'/redirect.js"></script>'."\n";
	}
}


