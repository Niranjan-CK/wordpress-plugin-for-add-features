<?php
/**
 * @package    Feedback
 *
 * Add a dashboard widget.
 */

/**
 * Dashboard to add widgets and control register_activation_hook and register_deactivation_hook
 */
class Dashboard {

	/**
	 * Activation hook
	 */
	public static function activate() {
		$feedback_page = array(
			'post_title'   => 'Feedback',
			'post_content' => '[feedback_form]',
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_name'    => 'feedback',
			'post_slug'    => 'feedback',
			'post_type'    => 'page',
		);
		wp_insert_post( $feedback_page );

		flush_rewrite_rules();
	}

	/**
	 * Deactivation hook
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Add a dashboard widget
	 */
	public static function add_feedback_page() {
	}

	/**
	 * Enqueue script
	 */
	public static function enqueue_script() {
		wp_enqueue_style( 'products-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '1.0.0' );
		wp_enqueue_script( 'products-script', PRODUCT_BASEFILE . 'assets/script.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'products-jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script(
			'products-jquery',
			'wp_feedback_vote',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonces'   => array(
					'wt_ddfw_nonce' => wp_create_nonce( 'products-jquery' ),
				),

			)
		);
	}
}
