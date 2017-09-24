/*
 * ZenBase - Javascript
 *
 * Copyright (c) 2008 Thomas Weustenfeld (www.thomas-weustenfeld.de)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 */

(function(){ /* TODO? */ });

var zenBase =
{
	init : function( view )
	{
		this.switchTab( view );
		this.fileUpload.init(); // nur auf uploadseite
	},

	quickEdit : function( target )
	{
		this.switchTab( 'edit' );

		var field = $( target );
		if( field ) field.focus();
	},

	newSearch : function()
	{
		$( 'searchfield' ).value = "";
		$( 'searchfield' ).setStyle( { color : 'black' } );
	},

	showRev : function( id )
	{
		var diffpre = $( "rev_" + id );
		var difflnk = $( "rln_" + id );

		if( diffpre && difflnk ) {
			diffpre.setStyle( { display : 'block' }  );
			difflnk.setStyle( { display : 'none' }  );
		}

		return false;
	},

	registerSubmit : function( sender ) {}, 
	loginSubmit    : function( sender ) {},
	pagenameSubmit : function( sender ) {},

	pageSubmit : function( sender )
	{
		// alert( sender );
	},

	/******* Tabs *********/

	switchTab : function( id )
	{
		this._hideTabs( 'contentContainer' );

		$( id ).removeClassName( 'inactive' );

		return false;
	},

	_hideTabs : function( container )
	{
		var children = $( container ).childElements();

		children.each( function( child ) {
			if( child.hasClassName( 'tab' ) ) {
				child.addClassName( 'inactive' );
			}
		});
	},

	setThumbnailMode : function( mode )
	{
		var container = $( 'imagelist' );

		container.removeClassName( 'grid' );
		container.removeClassName( 'list' );

		container.addClassName( mode );

		return false;
	},

	/***** file upload ******/

	fileUpload :
	{
		init : function()
		{
			if( $('file_upload_form') == null )
				return;

			$('file_upload_form').onsubmit = function()
			{
				//'upload_target' is the name of the iframe
				$( 'file_upload_form').target = "upload_target"; 
				$( 'upload_target' ).onload = function() { zenBase.fileUpload.uploadDone(); }
				//This function should be called when the iframe has compleated loading
				// That will happen when the file is completely uploaded and the server has returned the data we need.
				// TODO: IE
			}
		},

		uploadDone : function()
		{
			//Function will be called when iframe is loaded
			var ret = frames['upload_target'].document.getElementsByTagName("body")[0].innerHTML;
			alert(ret);
			//Parse JSON // Read the below explanations before passing judgment on me
			var data = ret.evalJSON( true ); // eval("("+ret+")");

			if(data.success)
			{
				//This part happens when the image gets uploaded.
				$("image_details").innerHTML = $("image_details").innerHTML
					+ "<div class='thumbnail'>"
					+ "<img src='/image.php?path=wiki/images/" + data.file_name + "' />"
					+ "<br />Size: " + data.size + " KB"
					+ "</div>";

				$("file").value = ""; 
			}
			else if(data.failure)
			{
				//Upload failed - show user the reason.
				alert("Upload Failed: " + data.failure);
			}
			else
			{
				alert( "unknown" );
			}
		}
	}
}
