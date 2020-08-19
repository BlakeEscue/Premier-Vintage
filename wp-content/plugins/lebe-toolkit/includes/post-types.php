<?php
/**
 * @version    1.0
 * @package    Lebe_Toolkit
 * @author     FamiThemes
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Class Toolkit Post Type
 *
 * @since    1.0
 */
if ( !class_exists( 'Lebe_Toolkit_Posttype' ) ) {
	class Lebe_Toolkit_Posttype
	{

		public function __construct()
		{
			add_action( 'init', array( &$this, 'init' ), 9999 );
		}

		public static function init()
		{
			/*Mega menu */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Mega Builder', 'lebe-toolkit' ),
					'singular_name'      => __( 'Mega menu item', 'lebe-toolkit' ),
					'add_new'            => __( 'Add new', 'lebe-toolkit' ),
					'add_new_item'       => __( 'Add new menu item', 'lebe-toolkit' ),
					'edit_item'          => __( 'Edit menu item', 'lebe-toolkit' ),
					'new_item'           => __( 'New menu item', 'lebe-toolkit' ),
					'view_item'          => __( 'View menu item', 'lebe-toolkit' ),
					'search_items'       => __( 'Search menu items', 'lebe-toolkit' ),
					'not_found'          => __( 'No menu items found', 'lebe-toolkit' ),
					'not_found_in_trash' => __( 'No menu items found in trash', 'lebe-toolkit' ),
					'parent_item_colon'  => __( 'Parent menu item:', 'lebe-toolkit' ),
					'menu_name'          => __( 'Menu Builder', 'lebe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'Mega Menus.', 'lebe-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'lebe_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'megamenu', $args );

			/* Footer */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Footers', 'lebe-toolkit' ),
					'singular_name'      => __( 'Footers', 'lebe-toolkit' ),
					'add_new'            => __( 'Add New', 'lebe-toolkit' ),
					'add_new_item'       => __( 'Add new footer', 'lebe-toolkit' ),
					'edit_item'          => __( 'Edit footer', 'lebe-toolkit' ),
					'new_item'           => __( 'New footer', 'lebe-toolkit' ),
					'view_item'          => __( 'View footer', 'lebe-toolkit' ),
					'search_items'       => __( 'Search template footer', 'lebe-toolkit' ),
					'not_found'          => __( 'No template items found', 'lebe-toolkit' ),
					'not_found_in_trash' => __( 'No template items found in trash', 'lebe-toolkit' ),
					'parent_item_colon'  => __( 'Parent template item:', 'lebe-toolkit' ),
					'menu_name'          => __( 'Footer Builder', 'lebe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Footer.', 'lebe-toolkit' ),
				'supports'            => array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'lebe_menu',
				'menu_position'       => 4,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
			);
			register_post_type( 'footer', $args );
			/*NewsLetter */
			$args = array(
				'labels'              => array(
					'name'               => __( 'NewsLetter', 'lebe-toolkit' ),
					'singular_name'      => __( 'NewsLetter item', 'lebe-toolkit' ),
					'add_new'            => __( 'Add new', 'lebe-toolkit' ),
					'add_new_item'       => __( 'Add new NewsLetter', 'lebe-toolkit' ),
					'edit_item'          => __( 'Edit NewsLetter', 'lebe-toolkit' ),
					'new_item'           => __( 'New NewsLetter', 'lebe-toolkit' ),
					'view_item'          => __( 'View NewsLetter', 'lebe-toolkit' ),
					'search_items'       => __( 'Search NewsLetter', 'lebe-toolkit' ),
					'not_found'          => __( 'No NewsLetter found', 'lebe-toolkit' ),
					'not_found_in_trash' => __( 'No NewsLetter found in trash', 'lebe-toolkit' ),
					'parent_item_colon'  => __( 'Parent NewsLetter:', 'lebe-toolkit' ),
					'menu_name'          => __( 'NewsLetter Builder', 'lebe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template NewsLetter.', 'lebe-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'lebe_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'newsletter', $args );

			/*Size Guide */
			$args = array(
				'labels'              => array(
					'name'               => __( 'Size Guide', 'lebe-toolkit' ),
					'singular_name'      => __( 'Size Guide item', 'lebe-toolkit' ),
					'add_new'            => __( 'Add new', 'lebe-toolkit' ),
					'add_new_item'       => __( 'Add new Size Guide', 'lebe-toolkit' ),
					'edit_item'          => __( 'Edit Size Guide', 'lebe-toolkit' ),
					'new_item'           => __( 'New Size Guide', 'lebe-toolkit' ),
					'view_item'          => __( 'View Size Guide', 'lebe-toolkit' ),
					'search_items'       => __( 'Search Size Guide', 'lebe-toolkit' ),
					'not_found'          => __( 'No Size Guide found', 'lebe-toolkit' ),
					'not_found_in_trash' => __( 'No Size Guide found in trash', 'lebe-toolkit' ),
					'parent_item_colon'  => __( 'Parent Size Guide:', 'lebe-toolkit' ),
					'menu_name'          => __( 'Size Guide Builder', 'lebe-toolkit' ),
				),
				'hierarchical'        => false,
				'description'         => __( 'To Build Template Size Guide.', 'lebe-toolkit' ),
				'supports'            => array( 'title', 'editor' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'lebe_menu',
				'menu_position'       => 3,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-welcome-widgets-menus',
			);
			register_post_type( 'sizeguide', $args ); 
            /* Project */

            $labels = array(
                'name'               => _x( 'Project', 'lebe-toolkit' ),
                'singular_name'      => _x( 'Project', 'lebe-toolkit' ),
                'add_new'            => __( 'Add New', 'lebe-toolkit' ),
                'all_items'          => __( 'Projects', 'lebe-toolkit' ),
                'add_new_item'       => __( 'Add New Project', 'lebe-toolkit' ),
                'edit_item'          => __( 'Edit Project', 'lebe-toolkit' ),
                'new_item'           => __( 'New Project', 'lebe-toolkit' ),
                'view_item'          => __( 'View Project', 'lebe-toolkit' ),
                'search_items'       => __( 'Search Project', 'lebe-toolkit' ),
                'not_found'          => __( 'No Project found', 'lebe-toolkit' ),
                'not_found_in_trash' => __( 'No Project found in Trash', 'lebe-toolkit' ),
                'parent_item_colon'  => __( 'Parent Project', 'lebe-toolkit' ),
                'menu_name'          => __( 'Projects', 'lebe-toolkit' ),
            );
            $args   = array(
                'labels'              => $labels,
                'description'         => 'Post type Project',
                'supports'            => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                ),
                'hierarchical'        => false,
                'rewrite'             => true,
                'public'              => true,
                'show_ui'             => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 4,
                'can_export'          => true,
                'has_archive'         => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'post',
                'menu_icon'           => 'dashicons-images-alt2',
            );

            //register_post_type( 'project', $args );
			
		}
	}

	new Lebe_Toolkit_Posttype();
}
