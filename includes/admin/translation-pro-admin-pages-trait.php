<?php
	namespace Contentlocalized {

		trait CLTP_TranslationPro_Admin_Pages_Trait {
			/**
			 * Call method by get params
			 */
			public function Router() {

				if ( $_GET['page'] != "cltp" ) {
					exit();
				}

				$action = isset( $_GET['action'] ) ? $_GET['action'] : false;


				switch ( $_GET['action'] ) {
					case "new_translation":
						$this->NeedToBeLogged();
						$this->NewTranslation();
						break;
					case "new_writing":
						break;
					case "view": //View article
						$this->NeedToBeLogged();
						$this->ViewArticle();
						break;
					case "settings": //View article
						$this->Settings();
						break;
					case "settings_login": //View article
						$this->SettingsProcess();
						break;
					default: //Call dashboard
						$this->NeedToBeLogged();
						$this->DashboardPage();
						break;
				}
			}

			/**
			 * Display dashboard
			 */
			public function DashboardPage() {
				require_once CLTP_TRANSLATIONPRO_PLUGIN_PATH . "admin/partials/dashboard.php";
			}

			/**
			 * Display article
			 */
			public function ViewArticle() {
				$order = ContentlocalizedAPI::ArticleView( $_GET['id'] );


				require_once CLTP_TRANSLATIONPRO_PLUGIN_PATH . "admin/partials/view_article.php";
			}

			/**
			 * Create new translation article
			 *
			 * @throws Requests_Exception
			 */
			public function NewTranslation() {
				$source_lng = ContentlocalizedAPI::GetLanguageList( 1 );
				$target_lng = ContentlocalizedAPI::GetLanguageList( 1, 'us' );
				$devilery   = ContentlocalizedAPI::GetDeliveryByProductId( 1 );
				$pages= get_pages( [] );
				$posts= get_posts( [] );

				require_once CLTP_TRANSLATIONPRO_PLUGIN_PATH . "admin/partials/new_translation.php";
			}

			/**
			 * Create order
			 */
			public function CreateOrder() {
				$params = array(
					'project_name'  => sanitize_text_field($_POST["project_name"]),
					'instructions'  => sanitize_textarea_field($_POST["excerpt"]),
					'content'       => $_POST["content"],
					'from_lng_code' => $_POST["from_id"],
					'to_lng_code'   => $_POST["to_id"],
					'delivery_id'   => $_POST["delivery"],
					'rating_id'     => 1,
				);

				if(strlen($params["project_name"]) < 4){
					set_transient( "cl_msg_error", "Project name is missing", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=new_translation' );
					exit();
				}

				if(empty($params["from_lng_code"])){
					set_transient( "cl_msg_error", "Please select source language", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=new_translation' );
					exit();
				}

				if(count($params["to_lng_code"]) == 0 || !is_array($params["to_lng_code"])) {
					set_transient( "cl_msg_error", "Please select target language(s)", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=new_translation' );
					exit();
				}

				if(strlen($params["content"]) < 4){
					set_transient( "cl_msg_error", "Please enter text for translation", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=new_translation' );
					exit();
				}

				$result = ContentlocalizedAPI::CreateOrder( 1, $params );

				if ( isset( $result['status'] ) && $result['status'] == 'false' ) {
					set_transient( "cl_msg_error", $result['error'], 45 );
					wp_safe_redirect( 'admin.php?page=cltp' );
					exit();
				} else {
					set_transient( "cl_msg_success", 'Project created successfully!', 45 );
					wp_safe_redirect( 'admin.php?page=cltp' );
					exit();
				}
			}

			/**
			 * Return languages by source
			 *
			 * @throws Requests_Exception
			 */
			public function GetTargetLanguage() {
				$source = isset( $_POST['source'] ) ? $_POST['source'] : "us";

				$target_lng = ContentlocalizedAPI::GetLanguageList( 1, $source );

				echo wp_send_json( $target_lng );
			}

			/**
			 * Calculate project
			 *
			 * @throws Requests_Exception
			 */
			public function CalculateProject() {
				$params = array(
					'from_id'  => $_POST['from_id'],
					'to_id'    => $_POST['to_id'],
					'content'  => $_POST['content'],
					'delivery' => $_POST['delivery'],
					'product'  => $_POST['product'],
				);

				$result = ContentlocalizedAPI::CalculateOrder( $params );

				echo wp_send_json( $result );
			}

			/**
			 *  Display settings page
			 */
			public function Settings() {
				require_once CLTP_TRANSLATIONPRO_PLUGIN_PATH . "admin/partials/settings.php";
			}

			/**
			 * Login
			 */
			public function SettingsProcess() {
				$user = sanitize_email($_POST["username"]);
				$pass = sanitize_text_field($_POST["password"]);

				if ( empty( $user ) || empty( $pass ) ) {
					set_transient( "cl_msg_error", 'Please enter username and password', 45 );
					wp_safe_redirect( wp_get_referer() );
					exit();
				}

				if ( ContentlocalizedAPI::CheckLoginDetails( $user, $pass ) != null ) {
					//save new login details
					update_option( 'cltp_api_username', $user );
					update_option( 'cltp_api_password', $pass );
					update_option( 'cltp_api_pass_test', '1' );

					set_transient( "cl_msg_success", 'You are successfully logged in', 45 );
					wp_safe_redirect( 'admin.php?page=cltp' );
					exit();
				} else {
					set_transient( "cl_msg_error", 'Invalid credentials.', 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
					exit();
				}
			}

			/**
			 * Logout
			 */
			public function SettingsLogoutProcess() {
				update_option( 'cltp_api_username', '' );
				update_option( 'cltp_api_password', '' );
				update_option( 'cltp_api_pass_test', '0' );

				set_transient( "cl_msg_success", 'You are successfully logout', 45 );
				wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
				exit();
			}

			/**
			 * Sing Up
			 */
			public function SingUpProcess() {
				$data = array(
					'first_name'       => sanitize_text_field(isset( $_POST['first_name'] ) ? $_POST['first_name'] : ""),
					'last_name'        => sanitize_text_field(isset( $_POST['last_name'] ) ? $_POST['last_name'] : ""),
					'email'            => sanitize_email(isset( $_POST['email'] ) ? $_POST['email'] : ""),
					'password'         => sanitize_text_field(isset( $_POST['password'] ) ? $_POST['password'] : ""),
					'personal_account' => 1,
					'company_name'     => '',
					'url'              => esc_url(isset( $_POST['url'] ) ? $_POST['url'] : ""),
				);

				if(empty($data["first_name"])) {
					set_transient( "cl_msg_error", "Please enter first name", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
					exit();
				}

				if(empty($data["last_name"])) {
					set_transient( "cl_msg_error", "Please enter last name", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
					exit();
				}

				if(!is_email($data["email"])) {
					set_transient( "cl_msg_error", "Please enter valid email", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
					exit();
				}

				if(empty($data["password"])) {
					set_transient( "cl_msg_error", "Please enter your password", 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
					exit();
				}

				try {
					$result = ContentlocalizedAPI::SignUp( $data );

					if ( ! $result["status"] ) {
						set_transient( "cl_msg_error", $result["msg"][0], 45 );
						wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
						exit();
					}
				} catch ( Exception $e ) {
					set_transient( "cl_msg_error", $e->getMessage(), 45 );
					wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
					exit();
				}

				set_transient( "cl_msg_success", $result['msg'], 45 );
				wp_safe_redirect( 'admin.php?page=cltp&action=settings' );
				exit();


			}

			public function SelectPageSource() {
				if(isset($_POST["type"]) && $_POST["type"] == 1){
					$p = get_page($_POST["id"]);
				}else {
					$p = get_post($_POST["id"]);
				}


				if($p)
					echo wp_send_json($p->post_content);
				else
					echo wp_send_json("");
			}


			public function AddBalance() {
				echo '<form id="redirect" action="' . ContentlocalizedAPI::GetAutologinEndPoint() . '" method="POST">';
				echo '<input type="hidden" name="email" value="' . get_option( 'cltp_api_username' ) . '" />';
				echo '<input type="hidden" name="password" value="' . get_option( 'cltp_api_password' ) . '" />';
				echo '<div style="text-align: center">';
				echo '<input type="submit" value="Redirect" />';
				echo '</div>';
				echo '</form>';

				echo '<script>document.getElementById("redirect").submit();</script>';
			}

			/**
			 * Need to be logged in
			 */
			private function NeedToBeLogged() {
				if ( get_option( 'cltp_api_pass_test' ) != 1 ) {
					set_transient( "cl_msg_error", 'You need to be logged in to perform that action', 45 );
					echo '<script> location.replace("admin.php?page=cltp&action=settings"); </script>';
					exit();
				}
			}
		}
	}