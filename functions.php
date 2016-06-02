<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'transform', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'transform' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Customizer CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Add in our Custom Post Type Featured Post
require( get_stylesheet_directory() . '/includes/class-featured-custom-post-type-widget-registrations.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Transform' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/altitude/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'transform_enqueue_scripts_styles' );
function transform_enqueue_scripts_styles() {

	wp_enqueue_script( 'transform-global', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'ionicons', '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', array() , '2.0.1', 'all' );
}

//Add TypeKit Font Set
add_action('wp_head', 'transform_font_typekit');
function transform_font_typekit() {
	echo '<script src="https://use.typekit.net/xhn5rrn.js"></script>
<script>try{Typekit.load({ async: true });}catch(e){}</script>';
}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add new image sizes
add_image_size( 'featured-page', 1140, 400, TRUE );
add_image_size( 'featured-post', 300, 200, TRUE );
add_image_size( 'featured-podcast-blog', 700, 350, TRUE );

//* Add support for 1-column footer widget area
add_theme_support( 'genesis-footer-widgets', 1 );

//* Add support for footer menu
add_theme_support ( 'genesis-menus' , array ( 'primary' => 'Primary Navigation Menu', 'secondary' => 'Secondary Navigation Menu', 'footer' => 'Footer Navigation Menu' ) );

//Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links' ) );

//Removes Title and Description on CPT Archive
remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
//Removes Title and Description on Blog Archive
remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );
//Removes Title and Description on Date Archive
remove_action( 'genesis_before_loop', 'genesis_do_date_archive_title' );
//Removes Title and Description on Archive, Taxonomy, Category, Tag
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
//Removes Title and Description on Archive, Taxonomy, Category, Tag
remove_action( 'genesis_before_loop', 'genesis_do_author_title_description', 15 );
//Removes Title and Description on Blog Template Page
remove_action( 'genesis_before_loop', 'genesis_do_blog_template_heading' );


//* Unregister the header right widget area
unregister_sidebar( 'header-right' );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

//* Remove output of primary navigation right extras
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_header', 'genesis_do_subnav', 5 );

//* Add secondary-nav class if secondary navigation is used
add_filter( 'body_class', 'transform_secondary_nav_class' );
function transform_secondary_nav_class( $classes ) {

	$menu_locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! empty( $menu_locations['secondary'] ) ) {
		$classes[] = 'secondary-nav';
	}
	return $classes;

}

//* Hook menu in footer
add_action( 'genesis_footer', 'rainmaker_footer_menu', 7 );
function rainmaker_footer_menu() {
	printf( '<nav %s>', genesis_attr( 'nav-footer' ) );
	wp_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => false,
		'menu_class'     => 'genesis-nav-menu',
	) );

	echo '</nav>';
}

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'flex-height'     => true,
	'flex-width'      => true,
	'width'           => 350,
	'height'          => 63,
	'header-text'     => false,
) );

//Allow SVG Images Via Media Uploader
function transform_add_svg_images($mimetypes) {
	$mimetypes['svg'] = 'image/svg+xml';
	return $mimetypes;
}
add_filter('upload_mimes', 'transform_add_svg_images');



//Remove Genesis header style so we can use the customiser and header function below genesischild_swap_header
remove_action( 'wp_head', 'genesis_custom_header_style');


//Add an image tag inline in the site title element for the main logo
function transform_swap_header($title, $inside, $wrap) {
//* Set what goes inside the wrapping tags
	if ( get_header_image() ) :
$logo = '<img  src="' . get_header_image() . '" width="' . esc_attr( get_custom_header()->width ) . '" height="' . esc_attr( get_custom_header()->height ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
	else:
$logo =  get_bloginfo('name');
	endif;
 $inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), $logo );
 //* Determine which wrapping tags to use - changed is_home to is_front_page to fix Genesis bug
 $wrap = is_front_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
 //* A little fallback, in case an SEO plugin is active - changed is_home to is_front_page to fix Genesis bug
 $wrap = is_front_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;
 //* And finally, $wrap in h1 if HTML5 & semantic headings enabled
 $wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;
 return sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );

}
add_filter( 'genesis_seo_title','transform_swap_header', 10, 3 );



