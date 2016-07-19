(function( $ ) {

	function initialize_code_field( $el ) {

		if ( $el.parents( ".acf-clone" ).length > 0 ) {
			return;
		}

		var $textarea = $el.find( '.acf-input>textarea' );

		//load needed CSS if doesn't already exist
		if ( !$( "link[href='" + acf_code_field_obj.plugins_url + "/js/codemirror-5.13/theme/" + $textarea.attr( "theme" ) + ".css']" ).length ) {
			$( '<link href="'+ acf_code_field_obj.plugins_url +'/js/codemirror-5.13/theme/' + $textarea.attr( "theme" ) + '.css" rel="stylesheet">' ).appendTo( "head" );
		}

		var editor = CodeMirror.fromTextArea( $textarea[ 0 ], {
			lineNumbers: true,
			fixedGutter: false,
			mode: $textarea.attr( "mode" ),
			theme: $textarea.attr( "theme" ),
			extraKeys: { "Ctrl-Space": "autocomplete" },
			value: document.documentElement.innerHTML

		} );

		editor.refresh();
	}

	if ( typeof acf.add_action !== 'undefined' ) {

		/*
		 *  ready append (ACF5)
		 *
		 *  These are 2 events which are fired during the page load
		 *  ready = on page load similar to $(document).ready()
		 *  append = on new DOM elements appended via repeater field
		 *
		 *  @type	event
		 *  @date	20/07/13
		 *
		 *  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		 *  @return	n/a
		 */

		acf.add_action( 'ready', function( $el ) {
			$el.find( ".acf-field-acf-code-field" ).each( function( index, field ) {
				initialize_code_field( $( field ) );
			} );

		} );
		acf.add_action( 'append_field', function( $el ) {
			if ( $el.attr( 'data-type' ) == "acf_code_field" ) {
				initialize_code_field( $el );
			}
		} );

	}

})( jQuery );
