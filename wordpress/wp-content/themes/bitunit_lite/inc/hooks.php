<?php
/**
 * Theme hooks.
 *
 * @package Bitunit_lite
 */

// Menu description.
add_filter( 'walker_nav_menu_start_el', 'bitunit_lite_nav_menu_description', 10, 4 );

// Rewrite thumbnail size for non-deafult blog formats.
add_filter( 'bitunit_lite_post_thumbail_size', 'bitunit_lite_set_thumb_sizes' );

// Sidebars classes.
add_filter( 'bitunit_lite_widget_area_classes', 'bitunit_lite_set_sidebar_classes', 10, 2 );

// Add row to footer area classes.
add_filter( 'bitunit_lite_widget_area_classes', 'bitunit_lite_add_footer_widgets_wrapper_classes', 10, 2 );

// Set footer columns.
add_filter( 'dynamic_sidebar_params', 'bitunit_lite_get_footer_widget_layout' );

// Adapt default image post format classes to current theme.
add_filter( 'cherry_post_formats_image_css_model', 'bitunit_lite_add_image_format_classes', 10, 2 );

// Enqueue sticky menu if required.
add_filter( 'bitunit_lite_theme_script_depends', 'bitunit_lite_enqueue_misc' );

// Add has/no thumbnail classes for posts.
add_filter( 'post_class', 'bitunit_lite_post_thumb_classes' );

// Modify a comment form.
add_filter( 'comment_form_defaults', 'bitunit_lite_modify_comment_form' );

// Additional body classes.
add_filter( 'body_class', 'bitunit_lite_extra_body_classes' );

// Render macros in text widgets.
add_filter( 'widget_text', 'bitunit_lite_render_widget_macros' );

// Adds the meta viewport to the header.
add_action( 'wp_head', 'bitunit_lite_meta_viewport', 0 );

// Customization for `Tag Cloud` widget.
add_filter( 'widget_tag_cloud_args', 'bitunit_lite_customize_tag_cloud' );

// Changed excerpt more string.
add_filter( 'excerpt_more', 'bitunit_lite_excerpt_more' );

add_filter( 'tm_builder_front_styles', 'bitunit_lite_builder_styles' );



// Changed cherry sidebar args.
add_filter( 'cherry_sidebar_args', 'bitunit_lite_sidebar_args' );

// Changed services cta format.
add_filter( 'cherry_services_cta_format', 'bitunit_lite_services_cta_format' );

// Disable requests to wp.org repository for this theme.
add_filter( 'http_request_args', 'bitunit_lite_disable_wporg_request', 5, 2 );

function bitunit_lite_services_cta_format( $cta_format ) {

	$cta_format = '<div class="container"><h2 class="service-cta_title">%1$s</h2><div class="service-cta_content"><div class="service-cta_desc">%2$s</div>%3$s</div></div>';

	return $cta_format;
}

function bitunit_lite_sidebar_args( $args ) {

	$args['before_title'] = '<h2 class="widget-title">';
	$args['after_title']  = '</h2>';

	return $args;
}


function bitunit_lite_builder_styles( $style ) {

	unset( $style['tm-builder-modules-grid'] );
	return $style;
}

add_filter( 'tm_pb_pre_set_style', 'bitunit_lite_pb_pre_set_style_blurb', 10, 2 );

function bitunit_lite_pb_pre_set_style_blurb( $style, $function_name ) {

	if( 'tm_pb_blurb' !== $function_name ) {
  		return $style;
 	}

 	if( strpos( $style['declaration'], 'font-family' ) || strpos( $style['declaration'], 'font-size' ) || strpos( $style['declaration'], 'font-weight') ) {
  		$style['selector'] = '.tm_pb_blurb .tm_pb_blurb_container > h4, .tm_pb_blurb .tm_pb_blurb_container > h4 a';
 	}

 	return $style;

}


/**
 * Append description into nav items
 *
 * @param  string  $item_output The menu item output.
 * @param  WP_Post $item        Menu item object.
 * @param  int     $depth       Depth of the menu.
 * @param  array   $args        wp_nav_menu() arguments.
 * @return string
 */
function bitunit_lite_nav_menu_description( $item_output, $item, $depth, $args ) {

	if ( 'main' !== $args->theme_location || ! $item->description ) {
		return $item_output;
	}

	$descr_enabled = get_theme_mod(
		'header_menu_attributes',
		bitunit_lite_theme()->customizer->get_default( 'header_menu_attributes' )
	);

	if ( ! $descr_enabled ) {
		return $item_output;
	}

	$current     = $args->link_after . '</a>';
	$description = '<div class="menu-item__desc">' . $item->description . '</div>';
	$item_output = str_replace( $current, $description . $current, $item_output );

	return $item_output;
}

