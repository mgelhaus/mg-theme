<?php
/**
 * Core Theme functionality.
 *
 * @package MG\Theme
 * 
 * @link https://developer.wordpress.org/themes/basics/categories-tags-custom-taxonomies/
 */
namespace MG\Theme;

class Core
{
	private static $initialized = false;
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @param null
	 *
	 * @uses add_filter 
	 * @link https://developer.wordpress.org/reference/functions/add_filter/
	 */
	public static function initialize() {
		if ( false === static::$initialized ) {
			// Theme Setup
			\add_action( 'after_setup_theme',
				__CLASS__ . '::addThemeSupport' );
			\add_action( 'after_setup_theme',
				__CLASS__ . '::setContentWidth', 2 );

			// Menus Setup
			\add_action( 'after_setup_theme',
				__CLASS__ . '::registerThemeMenus' );

			// Widgets Setup
			\add_action( 'widgets_init',
				__CLASS__ . '::registerThemeSidebars' );
			\add_action( 'widgets_init', 
				__CLASS__ . '::registerThemeWidgets' );

			// Customizer Setup
			\add_action( 'customize_register',
				__NAMESPACE__ . '\\Customizer\\Actions::registerPostMessage' );
			\add_action( 'customize_preview_init',
				__NAMESPACE__ . '\\Customizer\\Actions::addPreviewJS' );

			// Category Flusher
			\add_action( 'edit_category',
				__NAMESPACE__ . '\\Category\\Actions::transientFlusher' );
			\add_action( 'save_post',
				__NAMESPACE__ . '\\Category\\Actions::transientFlusher' );

			// JetPack
			\add_action( 'after_setup_theme',
				__NAMESPACE__ . '\\Plugin\\JetPack\\Actions::addThemeSupport' );
			
			// Remove Core Items from Theme
			\add_action( 'after_setup_theme',
				__CLASS__ . '::removeHeadLinks' );
			\add_action( 'widgets_init', 
				__CLASS__ . '::unregisterDefaultWidgets' );

			// Prevent repeat adding init actions
			static::$initialized = true;
		}
		// Add Actions
		\add_action( 'wp_enqueue_scripts',	__CLASS__ . '::enqueueScripts' );

		// Add Filters
		\add_filter( 'get_archives_link', 
			__NAMESPACE__ . '\\Archive\\Item\\Filters::markupIndent', 10, 6 );
		\add_filter( 'get_terms_args', 
			__NAMESPACE__ . '\\Category\\Filters::addThemeWalker' );
		\add_filter( 'category_css_class', 
			__NAMESPACE__ . '\\Category\\Filters::limitClassAttributes', 10, 4 );
		\add_filter( 'wp_list_categories', 
			__NAMESPACE__ . '\\Category\\Filters::removeEmptyClassAttributes', 10, 2 );
		\add_filter( 'wp_list_categories', 
			__NAMESPACE__ . '\\Category\\Filters::changeCurrentToActive', 10, 2 );
		\add_filter( 'wp_list_categories', 
			__NAMESPACE__ . '\\Category\\Filters::removeErroneousMarkupNewlines', 11, 2 );
		\add_filter( 'wp_list_categories', 
			__NAMESPACE__ . '\\Category\\Filters::markupIndent', 10, 2 );
		\add_filter( 'body_class', 
			__NAMESPACE__ . '\\Document\\Body\\Filters::addClassAttributes' );
		\add_filter( 'wp_nav_menu_args', 
			__NAMESPACE__ . '\\Menu\\Filters::addThemeWalker' );
		\add_filter( 'nav_menu_css_class', 
			__NAMESPACE__ . '\\Menu\\Filters::limitClassAttributes', 10, 4 );
		\add_filter( 'nav_menu_item_id', 
			__NAMESPACE__ . '\\Menu\\Filters::limitClassAttributes', 10, 4 );
		\add_filter( 'page_css_class', 
			__NAMESPACE__ . '\\Menu\\Filters::limitClassAttributes', 10, 4 );
		\add_filter( 'wp_nav_menu', 
			__NAMESPACE__ . '\\Menu\\Filters::changeCurrentToActive', 10, 2 );
		\add_filter( 'wp_nav_menu', 
			__NAMESPACE__ . '\\Menu\\Filters::removeEmptyClassAttributes', 10, 2 );
		\add_filter( 'wp_nav_menu', 
			__NAMESPACE__ . '\\Menu\\Filters::restructureMarkup', 11, 2 );
		\add_filter( 'navigation_markup_template',	
			__NAMESPACE__ . '\\Entry\\Navigation\\Filters::fixMarkup', 10, 2 );
		\add_filter( 'wp_link_pages_link',	
			__NAMESPACE__ . '\\Entry\\Navigation\\Item\\Filters::fixMarkup', 10, 2 );
		\add_filter( 'get_search_form', 
			__NAMESPACE__ . '\\Searchform\\Filters::restructureMarkup' );
	}

