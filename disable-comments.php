<?php
/**
 * MU Disable Comments
 *
 * Disables all comments sitewide for Marshall University's WordPress network.
 *
 * @package MU_Disable_Comments
 *
 * Plugin Name:  MU Disable Comments
 * Plugin URI:   https://www.marshall.edu
 * Description:  Disables all comments, pings, and trackbacks sitewide.
 * Version:      1.0.3
 * Author:       Christopher McComas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-mu-disable-comments.php';

/**
 * Returns the main instance of MU_Disable_Comments.
 *
 * @return MU_Disable_Comments
 */
function mu_disable_comments() {
	return MU_Disable_Comments::instance();
}

mu_disable_comments();
