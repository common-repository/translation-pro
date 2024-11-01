<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contentlocalized/
 * @subpackage Contentlocalized/CLTP_TranslationPro_Admin
 * @author     ContentLocalized <marko.janosevic@firstbeatmedia.com >
 */
	namespace Contentlocalized {

		class CLTP_TranslationPro_Admin {

			use CLTP_TranslationPro_Admin_Pages_Trait;

			/**
			 * The ID of this plugin.
			 *
			 * @since    1.0.0
			 * @access   private
			 * @var      string $plugin_name The ID of this plugin.
			 */
			private $plugin_name;

			/**
			 * The version of this plugin.
			 *
			 * @since    1.0.0
			 * @access   private
			 * @var      string $version The current version of this plugin.
			 */
			private $version;

			/**
			 * Initialize the class and set its properties.
			 *
			 * @since    1.0.0
			 *
			 * @param      string $plugin_name The name of this plugin.
			 * @param      string $version     The version of this plugin.
			 */
			public function __construct( $plugin_name, $version ) {

				$this->plugin_name = $plugin_name;
				$this->version     = $version;

			}

			/**
			 * Register the stylesheets for the admin area.
			 *
			 * @since    1.0.0
			 */
			public function enqueue_styles() {

				/**
				 * This function is provided for demonstration purposes only.
				 *
				 * An instance of this class should be passed to the run() function
				 * defined in Translation_Pro_Loader as all of the hooks are defined
				 * in that particular class.
				 *
				 * The Translation_Pro_Loader will then create the relationship
				 * between the defined hooks and the functions defined in this
				 * class.
				 */

				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/translation-pro-admin.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), '4.0.6', 'all' );
			}

			/**
			 * Register the JavaScript for the admin area.
			 *
			 * @since    1.0.0
			 */
			public function enqueue_scripts() {

				/**
				 * This function is provided for demonstration purposes only.
				 *
				 * An instance of this class should be passed to the run() function
				 * defined in Translation_Pro_Loader as all of the hooks are defined
				 * in that particular class.
				 *
				 * The Translation_Pro_Loader will then create the relationship
				 * between the defined hooks and the functions defined in this
				 * class.
				 */

				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/translation-pro-admin.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ), '4.0.6', false );

			}

			/**
			 * Register bar menu for admin area
			 *
			 * @since   1.0.0
			 */
			public function bar_menu() {
				add_menu_page( 'Translation.Pro', 'Translation.Pro', 'manage_options', 'cltp', array(
					$this,
					'Router'
				), plugin_dir_url( __DIR__ ) . "admin/img/icon.png" );

				add_submenu_page( 'cltp', 'Dashboard', 'Dashboard', 'manage_options', 'cltp', array(
					$this,
					'Router'
				) );

				add_submenu_page( 'cltp', 'New Translation', 'New Translation', 'manage_options', 'cltp&action=new_translation', array(
					$this,
					'Router'
				) );

				add_submenu_page( 'cltp', 'Settings', 'Settings', 'manage_options', 'cltp&action=settings', array(
					$this,
					'Router'
				) );


				global $submenu_file;
				if ( isset( $_GET['page'] ) && $_GET['page'] == "cltp" ) {
					if ( isset( $_GET["action"] ) ) {
						switch ( $_GET["action"] ) {
							case "new_translation":
								$submenu_file = "cltp&action=new_translation";
								break;
							case "new_writing":
								$submenu_file = "cltp&action=new_writing";
								break;
							case "settings":
								$submenu_file = "cltp&action=settings";
								break;
							case "view":
								$submenu_file = "";
								break;
							default:
								$submenu_file = "cltp";
								break;
						}
					}
				}
			}

			/**
			 * Messages
			 */
			public function Messages() {
				$msg = null;

				if ( $msg = get_transient( "cl_msg_error" ) ) {
					add_action( 'admin_notices', function () use ( $msg ) {
						echo "<div class='error'><p>" . $msg . "</p></div>";
					} );

					delete_transient( "cl_msg_error" );
				}

				if ( $msg = get_transient( "cl_msg_success" ) ) {
					add_action( 'admin_notices', function () use ( $msg ) {
						echo "<div class='updated'><p>" . $msg . "</p></div>";
					} );

					delete_transient( "cl_msg_success" );
				}
			}

		}
	}