/**
 * Rewrite thumbnail size for non-deafult blog template.
 *
 * @param  bool|string $size Default size.
 * @return string
 */
function bitunit_lite_set_thumb_sizes( $size ) {

	if ( is_single() ) {
		return $size;
	}

	$layout = get_theme_mod( 'blog_layout_type', bitunit_lite_theme()->customizer->get_default( 'blog_layout_type' ) );

	if ( 'default' === $layout && ! ( is_sticky() && is_home() && ! is_paged() ) ) {
		return $size;
	}

	if ( 'default' !== $layout && ! ( is_sticky() && is_home() && ! is_paged() ) ) {
		return 'post-thumbnail';
	}

	return 'bitunit_lite-thumb-l';
}

/**
 * Set layout classes for sidebars.
 *
 * @since  1.0.0
 * @uses   bitunit_lite_get_layout_classes.
 * @param  array  $classes Additional classes.
 * @param  string $area_id Sidebar ID.
 * @return array
 */
function bitunit_lite_set_sidebar_classes( $classes, $area_id ) {

	if ( ! in_array( $area_id, array( 'sidebar' ) ) ) {
		return $classes;
	}

	return bitunit_lite_get_layout_classes( 'sidebar', $classes );
}

/**
 * Set layout classes for sidebars.
 *
 * @since  1.0.0
 * @param  array  $classes Additional classes.
 * @param  string $area_id Sidebar ID.
 * @return array
 */
function bitunit_lite_add_footer_widgets_wrapper_classes( $classes, $area_id ) {

	if ( 'footer-area' !== $area_id ) {
		return $classes;
	}

  	$classes[] = 'row';

  	$columns = get_theme_mod(
    	'footer_widget_columns',
    	bitunit_lite_theme()->customizer->get_default( 'footer_widget_columns' )
  	);

  	if ( 1 === intval( $columns ) ) {
    	$classes[] = 'one-column';
  	}
	return $classes;
}


/**
 * Get footer widgets layout class
 *
 * @since  1.0.0
 * @param  string $params Existing widget classes.
 * @return string
 */
function bitunit_lite_get_footer_widget_layout( $params ) {

	if ( is_admin() ) {
		return $params;
	}

	if ( empty( $params[0]['id'] ) || 'footer-area' !== $params[0]['id'] ) {
		return $params;
	}

	if ( empty( $params[0]['before_widget'] ) ) {
		return $params;
	}

	$columns = get_theme_mod(
		'footer_widget_columns',
		bitunit_lite_theme()->customizer->get_default( 'footer_widget_columns' )
	);

	$columns = intval( $columns );
	$classes = 'class="col-xs-12 col-sm-%2$s col-md-%1$s %3$s ';

	switch ( $columns ) {
		case 4:
			$md_col = 3;
			$sm_col = 6;
			$extra  = '';
			break;

		case 3:
			$md_col = 4;
			$sm_col = 4;
			$extra  = '';
			break;

		case 2:
			$md_col = 6;
			$sm_col = 6;
			$extra  = '';
			break;

		default:
			$md_col = 12;
			$sm_col = 12;
			$extra  = 'footer-area--centered';
			break;
	}

	$params[0]['before_widget'] = str_replace(
		'class="',
		sprintf( $classes, $md_col, $sm_col, $extra ),
		$params[0]['before_widget']
	);

	return $params;
}

/**
 * Filter image CSS model
 *
 * @param  array $css_model Default CSS model.
 * @param  array $args      Post formats module arguments.
 * @return array
 */
function bitunit_lite_add_image_format_classes( $css_model, $args ) {
	$css_model['link'] .= ' post-thumbnail--fullwidth';

	return $css_model;
}

/**
 * Add jQuery Stickup to theme script dependencies if required.
 *
 * @param  array $depends Default dependencies.
 * @return array
 */
function bitunit_lite_enqueue_misc( $depends ) {
	$header_menu_sticky = get_theme_mod( 'header_menu_sticky', bitunit_lite_theme()->customizer->get_default( 'header_menu_sticky' ) );

	if ( $header_menu_sticky && ! wp_is_mobile() ) {
		$depends[] = 'jquery-stickup';
	}

	$totop_visibility = get_theme_mod( 'totop_visibility', bitunit_lite_theme()->customizer->get_default( 'totop_visibility' ) );

	if ( $totop_visibility ) {
		$depends[] = 'jquery-totop';
	}

	return $depends;
}

/**
 * Add has/no thumbnail classes for posts
 *
 * @param  array $classes Existing classes.
 * @return array
 */
function bitunit_lite_post_thumb_classes( $classes ) {
	$thumb = 'no-thumb';

	if ( has_post_thumbnail() ) {
		$thumb = 'has-thumb';
	}

	$classes[] = $thumb;

	return $classes;
}

/**
 * Add placeholder attributes for comment form fields.
 *
 * @param  array $args Argumnts for comment form.
 * @return array
 */
