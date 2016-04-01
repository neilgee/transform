<?php
/**
 * This file adds the Landing template to the Transform Theme.
 *
 * @author Neil Gowran
 * @package Transform
 * @subpackage Customizations
 */

/*
Template Name: Landing
*/

//* Add custom body class to the head
add_filter( 'body_class', 'transform_add_body_class' );
function transform_add_body_class( $classes ) {

   $classes[] = 'transform-landing';
   return $classes;

}

//* Force full width content layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );



//* Run the Genesis loop
genesis();
