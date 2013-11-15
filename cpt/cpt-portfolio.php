<?php

$portfolio_labels = apply_filters( 'stag_portfolio_labels', array(
	'name'               => __( 'Portfolio', 'stag' ),
	'singular_name'      => __( 'Portfolio', 'stag' ),
	'add_new'            => __( 'Add New', 'stag' ),
	'add_new_item'       => __( 'Add New Portfolio', 'stag' ),
	'edit_item'          => __( 'Edit Portfolio', 'stag' ),
	'new_item'           => __( 'New Portfolio', 'stag' ),
	'view_item'          => __( 'View Portfolio', 'stag' ),
	'search_items'       => __( 'Search Portfolio', 'stag' ),
	'not_found'          => __( 'No Portfolios found', 'stag' ),
	'not_found_in_trash' => __( 'No Portfolios found in trash', 'stag' ),
	'parent_item_colon'  => ''
) );

$stag_options    = get_option('stag_options');
@$portfolio_slug = $stag_options['portfolio_slug'];
@$skills_slug    = $stag_options['skills_slug'];
$rewrite         = defined( 'STAG_PORTFOLIO_DISABLE_REWRITE' ) && STAG_PORTFOLIO_DISABLE_REWRITE ? false : array('slug' => $portfolio_slug, 'with_front' => false);

$portfolio_args = array(
	'labels'            => $portfolio_labels,
	'public'            => true,
	'show_ui'           => true,
	'show_in_menu'      => true,
	'show_in_nav_menus' => false,
	'rewrite'           => $rewrite,
	'supports'          => apply_filters( 'stag_portfolio_supports', array( 'title', 'editor', 'thumbnail', 'revisions' ) ),
	'taxonomies'        => array( 'skill' )
);

register_post_type( 'portfolio', apply_filters( 'stag_portfolio_post_type_args', $portfolio_args ) );

register_taxonomy( 'skill', 'portfolio', array(
	'label'             => __( 'Skills', 'stag' ),
	'singular_label'    => __( 'Skill', 'stag' ),
	'public'            => true,
	'hierarchical'      => true,
	'show_ui'           => true,
	'show_in_nav_menus' => false,
	'args'              => array( 'orderby' => 'term_order' ),
	'query_var'         => true,
	'rewrite'           => array( 'slug' => $skills_slug, 'hierarchical' => true)
) );

function stag_portfolio_edit_columns( $columns ) {
	$columns = array(
		"cb" => "<input type=\"checkbox\">",
		"title" => __( 'Portfolio Title', 'stag' ),
		"type" => __( 'Skills', 'stag' ),
		"date" => __( 'Date', 'stag' )
	);
	return $columns;
}

function stag_portfolio_custom_column( $columns ) {
	global $post;
	switch ($columns){
		case 'type':
		echo get_the_term_list($post->ID, 'skill', '', ', ','');
		break;
	}
}

add_filter("manage_edit-portfolio_columns", "stag_portfolio_edit_columns");
add_action("manage_posts_custom_column",  "stag_portfolio_custom_column");
