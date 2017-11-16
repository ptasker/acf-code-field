<?php

class acf_code_field extends acf_field {

	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	21/03/2016
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'acf_code_field';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __( 'Code area field', 'acf-code-field' );


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'Code tools';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'mode'  => 'htmlmixed',
			'theme' => 'monokai',
		);


		$this->l10n = array(
			'error' => __( 'Error! Please enter a higher value', 'acf-code-field' ),
		);


		// do not delete!
		parent::__construct();
	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/


		// default_value
		acf_render_field_setting( $field, array(
			'label'        => __( 'Default Value', 'acf' ),
			'instructions' => __( 'Appears when creating a new post', 'acf' ),
			'type'         => 'textarea',
			'name'         => 'default_value',
		) );


		// placeholder
		acf_render_field_setting( $field, array(
			'label'        => __( 'Placeholder Text', 'acf' ),
			'instructions' => __( 'Appears within the input', 'acf' ),
			'type'         => 'text',
			'name'         => 'placeholder',
		) );

		acf_render_field_setting( $field, array(
			'label'        => __( 'Editor mode', 'acf' ),
			'instructions' => __( '', 'acf' ),
			'type'         => 'select',
			'name'         => 'mode',
			'choices'      => array(
				'htmlmixed'               => __( "HTML Mixed", 'acf' ),
				'javascript'              => __( "JavaScript", 'acf' ),
				'text/html'               => __( "HTML", 'acf' ),
				'css'                     => __( "CSS", 'acf' ),
				'application/x-httpd-php' => __( "PHP", 'acf' ),
			),
		) );

		$util = new ACF_Code_Field_Util();

		acf_render_field_setting( $field, array(
			'label'        => __( 'Editor theme', 'acf' ),
			'instructions' => __( 'Themes can be previewed on the <a href="https://codemirror.net/demo/theme.html#default" target="_blank">codemirror website</a>', 'acf' ),
			'type'         => 'select',
			'name'         => 'theme',
			'choices'      => $util->get_codemirror_themes(),
		) );
	}


	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {

		$dir       = plugin_dir_url( __FILE__ );
		$safe_slug = str_replace( "-", "_", $field['id'] );
		// vars
		$o = array( 'id', 'class', 'name', 'placeholder', 'mode', 'theme' );
		$e = '';


		// populate atts
		$atts = array();
		foreach ( $o as $k ) {
			$atts[ $k ] = $field[ $k ];
		}

		$atts['class'] = 'acf-code-field-box';

		$e .= '<textarea ' . acf_esc_attr( $atts ) . ' >';
		$e .= esc_textarea( $field['value'] );
		$e .= '</textarea>';


		echo $e;

		wp_enqueue_style( "codemirror-curr-style-{$field['theme']}", "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/theme/{$field['theme']}.css" );
	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_enqueue_scripts() {

		$dir = plugin_dir_url( __FILE__ );

		if ( version_compare( $GLOBALS['wp_version'], '4.9', '>=' ) ) {
			wp_enqueue_script( 'wp-codemirror' );
			wp_enqueue_style( 'wp-codemirror' );
			wp_enqueue_script( 'csslint' );
			wp_enqueue_script( 'jshint' );
			wp_enqueue_script( 'jsonlint' );
			wp_enqueue_script( 'htmlhint' );
			wp_enqueue_script( 'htmlhint-kses' );

			//Alias wp.CodeMirror to CodeMirror
			wp_add_inline_script( 'wp-codemirror', 'window.CodeMirror = wp.CodeMirror;' );
		} else {

			// CodeMirror isn't in WP core until WP 4.9
			wp_enqueue_script( 'acf-input-code-field-codemirror', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/lib/codemirror.js" );

			wp_enqueue_script( 'acf-input-code-field-codemirror-showhint', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/addon/hint/show-hint.js" );
			wp_enqueue_script( 'acf-input-code-field-codemirror-xmlhint', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/addon/hint/xml-hint.js" );
			wp_enqueue_script( 'acf-input-code-field-codemirror-htmlhint', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/addon/hint/html-hint.js" );

			wp_enqueue_style( 'acf-input-code-field', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/lib/codemirror.css" );
		}

		// CodeMirror modes
		wp_enqueue_script( 'acf-input-code-field-codemirror-css', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/mode/css/css.js" );
		wp_enqueue_script( 'acf-input-code-field-codemirror-js', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/mode/javascript/javascript.js" );
		wp_enqueue_script( 'acf-input-code-field-codemirror-xml', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/mode/xml/xml.js" );
		wp_enqueue_script( 'acf-input-code-field-codemirror-clike', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/mode/clike/clike.js" );
		wp_enqueue_script( 'acf-input-code-field-codemirror-php', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/mode/php/php.js" );
		wp_enqueue_script( 'acf-input-code-field-codemirror-htmlmixed', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/mode/htmlmixed/htmlmixed.js" );

		wp_enqueue_script( 'acf-input-code-field-codemirror-selection', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/addon/selection/mark-selection.js", array( 'wp-codemirror' ) );
		wp_enqueue_script( 'acf-input-code-field-codemirror-matchbrackets', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/addon/edit/matchbrackets.js", array( 'wp-codemirror' ) );
		wp_enqueue_script( 'acf-input-code-field-codemirror-autorefresh', "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/addon/display/autorefresh.js", array( 'wp-codemirror' ) );

		// register & include CSS
		wp_enqueue_style( 'acf-input-code-field-css', "{$dir}css/input.css" );

		// Register the script
		wp_register_script( 'acf-input-code-field-input', "{$dir}js/input.js" );

		// Localize the script with new data
		$localized_values = array(
			'plugins_url'        => plugins_url( 'acf-code-field' ),
			'codemirror_version' => ACFCF_CODEMIRROR_VERSION,
		);
		wp_localize_script( 'acf-input-code-field-input', 'acf_code_field_obj', $localized_values );

		// Enqueued script with localized data.
		wp_enqueue_script( 'acf-input-code-field-input', '', array( 'wp-codemirror' ) );

	}


	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_head() {



	}

	*/


	/*
	   *  input_form_data()
	   *
	   *  This function is called once on the 'input' page between the head and footer
	   *  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
	   *  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
	   *  seen on comments / user edit forms on the front end. This function will always be called, and includes
	   *  $args that related to the current screen such as $args['post_id']
	   *
	   *  @type	function
	   *  @date	6/03/2014
	   *  @since	5.0.0
	   *
	   *  @param	$args (array)
	   *  @return	n/a
	   */

	/*

	function input_form_data( $args ) {



	}

	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function load_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function update_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	/*

	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) {

			return $value;

		}


		// apply setting
		if( $field['font_size'] > 12 ) {

			// format the value
			// $value = 'something';

		}


		// return
		return $value;
	}

	*/


	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}
		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-FIELD_NAME'),
		}
		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function load_field( $field ) {

		return $field;

	}

	*/


	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function update_field( $field ) {

		return $field;

	}

	*/


	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/

	/*

	function delete_field( $field ) {



	}

	*/
}


// create field
new acf_code_field();
