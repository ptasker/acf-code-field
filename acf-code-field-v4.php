<?php

/**
 *
 *
 * Class acf_code_field_v4
 *
 * For 'FREE' version of ACF
 *
 */
class acf_code_field_v4 extends acf_field
{

    // vars
    var $settings, // will hold info such as dir / path
        $defaults; // will hold default field options

    /*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	21/03/2016
	*/

    function __construct()
    {
        // vars
        $this->name     = 'acf_code_field';
        $this->label    = __( 'ACF Code Field' );
        $this->category = __( "Code Tools", 'acf' ); // Basic, Content, Choice, etc
        $this->defaults = array(
            'default_value' => '',
            'formatting'    => 'br',
            'maxlength'     => '',
            'placeholder'   => '',
            'rows'          => '',
            'mode'          => 'htmlmixed',
            'theme'         => 'monokai',
        );

        // do not delete!
        parent::__construct();


        // settings
        $this->settings = array(
            'path'    => apply_filters( 'acf/helpers/get_path', __FILE__ ),
            'dir'     => apply_filters( 'acf/helpers/get_dir', __FILE__ ),
            'version' => '1.0.0',
        );
    }


    /*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	21/03/2016
	*
	*  @param	$field	- an array holding all the field's data
	*/

    function create_options($field)
    {
        // vars
        $key = $field['name'];

        ?>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( "Default Value", 'acf' ); ?></label>

                <p><?php _e( "Appears when creating a new post", 'acf' ) ?></p>
            </td>
            <td>
                <?php
                do_action( 'acf/create_field', array(
                    'type'  => 'textarea',
                    'name'  => 'fields[' . $key . '][default_value]',
                    'value' => $field['default_value'],
                ) );
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( "Placeholder Text", 'acf' ); ?></label>

                <p><?php _e( "Appears within the input", 'acf' ) ?></p>
            </td>
            <td>
                <?php
                do_action( 'acf/create_field', array(
                    'type'  => 'text',
                    'name'  => 'fields[' . $key . '][placeholder]',
                    'value' => $field['placeholder'],
                ) );
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( "Editor mode", 'acf' ); ?></label>
            </td>
            <td>
                <?php
                do_action( 'acf/create_field', array(
                    'type'    => 'select',
                    'name'    => 'fields[' . $key . '][mode]',
                    'value'   => $field['mode'],
                    'choices' => array(
                        'htmlmixed'               => __( "HTML Mixed", 'acf' ),
                        'javascript'              => __( "JavaScript", 'acf' ),
                        'text/html'               => __( "HTML", 'acf' ),
                        'css'                     => __( "CSS", 'acf' ),
                        'application/x-httpd-php' => __( "PHP", 'acf' ),
                    ),
                    'layout'  => 'horizontal',
                ) );
                ?>
            </td>
        </tr>

        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( "Theme", 'acf' ); ?></label>
            </td>
            <td>
                <?php
                $util = new ACF_Code_Field_Util();
                do_action( 'acf/create_field', array(
                    'type'    => 'select',
                    'name'    => 'fields[' . $key . '][theme]',
                    'value'   => $field['theme'],
                    'choices' => $util->get_codemirror_themes(),
                    'layout'  => 'horizontal',
                ) );
                ?>
            </td>
        </tr>

        <?php
    }


    /*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	21/03/2016
	*/

    function create_field($field)
    {
        // vars
        $o = array(
            'id',
            'class',
            'name',
            'placeholder',
            'rows',
            'mode',
            'theme',
        );


        $e = '';

        $field_id  = $field['id'];
        $safe_slug = str_replace( "-", "_", $field_id );


        $e .= '<textarea';

        foreach ($o as $k) {
            $e .= ' ' . $k . '="' . esc_attr( $field[ $k ] ) . '"';
        }

        $e .= '>';
        $e .= esc_textarea( $field['value'] );
        $e .= '</textarea>';

        // return
        echo $e;


        $dir = trailingslashit( plugin_dir_url( __FILE__ ) );

        wp_enqueue_style( 'codemirror-curr-style-' . $field['theme'], "{$dir}js/" . ACFCF_CODEMIRROR_VERSION . "/theme/{$field['theme']}.css" );

        ?>

        <script>
            var code_field_<?php echo $safe_slug; ?> = document.getElementById( '<?php echo $field['id']; ?>' );
            var editor = CodeMirror.fromTextArea( code_field_<?php echo $safe_slug; ?>, {
                lineNumbers: true,
                mode: '<?php echo esc_js( $field['mode'] ); ?>',
                theme: '<?php echo $field['theme']; ?>',
                extraKeys: { "Ctrl-Space": "autocomplete" },
                value: document.documentElement.innerHTML
            } );
        </script>

        <?php
    }


    /*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	21/03/2016
	*/

    function input_admin_enqueue_scripts()
    {

        $dir = trailingslashit( plugin_dir_url( __FILE__ ) );

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
	*  Use this action to add CSS and JavaScript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	21/03/2016
	*/

    function input_admin_head()
    {
        // Note: This function can be removed if not used
    }


    /*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	21/03/2016
	*/

    function field_group_admin_enqueue_scripts()
    {
        // Note: This function can be removed if not used
    }


    /*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	21/03/2016
	*/

    function field_group_admin_head()
    {
        // Note: This function can be removed if not used
    }


    /*
	*  load_value()
	*
		*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	21/03/2016
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in the database
	*/

    function load_value($value, $post_id, $field)
    {
        // Note: This function can be removed if not used
        return $value;
    }


    /*
	*  update_value()
	*
	*  This filter is applied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	21/03/2016
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/

    function update_value($value, $post_id, $field)
    {
        // Note: This function can be removed if not used
        return $value;
    }


    /*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	21/03/2016
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

    function format_value($value, $post_id, $field)
    {
        // defaults?
        /*
		$field = array_merge($this->defaults, $field);
		*/

        // perhaps use $field['preview_size'] to alter the $value?


        // Note: This function can be removed if not used
        return $value;
    }


    /*
	*  format_value_for_api()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	21/03/2016
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

    function format_value_for_api($value, $post_id, $field)
    {
        // defaults?
        /*
		$field = array_merge($this->defaults, $field);
		*/

        // perhaps use $field['preview_size'] to alter the $value?


        // Note: This function can be removed if not used
        return $value;
    }


    /*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	21/03/2016
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/

    function load_field($field)
    {
        // Note: This function can be removed if not used
        return $field;
    }


    /*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	21/03/2016
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

    function update_field($field, $post_id)
    {
        // Note: This function can be removed if not used
        return $field;
    }
}


// create field
new acf_code_field_v4();

?>
