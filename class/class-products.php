<?php
/**
 * @package    Products
 */

/**
 * Record product feedback.
 */
class Products {

	/**
	 * See each product feedback.
	 *
	 * @param array $atts The board id.
	 */
	public static function feedbacks_of_product( $atts ) {
		$board_id             = $atts['board_id'];
		$feedbacks_of_product = Database::fetch_feedback( $board_id );
		self::list_feedback( $feedbacks_of_product, $board_id );
	}

	/**
	 * Vote for each product feedback.
	 *
	 * @param int $board_id The board id.
	 * @param int $feedback_id The feedback id.
	 */
	public static function vote( $board_id, $feedback_id ) {
		?>
		<form  id ="vote" method="post" >
			<?php wp_nonce_field( 'vote', 'vote_nonce' ); ?>

			<button  class="btn btn-primary mt-2" name="like_feedback" onclick="submit_like_form(event)" id ="<?php echo esc_html( $feedback_id ); ?>" value="<?php echo esc_html( $board_id ) . ',' . esc_html( $feedback_id ); ?>">+</button>
			<?php $likes = Database::total_likes( $board_id, $feedback_id ); ?>
			<span id="<?php echo esc_html( 'text' . $feedback_id ); ?>"><?php echo esc_html( $likes ); ?></span>
		</form>
		<?php
	}

	/**
	 *
	 * List Products.
	 *
	 * @param array $feedbacks_of_product List of products.
	 * @param int   $board_id The board id.
	 */
	public static function list_feedback( $feedbacks_of_product, $board_id ) {
		if ( $feedbacks_of_product ) {
			foreach ( $feedbacks_of_product as $feedback ) {
				?>
				<div class="card m-3 p-3" style="background-color:#f9fafc;width:80%;">
			
					<div class="row">
						<div class="col-1 card">

							<?php self::vote( $board_id, $feedback['id'] ); ?>
						</div>
						<div class="col-5">
							<h3><?php echo esc_html( $feedback['fb_title'] ); ?></h3>
							<p><?php echo esc_html( $feedback['fb_description'] ); ?></p>
							<p><?php echo esc_html( $feedback['time'] ); ?></p>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}
}