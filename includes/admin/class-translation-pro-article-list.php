<?php
		// Include WP's list table class.
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		class CLTP_TranslationPro_Article_List extends WP_List_Table {
			/**
			 * Prepare the items for the table to process
			 *
			 * @return Void
			 */
			public function prepare_items() {
				$columns  = $this->get_columns();
				$hidden   = $this->get_hidden_columns();
				$sortable = $this->get_sortable_columns();
				$data     = $this->getData();
				//usort( $data, array( &$this, 'sort_data' ) );

				$perPage     = $data["per_page"];
				$currentPage = $this->get_pagenum();
				$totalItems  = $data["total_items"];
				$this->set_pagination_args( array(
					'total_items' => $totalItems,
					'per_page'    => $perPage
				) );
				//$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
				$this->_column_headers = array( $columns, $hidden, $sortable );
				$this->items           = $data["data"];
			}

			/**
			 * Override the parent columns method. Defines the columns to use in your listing table
			 *
			 * @return Array
			 */
			public function get_columns() {
				$columns = array(
					'project_name' => 'Project name',
					'product_name' => 'Product',
					'status'       => 'Status',
					'price'        => 'Price',
					'author'       => 'Author',
					'created_at'   => 'Created at'
				);

				return $columns;
			}

			/**
			 * Define which columns are hidden
			 *
			 * @return Array
			 */
			public function get_hidden_columns() {
				return array();
			}

			/**
			 * Define the sortable columns
			 *
			 * @return Array
			 */
			public function get_sortable_columns() {
				return array(
					'created_at'   => array(
						'created_at',
						false
					),
					'project_name' => array(
						'name',
						false
					),
					'product_name' => array(
						'product_id',
						false
					),
					'status'       => array(
						'status',
						false
					),
					'price'        => array(
						'price',
						false
					),
					'author'       => array(
						'author',
						false
					),
				);
			}

			/**
			 * Define what data to show on each column of the table
			 *
			 * @param  Array  $item        Data
			 * @param  String $column_name - Current column name
			 *
			 * @return Mixed
			 */
			public function column_default( $item, $column_name ) {
				switch ( $column_name ) {
					case 'project_name':
						if ( $item["status"] == "Completed" ) {
							return '<a href="admin.php?page=cltp&action=view&id=' . $item['id'] . '">' . $item["project_name"] . '</a>';
						} else {
							return $item["project_name"];
						}
					case 'product_name':
					case 'author':
						return $item[ $column_name ];
					case 'status':
						switch ( $item["status"] ) {
							case 'Completed':
								return '<label class="cl-success">Completed</label>';
								break;
							case 'Pending':
								return '<label class="cl-info">Pending</label>';
								break;
							case 'Submitted':
								return '<label class="cl-secondary">Submitted</label>';
								break;
							case 'Started':
								return '<label class="cl-warning">Started</label>';
								break;
						}
					case 'price':
						return "$" . $item["price"];
					case 'api':
						return $item["api"] ? '<label class="cl-success">Yes</label>' : '<label class="cl-danger">No</label>';
					case 'created_at':
						return date_i18n( get_option( 'date_format' ), strtotime( $item["created_at"]["date"] ) );
					default:
						return print_r( $item, true );
				}
			}

			/**
			 * Allows you to sort the data by the variables set in the $_GET
			 *
			 * @return Mixed
			 */
			private function sort_data( $a, $b ) {
				// Set defaults
				$orderby = 'title';
				$order   = 'asc';
				// If orderby is set, use this as the sort column
				if ( ! empty( $_GET['orderby'] ) ) {
					$orderby = $_GET['orderby'];
				}
				// If order is set use this as the order
				if ( ! empty( $_GET['order'] ) ) {
					$order = $_GET['order'];
				}
				$result = strcmp( $a[ $orderby ], $b[ $orderby ] );
				if ( $order === 'asc' ) {
					return $result;
				}

				return $result;
			}

			/**
			 * Get data from API
			 * @return array|mixed|object
			 */
			private function getData() {
				$params = array(
					'page'    => isset( $_GET["paged"] ) ? $_GET["paged"] : 1,
					'search'  => sanitize_text_field(isset( $_GET["s"] ) ? $_GET["s"] : ""),
					'sort'    => isset( $_GET["orderby"] ) ? $_GET["orderby"] : "",
					'sort_by' => sanitize_sql_orderby(isset( $_GET["order"] ) ? $_GET["order"] : "asc"),
					'product_id'    => [1]
				);

				return \Contentlocalized\ContentlocalizedAPI::ArticleList( $params );
			}
		}
	//}