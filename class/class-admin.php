<?php
/**
 * @package    Feedback
 */

/**
 * Handle the admin area functionality.
 */
class Admin {

	/**
	 * Admin sidebar
	 */
	public static function side_left_bar() {
		?>
			
			<!--  Modal creation -->

			<div class="modal " id="post_modal" >
				<div class="modal-dialog modal-dialog-centered" style="width:300px;">
					<div class="modal-content card " style="padding:40px;">
							<!-- Modal Header -->
							<div class="row">
								<h4 class="modal-title col">Create new Post</h4>
								<button type="button" class="close btn" style="width:40px;height:40px; color:black; display:hidden;" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body " style="width:200px;height:200px;margin:auto;">
								<!-- <span class="close">&times;</span> -->
								<form method="post">
									<?php wp_nonce_field( 'create_post_nonce', 'create_post_nonce' ); ?>
									<label for="board_name">BOARD NAME</label>
									<input type="text" name="board_name" id="board_name" class="form-control" placeholder="Board name" required>
									<label for="board_description">BOARD DESCRIPTION</label>
									<input type="text" name="board_description" id="board_description" class="form-control" placeholder="Board description" required>
									<button type="submit" class="btn btn-primary mt-2" name="create_board">Create</button>
								</form>
							</div>
					</div>
				</div>
			</div>
			<!-- End model -->

			<!-- Product List -->
			<div class="">
				<div class="wrapper">
					<?php echo esc_html( Database::fetch_board_id() ); ?>
				</div>
			</div>
		<?php
	}
}