	/**
	 * Add Theme Support functions
	 */
	public static function addThemeSupport() {
		// Set the theme text domain for translation
		\load_theme_textdomain( THEME_TEXT_DOMAIN, \get_template_directory() . '/languages' );
		
		// set HTML5 support
		\add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// add RSS Feed Support
		\add_theme_support( 'automatic-feed-links' );

		// add custom title head html support
		\add_theme_support( 'title-tag' );

		// add custom background functionality
		\add_theme_support( 'custom-background', 
			\apply_filters( strtolower( str_replace( '\\', '-', __NAMESPACE__ ) ) . '-custom-background-args', 
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				) 
			) 
		);

		// add custom header functionality
		\add_theme_support( 'custom-header', 
			\apply_filters( strtolower( str_replace( '\\', '-', __NAMESPACE__ ) ) . '-custom-header-args', 
				array(
					'default-image'			=> '',
					'default-text-color'	=> '000000',
					'width'					=> 1000,
					'height'				=> 250,
					'flex-height'			=> true,
					'wp-head-callback'		=> __NAMESPACE__ . '\\Header\\Render::style'
				)
			) 
		);

		// add post thumbnail support
		\add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	public static function setContentWidth() {
		$GLOBALS['content_width'] = \apply_filters( THEME_TEXT_DOMAIN . '-content-width', 640 );
	}

	/**
	 * Register Menu area(s).
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public static function registerThemeMenus() {
		\register_nav_menus( array(
			'primary' => \esc_html__( 'Primary', THEME_TEXT_DOMAIN ),
		) );
	}

	/**
	 * Register widget area(s).
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public static function registerThemeSidebars() {
		register_sidebar( array(
			'name'			=> esc_html__( 'Sidebar', THEME_TEXT_DOMAIN ),
			'id'			=> 'sidebar-1',
			'description'	=> esc_html__( 'Add widgets here.', THEME_TEXT_DOMAIN ),
			'before_widget'	=> '<section id="%1$s" class="widget %2$s">' . "\n",
			'after_widget'	=> '</section>' . "\n",
			'before_title'	=> '<h2 class="widget-title">',
			'after_title'	=> '</h2>' . "\n",
		) );
		register_sidebar( array(
			'name'			=> esc_html__( '404 Help Links', THEME_TEXT_DOMAIN ),
			'id'			=> 'help-links-404',
			'description'	=> esc_html__( 'Add widgets here.', THEME_TEXT_DOMAIN ),
			'before_widget'	=> '<section id="%1$s" class="widget %2$s">' . "\n",
			'after_widget'	=> '</section>' . "\n",
			'before_title'	=> '<h2 class="widget-title">',
			'after_title'	=> '</h2>' . "\n",
		) );
	}

	/**
	 * register theme widget(s).
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public static function registerThemeWidgets() {
		\register_widget( __NAMESPACE__ . '\\Archive\\Widget' );
		\register_widget( __NAMESPACE__ . '\\Category\\Widget' );
		\register_widget( __NAMESPACE__ . '\\Entry\\Widget' );
	}

	/**
	 * Remove Default Head functionality/markup
	 *
	 * @uses add_filter
	 * @uses remove_action
	 */
	public static function removeHeadLinks() {
		\add_filter( 'the_generator', '__return_false' );
		\add_filter( 'show_admin_bar','__return_false' );
		\remove_action( 'wp_head', 'feed_links_extra', 3 );
		\remove_action( 'wp_head', 'wp_generator' );
		\remove_action( 'wp_head', 'wlwmanifest_link' );
		\remove_action( 'wp_head', 'rsd_link' );
		\remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		\remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		\remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		\remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		\remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		\remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}
	
	/**
	 * Unregister default widgets.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public static function unregisterDefaultWidgets() {
		\unregister_widget( 'WP_Widget_Pages' );
		\unregister_widget( 'WP_Widget_Calendar' );
		\unregister_widget( 'WP_Widget_Archives' );
		\unregister_widget( 'WP_Widget_Links' );
		\unregister_widget( 'WP_Widget_Meta' );
		\unregister_widget( 'WP_Widget_Search' );
		\unregister_widget( 'WP_Widget_Text' );
		\unregister_widget( 'WP_Widget_Categories' );
		\unregister_widget( 'WP_Widget_Recent_Posts' );
		\unregister_widget( 'WP_Widget_Recent_Comments' );
		\unregister_widget( 'WP_Widget_RSS' );
		\unregister_widget( 'WP_Widget_Tag_Cloud' );
		\unregister_widget( 'WP_Nav_Menu_Widget' );
	}
	
	/**
	 * Enqueue scripts and styles.
	 */
	public static function enqueueScripts() {
		wp_enqueue_style(  THEME_TEXT_DOMAIN . '-style', get_stylesheet_uri() );
		wp_enqueue_script( THEME_TEXT_DOMAIN . '-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
		wp_enqueue_script( THEME_TEXT_DOMAIN . '-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}