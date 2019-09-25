<?php

// Turn theme support on for various features
// ====================================
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );

/* #region Initialize menus to use in the admin panel */

function register_theme_menus() {

	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu' ),
			'footer-menu-bottom' => __( 'Footer Menu - Bottom' ),
			'footer-column-2' => __( 'Footer Menu - Column 2' ),
			'footer-column-3' => __( 'Footer Menu - Column 3' )
		)
	);

}
add_action( 'init', 'register_theme_menus' );

/* #endregion */

/* #region Import all CSS */

function pb_theme_style() {

	// Webfonts
	wp_enqueue_style( 'googlefont_css', 'https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Raleway:300,400,500,600,700|Crete+Round:400i' );

	// Main Theme Styles
	wp_enqueue_style( 'main_css', get_template_directory_uri() . '/style.css' );

}
add_action( 'wp_enqueue_scripts', 'pb_theme_style' );

/* #endregion */

/* #region Import all JavaScript */

function pb_theme_js() {

	// Theme Scripts
	wp_enqueue_script( 'plugins_js', get_template_directory_uri() . '/assets/js/plugins.js', array( 'jquery' ), '',  true );
	wp_enqueue_script( 'functions_js', get_template_directory_uri() . '/assets/js/functions.js', array( 'jquery' ), '',  true );

}
add_action( 'wp_enqueue_scripts', 'pb_theme_js' );

/* #endregion */

// Add SVG image support to available MIME types
// ====================================
function add_file_types_to_uploads( $file_types ) {

	$new_filetypes = array();
	$new_filetypes[ 'svg' ] = 'image/svg+xml';
	$file_types = array_merge( $file_types, $new_filetypes );
	return $file_types;

}
add_filter( 'upload_mimes', 'add_file_types_to_uploads' );

/* #region Home page article listings */

// Truncate Content
function pb_custom_excerpt_length( $length ) {

	return 20;

}
add_filter( 'excerpt_length', 'pb_custom_excerpt_length', 999 );

// Add read more link
function pb_excerpt_more( $more ) {

	if ( ! is_single() ) {
		$more = sprintf( ' ... <a class="read-more" href="%1$s">%2$s</a>',
			get_permalink( get_the_ID() ),
			__( 'Read More', 'textdomain' )
		);
	}

	return $more;

}
add_filter( 'excerpt_more', 'pb_excerpt_more' );

/* #endregion */

/* #region Comments with Theme Design */
function pb_theme_comment( $comment, $args, $depth ) {

	if( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}

	if( $comment->comment_approved != '0' ) {
	?>

	<<?php echo $tag; ?> <?php comment_class( empty( $args['comment even thread-even depth-1'] ) ? '' : 'parent' ); ?> id="li-comment-<?php comment_ID() ?>">

	<?php if( 'div' != $args['style'] ) { ?>
		<div id="comment-<?php comment_ID() ?>" class="comment-wrap clearfix">
	<?php } ?>

		<div class="comment-meta">
			<div class="comment-author vcard">

				<?php if ( $args['avatar_size'] != 0 ) { ?>
					<span class="comment-avatar clearfix">
					<?php echo get_avatar( $comment, $args ); ?>
					</span>
				<?php } ?>
				<?php // printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() ); ?>
			</div>
		</div>

		<div class="comment-content clearfix">
			<div class="comment-author">
				<?php echo get_comment_author(); ?>
				<span>
					<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php
							/* translators: 1: date, 2: time */
							printf( __( '%1$s at %2$s' ), get_comment_date(), get_comment_time() );
						?>
					</a>
				</span>
				<?php // edit_comment_link( __( '(Edit)' ), '  ', '' ); ?>
			</div>

			<?php

				comment_text();

				comment_reply_link(
					array_merge( $args, array(
						'add_below' 	=> $add_below,
						'depth'     	=> $depth,
						'max_depth' 	=> $args['max_depth'],
						'class' 			=> 'comment-reply-link',
						'reply_text'	=> __( '<i class="icon-reply"></i>' ),
					) )
				);

			?>
		</div>

		<div class="clear"></div>
	</div>

	<?php
	}
}
/* #endregion */

?>