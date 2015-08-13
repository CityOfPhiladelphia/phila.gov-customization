//pretty much all of this came from https://gist.github.com/figureone/43661c51d992d89e56e1

( function ( $ ) {
  var clicked = false;

	$( document ).ready( function () {
		// Global: wpLink.

    /* When a link gets added from existing content, set clicked */
    $("#most-recent-results").contents().click(function(){
      clicked = true;
      return clicked;
    });

		// Extend the getAttrs() function to include the required link class.
		// getAttrs() returns { href: '', title: '', target: '' }, so we will
		// just add class: 'required' to that if the checkbox is checked.
		var core_getAttrs = wpLink.getAttrs;
		wpLink.getAttrs = function () {
		var attributes = core_getAttrs.apply( core_getAttrs );
    //if the user is adding existing content, don't add the class.
    attributes.class = (clicked) ? '' : 'external';
		return attributes;
		}

		// Extend the htmlUpdate() function to include the 'required' class
		// in the generated markup if the required link checkbox is set.
		var core_htmlUpdate = wpLink.htmlUpdate;
		wpLink.htmlUpdate = function () {
			// Extend: recreate out-of-scope variables from wplink.js.
			var inputs = {
				text: $( '#wp-link-text' ),
			};

			var attrs, text, html, begin, end, cursor, selection,
				textarea = wpLink.textarea;

			if ( ! textarea ) {
				return;
			}

			attrs = wpLink.getAttrs();
			text = inputs.text.val();

			// If there's no href, return.
			if ( ! attrs.href ) {
				return;
			}

			// Build HTML
			html = '<a href="' + attrs.href + '"';

			if ( attrs.target ) {
				html += ' target="' + attrs.target + '"';
			}

			// Extend: Add class to generated markup.
			if ( attrs.class ) {
				html += ' class="' + attrs.class + '"';
			}

			html += '>';

			// Insert HTML
			if ( document.selection && wpLink.range ) {
				// IE
				// Note: If no text is selected, IE will not place the cursor
				//       inside the closing tag.
				textarea.focus();
				wpLink.range.text = html + ( text || wpLink.range.text ) + '</a>';
				wpLink.range.moveToBookmark( wpLink.range.getBookmark() );
				wpLink.range.select();

				wpLink.range = null;
			} else if ( typeof textarea.selectionStart !== 'undefined' ) {
				// W3C
				begin = textarea.selectionStart;
				end = textarea.selectionEnd;
				selection = text || textarea.value.substring( begin, end );
				html = html + selection + '</a>';
				cursor = begin + html.length;

				// If no text is selected, place the cursor inside the closing tag.
				if ( begin === end && ! selection ) {
					cursor -= 4;
				}

				textarea.value = (
					textarea.value.substring( 0, begin ) +
					html +
					textarea.value.substring( end, textarea.value.length )
				);

				// Update cursor position
				textarea.selectionStart = textarea.selectionEnd = cursor;
			}

			wpLink.close();
			textarea.focus();
		}

		// Extend refresh() to check/uncheck required link checkbox if it's set on the selected link.
		var core_refresh = wpLink.refresh;
		wpLink.refresh = function () {
			// Run original function.
			core_refresh.apply( core_refresh );
      //reset clicked
      clicked = false;

			// Extend: recreate out-of-scope variables from wplink.js.
			var editor = tinymce.get( wpActiveEditor ),
				selectedNode = editor.selection.getNode(),
				linkNode = editor.dom.getParent( selectedNode, 'a[href]' );
		}
	});
})( jQuery );
