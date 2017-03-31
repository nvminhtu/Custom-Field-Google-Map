<?php
/*
Plugin Name: CMB2 Field Type: Google Maps
Description: Google Maps field type for CMB2.
Version: 1.1.2s
Author: Tu Nguyen
Author URI: http://minhtu.net/
License: GPLv2+
*/

/**
 * Class PW_CMB2_Field_Google_Maps
 */
class PW_CMB2_Field_Google_Maps {

	/**
	 * Current version number
	 */
	const VERSION = '2.1.1';

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	public function __construct() {
		add_filter( 'cmb2_render_pw_map', array( $this, 'render_pw_map' ), 10, 5 );
		add_filter( 'cmb2_sanitize_pw_map', array( $this, 'sanitize_pw_map' ), 10, 4 );
	}

	/**
	 * Render field
	 */
	public function render_pw_map( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		$this->setup_admin_scripts();

		echo '<p class="cmb2-metabox-description">Input your address / use maker to check your location</p>';

		echo '<input type="text" class="large-text pw-map-search" id="' . $field->args( 'id' ) . '" />';

		echo '<div class="pw-map"></div>';

		$field_type_object->_desc( true, true );


		echo '<p class="cmb2-metabox-place-name">Place Name</p>';
		echo $field_type_object->input( array(
			'type'       => 'text',
			'name'       => $field->args('_name') . '[place_name]',
			'value'      => isset( $field_escaped_value['place_name'] ) ? $field_escaped_value['place_name'] : '',
			'class'      => 'large-text',
			'desc'       => '',
		) );


		echo '<p class="cmb2-metabox-place-address">Place Address</p>';
		echo $field_type_object->input( array(
			'type'       => 'text',
			'name'       => $field->args('_name') . '[place_address]',
			'value'      => isset( $field_escaped_value['place_address'] ) ? $field_escaped_value['place_address'] : '',
			'class'      => 'large-text',
			'desc'       => '',
		) );
		
		echo $field_type_object->input( array(
			'type'       => 'hidden',
			'name'       => $field->args('_name') . '[latitude]',
			'value'      => isset( $field_escaped_value['latitude'] ) ? $field_escaped_value['latitude'] : '',
			'class'      => 'pw-map-latitude',
			'desc'       => '',
		) );
		echo $field_type_object->input( array(
			'type'       => 'hidden',
			'name'       => $field->args('_name') . '[longitude]',
			'value'      => isset( $field_escaped_value['longitude'] ) ? $field_escaped_value['longitude'] : '',
			'class'      => 'pw-map-longitude',
			'desc'       => '',
		) );
	}

	/**
	 * Optionally save the latitude/longitude values into two custom fields
	 */
	public function sanitize_pw_map( $override_value, $value, $object_id, $field_args ) {
		if ( isset( $field_args['split_values'] ) && $field_args['split_values'] ) {
			if ( ! empty( $value['latitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_latitude', $value['latitude'] );
			}

			if ( ! empty( $value['longitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_longitude', $value['longitude'] );
			}
		}

		return $value;
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function setup_admin_scripts() {
		wp_register_script( 'pw-google-maps-api', '//maps.googleapis.com/maps/api/js?key=AIzaSyCIpA4fYWolrgbB6esEOWLDfAYrjg9GoCA&libraries=places&language=ja&region=JP', null, null );
		wp_enqueue_script( 'pw-google-maps', plugins_url( 'js/script.js', __FILE__ ), array( 'pw-google-maps-api' ), self::VERSION );
		wp_enqueue_style( 'pw-google-maps', plugins_url( 'css/style.css', __FILE__ ), array(), self::VERSION );
	}
}
$pw_cmb2_field_google_maps = new PW_CMB2_Field_Google_Maps();
