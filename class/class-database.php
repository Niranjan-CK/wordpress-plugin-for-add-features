<?php
/**
 * @package Feedback
 */

/**
 * Fetch board details form the database
 */
class Database {

	/**
	 * Create a board
	 *
	 * @param int $board_id The board id.
	 */
	public static function create_board( $board_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'feedback_board';
		$query      = $wpdb->query( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );

		if ( $query !== $table_name ) {
			self::create_table( $table_name, $board_id );
		} else {
			self::insert_board( $table_name, $board_id );
		}
	}

	/**
	 * Create a table
	 *
	 * @param string $table_name The table name.
	 * @param int    $board_id The board id.
	 */
	public static function create_table( $table_name, $board_id ) {
		global $wpdb;
		// SQL query to create the table.
		$sql = "CREATE TABLE $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT,
            board_id VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        ) " . $wpdb->get_charset_collate() . ';';
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		// Execute the query to create the table.
		dbDelta( $sql );

		self::insert_board( $table_name, $board_id );
	}

	/**
	 * Insert board id into the table
	 *
	 * @param string $table_name The table name.
	 * @param int    $board_id The board id.
	 */
	public static function insert_board( $table_name, $board_id ) {
		global $wpdb;
		$data = array(
			'board_id' => $board_id,
		);
		$wpdb->insert( $table_name, $data );
	}

	/**
	 * Fetch board id from the table
	 */
	public static function fetch_board_id() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'feedback_board';
		$query      = $wpdb->get_results( "SELECT board_id FROM {$wpdb->prefix}feedback_board" );
		// print all the board id.
		foreach ( $query as $row ) {
			self::fetch_board_name( $row->board_id );
		}
	}

	/**
	 * Fetch board details from the table
	 *
	 * @param int $board_id The board id.
	 */
	public static function fetch_board_name( $board_id ) {
		global $wpdb;

		$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d", $board_id ) );
		if ( ! empty( $query ) ) {
			$board_name = $query[0]->post_title;
			?>
				<form method="post">
					<?php wp_nonce_field( 'feedback_board', 'feedback_board_nonce' ); ?>
					<button class="card  m-3 p-3" name="board_id" value="<?php echo esc_html( $board_id ); ?>" style="background-color:#f9fafc;width:80%;"><?php echo esc_html( $board_name ); ?></button>
				</form>
			<?php
		} else {
			echo 'Board not found.';
		}
	}

	/**
	 * Fetch feedbacks of a board
	 */
	public static function fetch_board_details() {
		if ( ! isset( $_POST['feedback_board_nonce'] ) || ! wp_verify_nonce( $_POST['feedback_board_nonce'], 'feedback_board' ) ) {
			return;
		}
		if ( isset( $_POST['board_id'] ) ) {
			$board_id = $_POST['board_id'];
			global $wpdb;
			$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d", $board_id ) );
			if ( ! empty( $query ) ) {
				$board_name        = $query[0]->post_title;
				$board_description = $query[0]->post_content;
				$data              = array(
					'board_name'        => $board_name,
					'board_description' => $board_description,
					'board_id'          => $board_id,
				);
				return $data;
			}
		} else {
			return false;
		}
	}

	/**
	 * Create feedback table
	 *
	 * @param string $table_name The table name.
	 * @param array  $data The data.
	 */
	public static function create_feedback_table( $table_name, $data ) {

		global $wpdb;
		// SQL query to create the table.
		$sql = "CREATE TABLE $table_name (
			id INT(11) NOT NULL AUTO_INCREMENT,
			fd_id VARCHAR(255) NOT NULL,
			fb_title VARCHAR(255) NOT NULL,
			fb_description VARCHAR(255) NOT NULL,
			user_id VARCHAR(255) NOT NULL,
			vote int(11) NULL,
			time datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) " . $wpdb->get_charset_collate() . ';';
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Execute the query to create the table.
		dbDelta( $sql );

		self::insert_feedback( $table_name, $data );
	}

	/**
	 * Insert feedback into the table
	 *
	 * @param string $table_name The table name.
	 * @param array  $data The data.
	 */
	public static function insert_feedback( $table_name, $data ) {
		global $wpdb;
		$wpdb->insert( $table_name, $data );
	}

	/**
	 * Fetch feedbacks of a board
	 *
	 * @param int $board_id The board id.
	 */
	public static function fetch_feedback( $board_id ) {
		global $wpdb;
		$query     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}product_feedback WHERE fd_id = %d", $board_id ) );
		$feedbacks = array();
		if ( ! empty( $query ) ) {
			foreach ( $query as $row ) {
				$feedbacks[] = array(
					'id'             => $row->id,
					'fb_title'       => $row->fb_title,
					'fb_description' => $row->fb_description,
					'user_id'        => $row->user_id,
					'vote'           => $row->vote,
					'time'           => $row->time,
				);
				?>
				</div>
				<?php
			}
			return $feedbacks;
		}
	}

	/**
	 * Total Likes
	 *
	 * @param int $board_id The board id.
	 * @param int $feedback_id The feedback id.
	 */
	public static function total_likes( $board_id, $feedback_id ) {
		global $wpdb;
		$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}product_feedback WHERE fd_id = %d and id = %d", $board_id, $feedback_id ) );

		if ( null === $query[0]->vote ) {
			return 0;
		} else {
			return $query[0]->vote;
		}
	}

	/**
	 * Total likes
	 */
	public static function vote_like_feedback() {
		if ( ! isset( $_POST['vote_nonce'] ) && wp_verify_nonce( $_POST['vote_nonce'], 'vote' ) ) {
			return;
		}

		if ( isset( $_POST['vote_id'] ) ) {
			$feedback    = $_POST['vote_id'];
			$feedback    = explode( ',', $feedback );
			$board_id    = $feedback[0];
			$feedback_id = $feedback[1];
			$user_id     = get_current_user_id();
			$data        = array(
				'fd_id'       => $board_id,
				'user_id'     => $user_id,
				'feedback_id' => $feedback_id,
				'vote'        => 1,
			);

			// Update the vote by 1.
			global $wpdb;

			$table_name = $wpdb->prefix . 'votes';
			$query      = $wpdb->query( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );
			if ( ! $query ) {
				self::create_vote_table( $table_name, $data );
			} else {
				self::insert_vote( $table_name, $data );

				$new_query = $wpdb->get_results( $wpdb->prepare( "SELECT vote FROM {$wpdb->prefix}product_feedback WHERE fd_id = %d and id = %d", $board_id, $feedback_id ) );

				echo esc_html( $new_query[0]->vote );
				exit();
			}
		}
	}

	/**
	 * Create vote table
	 *
	 * @param string $table_name The table name.
	 * @param array  $data The data.
	 */
	public static function create_vote_table( $table_name, $data ) {
		echo 'create table';
		global $wpdb;
		// SQL query to create the table.
		$sql = "CREATE TABLE $table_name (
			id INT(11) NOT NULL AUTO_INCREMENT,
			fd_id VARCHAR(255) NOT NULL,
			feedback_id int(11) NOT NULL,
			user_id VARCHAR(255) NOT NULL,
			vote int(11) NOT NULL,
			time datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) " . $wpdb->get_charset_collate() . ';';
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Execute the query to create the table.
		dbDelta( $sql );

		self::insert_vote( $table_name, $data );
	}

	/**
	 * Insert vote
	 *
	 * @param string $table_name The table name.
	 * @param array  $data The data.
	 */
	public static function insert_vote( $table_name, $data ) {
		global $wpdb;
		$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}votes WHERE fd_id = %d and user_id = %d and feedback_id = %d ", $data['fd_id'], $data['user_id'], $data['feedback_id'] ) );
		if ( empty( $query ) ) {
			$wpdb->insert( $table_name, $data );
			$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}product_feedback WHERE fd_id = %d and  id = %d ", $data['fd_id'], $data['feedback_id'] ) );
			$vote  = $query[0]->vote;
			++$vote;
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}product_feedback SET vote = %d WHERE fd_id = %d and id = %d ", $vote, $data['fd_id'], $data['feedback_id'] ) );

		} else {
			echo 'You have already voted';
		}
	}
}
