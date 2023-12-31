<?php

/**
 * @author OnTheGo Systems
 */
class WPML_Custom_Fields_Post_Meta_Info implements IWPML_Action {
	const RESOURCES_HANDLE = 'wpml-cf-info';
	const AJAX_ACTION      = 'wpml-cf-info-get';

	private $translatable_element_factory;

	/**
	 * WPML_Custom_Fields_Post_Meta_Info constructor.
	 *
	 * @param WPML_Translation_Element_Factory $translatable_element_factory
	 */
	public function __construct( WPML_Translation_Element_Factory $translatable_element_factory ) {
		$this->translatable_element_factory = $translatable_element_factory;
	}

	public function add_hooks() {
		add_action( 'wp_ajax_wpml_cf_get_info', array( $this, 'get_info_ajax' ) );
		add_filter( 'wpml_custom_field_original_data', array( $this, 'get_info_filter' ), 10, 3 );
	}

	public function get_info_ajax() {
		$result = null;

		if ( check_ajax_referer( self::AJAX_ACTION, 'nonceGet', false ) ) {
			$meta_id = filter_var( $_GET['meta_id'], FILTER_SANITIZE_NUMBER_INT );
			if ( $meta_id ) {
				/** @var \stdClass $custom_field */
				$custom_field = get_post_meta_by_id( (int) $meta_id );
				if ( $custom_field ) {
					$post_id  = $custom_field->post_id;
					$meta_key = $custom_field->meta_key;
					$result   = $this->get_info( $post_id, $meta_key );
					if ( $result ) {
						wp_send_json_success( $result );

						return;
					}
				} else {
					$result = 'Custom field not found';
				}
			} else {
				$result = 'Missing meta_id';
			}
		}
		wp_send_json_error( $result );
	}

	public function get_info_filter( $ignore, $post_id, $meta_key ) {
		return $this->get_info( $post_id, $meta_key );
	}

	private function get_info( $post_id, $meta_key ) {
		$post_element     = $this->translatable_element_factory->create( $post_id, 'post' );
		$original_element = $post_element->get_source_element();

		if ( $original_element && $original_element->get_id() !== $post_element->get_id() ) {

			return array(
				'meta_key' => $meta_key,
				'value'    => get_post_meta( $original_element->get_id(), $meta_key, true )
			);
		}

		return null;
	}
}