//Add site description screen reader class
function transform_add_site_description_class( $attributes ) {
	$attributes['class'] .= ' screen-reader-text';
	return $attributes;
}
add_filter( 'genesis_attr_site-description', 'transform_add_site_description_class' );


//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer',
) );

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'transform_author_box_gravatar' );
function transform_author_box_gravatar( $size ) {

	return 176;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'transform_comments_gravatar' );
function transform_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;
	return $args;

}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'transform_remove_comment_form_allowed_tags' );
function transform_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'transform' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Setup widget counts
function transform_count_widgets( $id ) {
	global $sidebars_widgets;

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

function transform_widget_area_class( $id ) {
	$count = transform_count_widgets( $id );

	$class = '';

	if( $count == 1 ) {
		$class .= ' widget-full';
	} elseif( $count % 3 == 1 ) {
		$class .= ' widget-thirds';
	} elseif( $count % 4 == 1 ) {
		$class .= ' widget-fourths';
	} elseif( $count % 2 == 0 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves';
	}
	return $class;

}

//* Relocate the post info
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 12 );

//* Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'transform_post_info_filter' );
function transform_post_info_filter( $post_info ) {

    $post_info = '[post_date format="M d Y"] [post_comments][post_edit]';
    return $post_info;

}

// Customize the entry meta in the entry footer
add_filter( 'genesis_post_meta', 'transform_post_meta_filter' );
function transform_post_meta_filter( $post_meta ) {

	$post_meta = 'Written by [post_author_posts_link] [post_categories before=" &middot; Categorized: "]  [post_tags before=" &middot; Tagged: "]';
	return $post_meta;

}

// Customize the content limit more markup
function transform_content_limit_read_more_markup( $output, $content, $link ) {
	$output = sprintf( '<p>%s &#x02026;</p><p class="more-link-wrap">%s</p>', $content, str_replace( '&#x02026;', '', $link ) );
	return $output;
}
add_filter( 'get_the_content_limit', 'transform_content_limit_read_more_markup', 10, 3 );

// Change the Read More on an excerpt for a post
function transform_read_more_link() {
	return '...<br /><a href="' . get_permalink() . '" class="more-link" title="Read More" >Read More</a>';
}

add_filter( 'excerpt_more', 'transform_read_more_link' );


