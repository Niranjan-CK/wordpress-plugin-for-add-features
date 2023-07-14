<?php
/**
 * @package    Feedback
 */

/**
 * Plugin Name: Feedback
 * Description: A plugin to allow users to submit feedback of products
 * Version: 1.0.0
 * Author: Niranjan C K
 * Text Domain: feedback
 */


	require __DIR__ . '/includes/init.php';
	defined( 'ABSPATH' ) || die( 'not working' );

	/**
	 * Create constant value of base file.
	 */
	if ( ! defined( 'PRODUCT_BASEFILE' ) ) {
		define( 'PRODUCT_BASEFILE', plugin_dir_url( __FILE__ ) );
	}

	register_activation_hook( __FILE__, array( 'Dashboard', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Dashboard', 'deactivate' ) );

	add_action( 'admin_enqueue_scripts', array( 'Dashboard', 'enqueue_script' ) );
	add_action( 'wp_enqueue_scripts', array( 'Dashboard', 'enqueue_script' ) );

	// Forms.
	add_action( 'init', array( 'Forms', 'add_board' ) );
	add_action( 'init', array( 'Forms', 'feedback_form' ) );
	add_action( 'wp_ajax_update_vote_like', array( 'Database', 'vote_like_feedback' ) );
	add_action( 'wp_ajax_update_vote_dislike', array( 'Database', 'vote_dislike_feedback' ) );







	// Shortcode.
	add_shortcode( 'feedback_form', array( 'Feedback', 'feedback' ) );
	add_shortcode( 'feedbacks_of_product', array( 'Products', 'feedbacks_of_product' ) );
