/*global podcastplayerImageUploadText */
( function( $ ) {
	var fileFrame,
		$document = $( document );

	$document.on( 'click', '.podcast-player-widget-img-uploader', function( event ) {
		var _this = $( this );
		event.preventDefault();

		// Create the media frame.
		fileFrame = wp.media.frames.fileFrame = wp.media({
			title: podcastplayerImageUploadText.uploader_title,
			button: {
				text: podcastplayerImageUploadText.uploader_button_text
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		fileFrame.on( 'select', function() {

			// We set multiple to false so only get one image from the uploader
			var attachment  = fileFrame.state().get( 'selection' ).first().toJSON(),
				imgUrl      = attachment.url,
				imgId       = attachment.id,
				featuredImg = document.createElement( 'img' );

			featuredImg.src       = imgUrl;
			featuredImg.className = 'custom-widget-thumbnail';
			_this.html( featuredImg );
			_this.addClass( 'has-image' );
			_this.nextAll( '.podcast-player-widget-img-id' ).val( imgId ).trigger( 'change' );
			_this.nextAll( '.podcast-player-widget-img-instruct, .podcast-player-widget-img-remover' ).removeClass( 'podcast-player-hidden' );
		});

		// Finally, open the modal
		fileFrame.open();
	});

	$document.on( 'click', '.podcast-player-widget-img-remover', function( event ) {
		event.preventDefault();
		$( this ).prevAll( '.podcast-player-widget-img-uploader' ).html( podcastplayerImageUploadText.set_featured_img ).removeClass( 'has-image' );
		$( this ).prev( '.podcast-player-widget-img-instruct' ).addClass( 'podcast-player-hidden' );
		$( this ).next( '.podcast-player-widget-img-id' ).val( '' ).trigger( 'change' );
		$( this ).addClass( 'podcast-player-hidden' );
	});

	$document.on( 'click', '.pp-settings-toggle', function( event ) {
		var _this = $( this );
		event.preventDefault();
		_this.next( '.pp-settings-content' ).slideToggle('fast');
		_this.toggleClass( 'toggle-active' );
	});

	$(document).on( 'ready widget-added widget-updated', function(event, widget) {
		var params = { 
			change: function(e, ui) {
				$( e.target ).val( ui.color.toString() );
				$( e.target ).trigger('change'); // enable widget "Save" button
			},
		} 
		$('.pp-accent-color').not('[id*="__i__"]').wpColorPicker( params );
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-display-style', function() {
		var _this = $(this);
		var style = _this.val();
		var parent = _this.parent().parent();
		var wrap = parent.prevAll('.pp-settings-content');
		var hdefault = wrap.find('.header-default');
		var excerpt = wrap.find('.excerpt-length');
		var excerptSupport = ['lv1', 'gv1'];
		var thumbSupport = ['lv1', 'gv1'];
		var gridSupport = ['gv1'];
		var aspectRatio;

		if ( style ) {
			hdefault.hide();
		} else {
			hdefault.show();
		}

		if (excerptSupport.includes(style)) {
			excerpt.show();
		} else {
			excerpt.hide();
		}

		if (gridSupport.includes(style)) {
			parent.find('.grid-columns').show();
		} else {
			parent.find('.grid-columns').hide();
		}

		if (thumbSupport.includes(style)) {
			parent.find('.aspect-ratio').show();
			aspectRatio = parent.find('select.podcast-player-pp-aspect-ratio');
			if (aspectRatio.length && aspectRatio.val()) {
				parent.find('.crop-method').show();
			}
		} else {
			parent.find('.aspect-ratio').hide();
			parent.find('.crop-method').hide();
		}
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-aspect-ratio', function() {
		var _this = $(this);
		var parent = _this.parent();
		if (_this.val()) {
			parent.siblings('.crop-method').show();
		} else {
			parent.siblings('.crop-method').hide();
		}
	});

	$('#widgets-right').on('change', '.hide_header input[type="checkbox"]', function() {
		var _this = $(this);
		var parent = _this.parent();
		var sibs = parent.nextAll('.hide-cover-img, .hide-title, .hide-description, .hide-subscribe');
		if (_this.is(':checked')) {
			sibs.hide();
		} else {
			sibs.show();
		}
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-fetch-method', function() {
		var _this = $(this);
		var parent = _this.parent();
		var wrapper = parent.siblings('.pp-options-wrapper');
		var toggleLink = wrapper.find('.podcast-addinfo-toggle');
		var toggled = wrapper.find('.pp-settings-content');
		if ('feed' === _this.val()) {
			parent.siblings('.feed-url').show();
			parent.siblings('.post-type-fetch').hide();
			toggleLink.find('.is-premium-post').hide();
			toggleLink.find('.is-feed').show();
			toggled.find('.podcast-title').hide();
			parent.siblings('.pp-options-wrapper').show();
			toggled.find('.hide-content').show();
			parent.siblings('.single-audio-fetch').hide();
		} else if ( 'post' === _this.val() ) {
			parent.siblings('.feed-url').hide();
			parent.siblings('.post-type-fetch').show();
			toggleLink.find('.is-premium-post').show();
			toggleLink.find('.is-feed').hide();
			toggled.find('.podcast-title').show();
			parent.siblings('.pp-options-wrapper').show();
			toggled.find('.hide-content').hide();
			parent.siblings('.single-audio-fetch').hide();
		} else if ( 'link' === _this.val() ) {
			parent.siblings('.single-audio-fetch').show();
			parent.siblings('.feed-url').hide();
			parent.siblings('.post-type-fetch').hide();
			parent.siblings('.pp-options-wrapper').hide();
		}
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-post-type', function() {
		var _this = $(this);
		var postType  = _this.val();
		var parent = _this.parent();
		var taxonomy = parent.siblings('.pp-taxonomies').find('.podcast-player-pp-taxonomy');
		parent.siblings('.pp-terms-panel').hide();
		taxonomy.find( 'option' ).hide();
		taxonomy.find( '.' + postType ).show();
		taxonomy.find( '.always-visible' ).show();
		taxonomy.val('');
	});

	$('#widgets-right').on('change', 'select.podcast-player-pp-taxonomy', function() {
		var taxonomy = $(this);
		if ( taxonomy.val() ) {
			taxonomy.parent().next('.pp-terms-panel').show();
			taxonomy.parent().next('.pp-terms-panel').find( '.pp_terms-checklist li' ).hide();
			taxonomy.parent().next('.pp-terms-panel').find( '.pp_terms-checklist .' + taxonomy.val() ).show();
		} else {
			taxonomy.parent().next('.pp-terms-panel').hide();
		}
	});
} )( jQuery );