function bitunit_lite_modify_comment_form( $args ) {
	$args = wp_parse_args( $args );

	if ( ! isset( $args['format'] ) ) {
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
	}

	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html_req  = ( $req ? " required='required'" : '' );
	$html5     = 'html5' === $args['format'];
	$commenter = wp_get_current_commenter();

	$args['label_submit'] = esc_html__( 'Submit Comment', 'bitunit_lite' );

	$args['fields']['author'] = '<p class="comment-form-author"><input id="author" class="comment-form__field" name="author" type="text" placeholder="' . esc_html__( 'Your name', 'bitunit_lite' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>';

	$args['fields']['email'] = '<p class="comment-form-email"><input id="email" class="comment-form__field" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'Your e-mail', 'bitunit_lite' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>';

	$args['fields']['url'] = '<p class="comment-form-url"><input id="url" class="comment-form__field" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'Your website', 'bitunit_lite' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	$args['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" class="comment-form__field" name="comment" placeholder="' . esc_html__( 'Comments *', 'bitunit_lite' ) . '" cols="45" rows="8" aria-required="true" required="required"></textarea></p>';

	$args['class_submit'] = 'btn btn-primary';

	return $args;
}

/**
 * Add extra body classes
 *
 * @param  array $classes Existing classes.
 * @return array
 */
function bitunit_lite_extra_body_classes( $classes ) {

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a options-based classes.
	$header_layout      = get_theme_mod( 'page_layout_type', bitunit_lite_theme()->customizer->get_default( 'header_container_type' ) );
	$content_layout      = get_theme_mod( 'content_container_type', bitunit_lite_theme()->customizer->get_default( 'content_container_type' ) );
	$footer_layout      = get_theme_mod( 'footer_container_type', bitunit_lite_theme()->customizer->get_default( 'footer_container_type' ) );
	$blog_layout = get_theme_mod( 'blog_layout_type', bitunit_lite_theme()->customizer->get_default( 'blog_layout_type' ) );
	$sb_position = get_theme_mod( 'sidebar_position', bitunit_lite_theme()->customizer->get_default( 'sidebar_position' ) );
	$sidebar     = get_theme_mod( 'sidebar_width', bitunit_lite_theme()->customizer->get_default( 'sidebar_width' ) );
	$word_wrap      = ( get_theme_mod( 'word_wrap', bitunit_lite_theme()->customizer->get_default( 'word_wrap' ) ) ) ? 'wordwrap' : '';

	return array_merge( $classes, array(
		'header-layout-' . $header_layout,
 		'content-layout-' . $content_layout,
 		'footer-layout-' . $footer_layout,
		'blog-' . $blog_layout,
		'position-' . $sb_position,
		'sidebar-' . str_replace( '/', '-', $sidebar ),
		$word_wrap,
	) );
}

/**
 * Replace macroses in text widget.
 *
 * @param  string $text Default text.
 * @return string
 */
function bitunit_lite_render_widget_macros( $text ) {
	$uploads = wp_upload_dir();
	$home_url = home_url();
	$data = array(
		'/%%uploads_url%%/' => $uploads['baseurl'],
		'/%%home_url%%/'    => esc_url( $home_url ),
		'/%%theme_url%%/'   => get_stylesheet_directory_uri(),
	);

	return preg_replace( array_keys( $data ), array_values( $data ), $text );
}

/**
 * Adds the meta viewport to the header.
 *
 * @since  1.0.1
 * @return string `<meta>` tag for viewport.
 */
function bitunit_lite_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
}

/**
 * Customization for `Tag Cloud` widget.
 *
 * @since  1.0.1
 * @param  array $args Widget arguments.
 * @return array
 */
function bitunit_lite_customize_tag_cloud( $args ) {
	$args['smallest'] = 14;
	$args['largest']  = 14;
	$args['unit']     = 'px';

	return $args;
}

/**
 * Replaces `[...]` (appended to automatically generated excerpts) with `...`.
 *
 * @since  1.0.1
 * @param  string $more The string shown within the more link.
 * @return string
 */
function bitunit_lite_excerpt_more( $more ) {

	if ( is_admin() ) {
		return $more;
	}

	return ' &hellip;';
}

function bitunit_lite_disable_wporg_request( $r, $url ) {
 
    // If it's not a theme update request, bail.
    if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
        return $r;
    }
 
    // Decode the JSON response.
    $themes = json_decode( $r['body']['themes'] );
 
    // Remove the active parent and child themes from the check.
    $parent = get_option( 'template' );
    $child  = get_option( 'stylesheet' );
 
    unset( $themes->themes->$parent );
    unset( $themes->themes->$child );
 
    // Encode the updated JSON response.
    $r['body']['themes'] = json_encode( $themes );
 
    return $r;
}