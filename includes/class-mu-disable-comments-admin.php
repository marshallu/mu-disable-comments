<?php
/**
 * Admin-side comment suppression.
 *
 * Removes all comment-related UI from the WordPress dashboard: the top-level
 * menu, the discussion settings page, the dashboard widget, the post-list
 * comments column, the editor meta boxes, and the admin bar node. Direct
 * URL access to any comments screen is redirected to the dashboard.
 *
 * @package MU_Disable_Comments
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MU_Disable_Comments_Admin class.
 */
class MU_Disable_Comments_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'remove_menu_pages' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'remove_admin_bar_items' ) );
		add_action( 'admin_head-index.php', array( $this, 'hide_glance_comment_count' ) );
		add_filter( 'manage_posts_columns', array( $this, 'remove_comments_column' ) );
		add_filter( 'manage_pages_columns', array( $this, 'remove_comments_column' ) );
	}

	/**
	 * Remove the Comments top-level menu page.
	 */
	public function remove_menu_pages() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Handle admin-init tasks: redirect restricted pages, remove widgets and
	 * meta boxes, and strip comment/trackback support from all post types.
	 */
	public function admin_init() {
		$this->redirect_comments_pages();
		$this->remove_dashboard_widget();
		$this->remove_editor_meta_boxes();
		$this->remove_post_type_support();
	}

	/**
	 * Redirect any direct access to comment-related admin screens.
	 */
	private function redirect_comments_pages() {
		global $pagenow;

		$blocked = array( 'edit-comments.php', 'comment.php', 'options-discussion.php' );

		if ( in_array( $pagenow, $blocked, true ) ) {
			wp_safe_redirect( admin_url() );
			exit;
		}
	}

	/**
	 * Remove the Recent Comments dashboard widget.
	 */
	private function remove_dashboard_widget() {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	/**
	 * Remove the Comments and Allow Comments meta boxes from all post editors.
	 */
	private function remove_editor_meta_boxes() {
		foreach ( get_post_types() as $post_type ) {
			remove_meta_box( 'commentsdiv', $post_type, 'normal' );
			remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
		}
	}

	/**
	 * Strip comment and trackback support from every registered post type.
	 */
	private function remove_post_type_support() {
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Remove the Comments node from the admin bar.
	 */
	public function remove_admin_bar_items() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

	/**
	 * Hide the comment count item in the At a Glance dashboard widget.
	 */
	public function hide_glance_comment_count() {
		echo '<style>#dashboard_right_now .comment-count, #dashboard_right_now .comment-mod-count { display: none; }</style>';
	}

	/**
	 * Remove the comments column from post list tables.
	 *
	 * @param array $columns The current list-table columns.
	 * @return array
	 */
	public function remove_comments_column( $columns ) {
		unset( $columns['comments'] );
		return $columns;
	}
}
