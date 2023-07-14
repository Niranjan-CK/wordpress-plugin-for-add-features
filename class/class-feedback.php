<?php
/**
 * @package Feedback
 */

/**
 * Feedback class
 */
class Feedback {

	/**
	 * Feedback
	 */
	public static function feedback() {
		?>
			<div class="conatiner p-2" style="">
				<div class="row" style="height:100vh; ">
					<div class="col-2 bg-white border-end" >
						<h1 class="text-center pt-4">Logo</h1>
						<?php
						if ( is_user_logged_in() ) {

							$the_author_id = get_the_author_meta( 'ID' );
							$get_user_url  = get_avatar_url( $the_author_id );
							$user          = wp_get_current_user();
							?>
							<div class="wrapper p-3 m-1 row">
								<div class="rounded-circle col">
									<img class="rounded-circle" src="<?php echo esc_html( $get_user_url ); ?> " alt="something wrong" style="width:50px;height:50px;"/>
								</div>
								
								<p class="col-7"><?php echo esc_html( $user->user_login ); ?></p>
							
								<?php
								$role = $user->roles[0];
								if ( 'administrator' === $role ) {
									?>
									<div class="wrapper mt-2" style="width:auto">
										<span class="bg-opacity-50 bg-success p-1 text-white">Admin</span>
									</div>
									
									
									<div class="wrapper ">
										<button class="btn rounded-circle bg-primary " style="width:40px;height:40px ;float:right; color:white;"id="create_post">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
											</svg>
										</button>
									</div>
									
								

							</div>
									<?php

									Admin::side_left_bar();
								} else {
									echo 'not admin';
								}
						} else {
							Guest::side_left_bar();
						}
						?>
					</div>
					<div class="col-9 pt-4">
						<?php
						if ( ! isset( $_POST['feedback_board_nonce'] ) || ! wp_verify_nonce( $_POST['feedback_board_nonce'], 'feedback_board' ) ) {
							return;
						}

						if ( isset( $_POST['board_id'] ) ) {
							self::feedback_of_board();
						}
						?>
					</div>
							
				</div> 
			</div>
		<?php
	}

	/**
	 * Feedback of board
	 */
	public static function feedback_of_board() {

		?>
			<h2 >Feature request
			<button class="btn rounded-circle bg-primary " style="width:40px;height:40px ; color:white;"id="create_feedback">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
				<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
				<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
				</svg>
			</button>
			
			</h2>
			<?php Forms::create_feedback(); ?>
			<div class="wrapper">
				<div class="card m-3 p-3" style="background-color:#f9fafc;width:80%;">
					<p>
						<?php
						$board_deatils = Database::fetch_board_details();

						?>
						<h3><?php echo esc_html( $board_deatils['board_name'] ); ?></h3>
						<p><?php echo esc_html( $board_deatils['board_description'] ); ?></p>
						</p>	
				</div>
				<div class="">
					<?php
						$board_id             = $board_deatils['board_id'];
						$feedbacks_of_product = Database::fetch_feedback( $board_id );
						Products::list_feedback( $feedbacks_of_product, $board_id );
					?>
				</div>
			</div>
			<?php
	}
}