// Register widget areas
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'transform' ),
	'description' => __( 'This is the front page 1 section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'logos',
	'name'        => __( 'Logo Display', 'transform' ),
	'description' => __( 'This is the front page logo section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'transform' ),
	'description' => __( 'This is the front page 2 section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'transform' ),
	'description' => __( 'This is the front page 3 section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'transform' ),
	'description' => __( 'This is the front page 4 section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-5',
	'name'        => __( 'Front Page 5', 'transform' ),
	'description' => __( 'This is the front page 5 section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-6',
	'name'        => __( 'Front Page 6', 'transform' ),
	'description' => __( 'This is the front page 6 section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-7',
	'name'        => __( 'Front Page 7', 'transform' ),
	'description' => __( 'This is the front page 7 section.', 'transform' ),
) );
genesis_register_sidebar( array(
	'id'          => 'footercontent',
	'name'        => __( 'Footer Area', 'transform' ),
	'description' => __( 'This is the general footer area', 'transform' ),
) );


// Remove Existing Footer
remove_action( 'genesis_footer', 'genesis_do_footer' );


// Position the New Footer Area
function transform_footer_widget() {
    genesis_widget_area ('footercontent', array(
        'before' => '<div class="footercontent">',
        'after' => '</div>',));
}
add_action('genesis_footer','transform_footer_widget');


//Allow short code to run in widget
add_filter('widget_text', 'do_shortcode');


// Code for custom podcast loop
function podcast_featured_post_loop() {
ob_start();
    global $post;

    $args = array(
	'post_type'      => 'podcast',
	'posts_per_page' => 4,
	'category_name'	 => 'home-featured-podcast',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
	);

$the_query = new WP_Query($args);

// have_posts() is a wrapper function for $wp_query->have_posts(). Since we
	// don't want to use $wp_query, use our custom variable instead.

	if ( $the_query->have_posts() ) :
		//output the widget mark up
		echo '<div class="featured-content featuredpost">';
		//custom loop
		while ( $the_query->have_posts() ) : $the_query->the_post();

		printf( '<article %s>', genesis_attr( 'entry' ) );
		the_post_thumbnail('thumbnail'); //Add in featured image
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		do_action( 'genesis_entry_header' );

		echo '<div class="entry-content">';
		echo the_excerpt_max_charlength(100); //change amount of characters to display
		echo '</div>';
			printf( '</article>');

	endwhile;

	endif;

wp_reset_postdata(); //resets loop
	return ob_get_clean();

}
add_shortcode( 'podcast_featured_posts', 'podcast_featured_post_loop' );

// Limit the excerpt by character - Reference - http://codex.wordpress.org/Function_Reference/get_the_excerpt .
function the_excerpt_max_charlength($charlength) {

	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '... <br><a href="' . get_permalink() . '" class="more-link" title="Read More">Read More</a>';
	} else {
		echo $excerpt;
	}
}

//Change Title tag to h4 for podcast title in featured podcasts on home page loop
function transform_h4_post_title_output( $title ) {

	if( 'podcast' == get_post_type()) {
		$title = sprintf( '<h4 class="entry-title"><a href="%s" rel="bookmark">%s</a></h4>', get_permalink(), get_the_title()   );
	return $title;
	}
	else {
		return $title;
	}
}
add_filter( 'genesis_post_title_output', 'transform_h4_post_title_output');


//Add the permalink around the featured image on podcats on home page loop
function transform_featured_image_permalink( $html, $post_id, $post_image_id ) {

  $html = '<a class="alignleft" href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
  return $html;

}

// Remove Post Info, Post Meta from podcast CPT
function transform_remove_post_info() {
//	if ('podcast' == get_post_type()) {//add in your CPT name
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	//	}
}
add_action ( 'get_header', 'transform_remove_post_info' );



// Display featured image on single posts
function transform_show_featured_image_single_posts() {
	if ( ! is_singular ( array( 'post', 'podcast' ) )) {
		return;
	}

	$image_args = array(
		'size'  => 'featured-podcast-blog',
		'attr'  => array(
			'class' => 'alignnone',
		),
	);

	genesis_image( $image_args );
}
add_action( 'genesis_entry_content', 'transform_show_featured_image_single_posts', 4 );


// Output Sign Up Box Wherever
function transform_after_entry () {
	ob_start();
	?>
	<div class="widget-wrap"><div class="enews">
		<form id="subscribe" action="//brettbarclay.us12.list-manage.com/subscribe/post?u=be05ffe38c9e6322ce09b4931&amp;id=10de237c70" method="post" target="_blank" onsubmit="if ( subbox1.value == 'First Name') { subbox1.value = ''; } if ( subbox2.value == 'Last Name') { subbox2.value = ''; }" name="">
			<label for="subbox1" class="screenread">First Name</label>
			<input id="subbox1" class="enews-subbox" value="First Name" onfocus="if ( this.value == 'First Name') { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = 'First Name'; }" name="FNAME" type="text">
			<label for="subbox" class="screenread">E-Mail Address</label>
			<input value="E-Mail Address" id="subbox" onfocus="if ( this.value == 'E-Mail Address') { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = 'E-Mail Address'; }" name="EMAIL" required="required" type="email">
			<input value="Get Instant Access" id="subbutton" type="submit">
		</form>
	</div></div>


	<?php
	return ob_get_clean();
}

add_shortcode( 'after_post', 'transform_after_entry' );



// Output Lead Page Sign Up
function transform_lead_page () {
	ob_start();
	?>
<div class="widget-wrap lead-page-pop"><a style="background-color: #ffcc00; color: #333; text-decoration: none; font-family: 'museo-sans', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 18px; line-height: 1.4; text-transform:uppercase; padding: 15px 40px; display: inline-block; max-width: 600px; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; background-position: initial initial; background-repeat: initial initial;" href="https://brettbarclay.leadpages.co/leadbox/14239b173f72a2%3A10f9ecddcf46dc/5648554290839552/" target="_blank">Get Instant Access Now</a><script data-leadbox="14239b173f72a2:10f9ecddcf46dc" data-url="https://brettbarclay.leadpages.co/leadbox/14239b173f72a2%3A10f9ecddcf46dc/5648554290839552/" data-config="%7B%7D" type="text/javascript" src="https://brettbarclay.leadpages.co/leadbox-1463513236.js"></script></div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'transform_lead_page', 'transform_lead_page' );
