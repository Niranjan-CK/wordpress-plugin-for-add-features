<?php
/**
 * @package Feedback
 */

/**
 * Handle the form submission
 */
class Forms {

	/**
	 * Handle the form submission
	 */
	public static function add_board() {
		if ( ! isset( $_POST['create_post_nonce'] ) || ! wp_verify_nonce( $_POST['create_post_nonce'], 'create_post_nonce' ) ) {
			return;
		}
		if ( isset( $_POST['create_board'] ) ) {
			$board_name        = $_POST['board_name'];
			$board_description = $_POST['board_description'];
			$user_id           = get_current_user_id();

			$board = array(
				'post_title'  => $board_name,
				'post_author' => $user_id,
				'post_status' => 'publish',
				'post_type'   => 'page',
				'post-slug'   => $board_name,
			);

			$post_id = wp_insert_post( $board );
			if ( $post_id ) {
				wp_update_post(
					array(
						'ID'           => $post_id,
						'post_content' => '[feedbacks_of_product board_id=' . $post_id . ']',
					)
				);
				Database::create_board( $post_id );
			} else {
				echo 'failed';
			}
		}
	}

	/**
	 * Create feedback
	 */
	public static function create_feedback() {
		?>
			<!-- Create modal -->
			<div class="modal" id="feedback_modal">
				<div class="modal-dialog modal-dialog-centered" style="width:300px;">
					<div class="modal-content card " style="padding:40px;">
						<!-- Modal Header -->
						<div class="row">
								<h4 class="modal-title col">Create new Post</h4>
								<button type="button" class="close btn" style="width:40px;height:40px; color:black; display:hidden;" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body " style="margin:auto;">
								<!-- <span class="close">&times;</span> -->
								<form method="post">
									<span>
										<?php
											$board_details = Database::fetch_board_details();
										if ( is_array( $board_details ) ) {
											?>
												<h4><?php echo esc_html( $board_details['board_name'] ); ?></h4>
												<?php
										}
										?>


									</span>

									<?php wp_nonce_field( 'create_feedback_nonce', 'create_feedback_nonce' ); ?>
									<label for="fb_title">TITLE</label>
									<input type="text" name="fb_title" id="fb_title" class="form-control" placeholder="Short, descriptive title" required>
									<label for="fd_details">DETAILS</label>
									<textarea name="fd_details" id="fd_details" class="form-control" placeholder="Board description" required ></textarea>
									<button type="submit" class="btn btn-primary mt-2" name="create_feedback" value="<?php echo esc_html( $board_details['board_id'] ); ?>">Create</button>
								</form>
							</div>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Feedback from submission
	 */
	public static function feedback_form() {
		if ( ! isset( $_POST['create_feedback_nonce'] ) || ! wp_verify_nonce( $_POST['create_feedback_nonce'], 'create_feedback_nonce' ) ) {
			return;
		}
		if ( isset( $_POST['create_feedback'] ) ) {

			$fb_title   = $_POST['fb_title'];
			$fb_details = $_POST['fd_details'];
			$fd_id      = $_POST['create_feedback'];
			$user_id    = get_current_user_id();

			$data = array(
				'fb_title'       => $fb_title,
				'fb_description' => $fb_details,
				'fd_id'          => $fd_id,
				'user_id'        => $user_id,
			);

			global $wpdb;
			$table_name = $wpdb->prefix . 'product_feedback';
			$query      = $wpdb->query( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );
			if ( $query !== $table_name ) {
				Database::create_feedback_table( $table_name, $data );
			} else {
				Database::insert_feedback( $table_name, $data );
			}
		}
	}
}
