<?php


/** Replace the standard loop with our custom loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'podcast_archive_loop' );

/** Code for custom podcast loop */
function podcast_archive_loop() {
    global $post;

    $args = array(
	'post_type'      => 'podcast',
	'posts_per_page' => 5,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
	'paged'          => get_query_var( 'paged' )
	);

	global $wp_query;
	$wp_query = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
	// don't want to use $wp_query, use our custom variable instead.

	if ( have_posts() ) :
		//custom loop
		while ( have_posts() ) : the_post();
		printf( '<article %s>', genesis_attr( 'entry' ) );
	//	remove_action( 'genesis_entry_header', 'genesis_post_info', 5 );
		do_action( 'genesis_entry_header' );

    if( has_post_thumbnail() ) {
		the_post_thumbnail('featured-podcast', array('class' => 'alignnone')); //Add in featured image
      }
    //Output the player    
    echo do_shortcode('[player]');

		printf( '<div %s>', genesis_attr( 'entry-content' ) );

			remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
			do_action( 'genesis_entry_content' );

			echo '</div>';

			echo '</article>';

		endwhile;

		do_action( 'genesis_after_endwhile' );
	endif;

	wp_reset_query();

}

//* Modify the length of post excerpts
add_filter( 'excerpt_length', 'transform_excerpt_length' );
function transform_excerpt_length( $length ) {
	return 20; // pull first 50 words
}


genesis();
