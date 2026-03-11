<?php
/**
 * Public-side and API comment suppression.
 *
 * Closes comments and pings on all content, empties any existing comment
 * arrays, redirects comment feeds, removes comment endpoints from the REST
 * API, and removes the pingback methods from XML-RPC.
 *
 * @package MU_Disable_Comments
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MU_Disable_Comments_Public class.
 */
class MU_Disable_Comments_Public {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Close comments and pings on all content.
		add_filter( 'comments_open', '__return_false', 20 );
		add_filter( 'pings_open', '__return_false', 20 );

		// Return no comments so themes and templates render nothing.
		add_filter( 'comments_array', '__return_empty_array', 20 );

		// Block comment feeds and the REST API endpoints.
		add_action( 'template_redirect', array( $this, 'redirect_comment_feed' ) );
		add_filter( 'rest_endpoints', array( $this, 'disable_rest_endpoints' ) );

		// Remove pingback methods from XML-RPC.
		add_filter( 'xmlrpc_methods', array( $this, 'disable_xmlrpc_pingback' ) );
	}

	/**
	 * Redirect comment feed requests to the home page.
	 */
	public function redirect_comment_feed() {
		if ( is_comment_feed() ) {
			wp_safe_redirect( home_url() );
			exit;
		}
	}

	/**
	 * Remove the comment endpoints from the REST API.
	 *
	 * @param array $endpoints Registered REST API endpoints.
	 * @return array
	 */
	public function disable_rest_endpoints( $endpoints ) {
		unset( $endpoints['/wp/v2/comments'] );
		unset( $endpoints['/wp/v2/comments/(?P<id>[\d]+)'] );
		return $endpoints;
	}

	/**
	 * Remove pingback methods from the XML-RPC server.
	 *
	 * @param array $methods Registered XML-RPC methods.
	 * @return array
	 */
	public function disable_xmlrpc_pingback( $methods ) {
		unset( $methods['pingback.ping'] );
		unset( $methods['pingback.extensions.getPingbacks'] );
		return $methods;
	}
}
