<?php

class DFM_WP_Admin_Category_Listing {

	/**
	 * The string used to uniquely identify this plugin.
	 * Preferably, this would have been passed in.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $plugin_name;

	/**
	 * Needed for permissions
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string
	 */
	public $capability;

	/**
	 * Array for category slugs and nice names
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $categories;

	/**
	 * DFM_WP_Admin_Category_Listing constructor.
	 */
	public function __construct() {
		// Preferably, this would have been passed in
		$this->plugin_name = 'dfm-wp-public';
		// Anyone who can edit categories can see these menu items
		$this->capability = 'manage_categories';
		/**
		 * Define with categories specified in business requirements.
		 * $key is the slug and $value is an array of the nice name (name) and how many posts we want to show (count).
		 */
		$this->categories = array(
			'sports' => array(
				'name' => 'Sports',
				'count' => 25,
			),
			'animals' => array(
				'name' => 'Animals',
				'count' => 10,
			),
			'business' => array(
				'name' => 'Business',
				'count' => 12,
			),
			'entertainment' => array(
				'name' => 'Entertainment',
				'count' => 50,
			),
			'world-and-news' => array(
				'name' => 'World and News',
				'count' => 100,
			),
		);

	}

	/**
	 * This must be run AFTER we define the categories.
	 */
	public function run() {
		$this->validate_categories();
		// Make sure we add the menu items at the right time
		add_action('admin_menu', array($this,'create_menu_items'));
	}

	/**
	 * Ensure all categories exist and are configured correctly
	 * If any of them aren't, they will be removed from the class' category array
	 */
	public function validate_categories() {
		// Initial assumption of "all categories exist"
		$invalid_categories = false;

		// Loop through category array and see if they exist in WordPress in the "category" taxonomy
		foreach ( $this->categories as $slug => $details ) {
			// Returns Term object if it exists
			$slug_term = get_term_by('slug', $slug, 'category');
			// If the category doesn't exist or doesn't match the name specified in $this->categories, it is invalid
			if ( ! ( $slug_term->name === $details['name'] ) ) {
				$invalid_categories = true;
				// This is graceful degradation, my friends. Do what we can with what we have.
				unset( $this->categories[$slug] );
			}
		}
		// Show admin error if any category is invalid
		if( $invalid_categories ) {
			add_action( 'admin_notices', array( $this, 'error_in_category' ) );
		}
	}

	/**
	 * Callback for invalid categories
	 */
	public function error_in_category() {
		// Keep the message dynamic in case our class' categories array changes.
		$category_list = '';
		foreach($this->categories as $slug => $details) {
			$category_list .= '<br>"' . $details['name'] . '" with a slug of "' . $slug . '"';
		}

		// Normal-ish admin error message format
		$message = __( "One or more of your categories are missing or configured incorrectly for this plugin. Please ensure you have these: $category_list", $this->plugin_name );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	}

	/**
	 * Create the menu items for the categories and assign them to an anonymous function
	 */
	public function create_menu_items() {
		// Loop over each category that still exists in the array
		foreach( $this->categories as $slug => $details ){
			// Use anonymous function to pass arguments into the menu functions
			$menu_function = function() use ( $slug, $details ) {
				$this->build_category_posts( $slug, $details );
			};
			// Add a menu item
			add_menu_page(
				$details['name'] . ' Content',
				$details['name'] . ' Content',
				$this->capability,
				$this->plugin_name . '/' . $slug,
				$menu_function
			);
		}
	}

	/**
	 * Here we start by querying the category posts and running the output function to show them to the user.
	 * This is the part where we limit the posts and specify which ones to get
	 *
	 * @param string $slug The category slug.
	 * @param array $details The nice name and post limit of the category
	 */
	public function build_category_posts($slug, $details) {
		$args = array(
			'posts_per_page'   => $details['count'], //This limits the posts to the amount specified in the class' categories variable
			'category_name'    => $slug,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
		);
		$posts = get_posts( $args );
		$this->output_category_posts($details['name'], $posts);
	}

	/**
	 * This just handles the output of the data.
	 * I would use the normal template php here, but it's just messy and too much for a coding exercise.
	 *
	 * @param string $page_title The nice name of the category
	 * @param array $posts The posts in that category
	 */
	public function output_category_posts($page_title, $posts){
		$output = "<h1>$page_title Content</h1>";
		if($posts){
			$output .= '<table class="wp-list-table widefat fixed striped posts"><thead><tr><th scope="col" id="title" class="manage-column column-title column-primary">Title</th><th scope="col" id="author" class="manage-column column-author">Author</th><th scope="col" id="date" class="manage-column column-date">Date</th></tr></thead><tbody id="the-list">';

			foreach($posts as $post){
				$output .= '<tr id="post-' . $post->ID . '" class="type-post status-publish entry">' .
				           '<td>' . $post->post_title . '</td>' .
				           '<td>' . get_the_author_meta('display_name', $post->post_author) . '</td>' .
				           '<td>' . $post->post_date . '</td>' .
				           '</tr>';
			}

			$output .= '</tbody><tfoot><tr><th scope="col" class="manage-column column-title column-primary">Title</th><th scope="col" class="manage-column column-author">Author</th><th scope="col" class="manage-column column-date">Date</th></tr></tfoot></table>';
		}else{
			$output .= "<h3>There are no $page_title posts. Check back later.</h3>";
		}

		echo $output;
	}
}

$plugin = new DFM_WP_Admin_Category_Listing();
$plugin->run();