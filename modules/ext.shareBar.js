/**
 * Utilities for a sharebar, mainly to open windows and modals
 * (c) 2014-2015 Kol-Zchut Ltd. & Dror S.
 * GPLv3
 *
 */

( function ( mw, $ ) {
	'use strict';
	var wrShareBar;

	wrShareBar = mw.wrShareBar = {
		settings: {},
		$activeModal: null,
		modalTemplate: null,
		/* static */ basicWindowFeatures: 'menubar=no,toolbar=no,location=no,resizable=no,scrollbars=no,status=no,directories=no',

		init: function () {
			this.settings = mw.config.get( 'egShareBar' );
			this.modalTemplate = mw.template.get( 'ext.wr.ShareBar.js', 'modal.mustache' );

			this.attachClickHandlers();
		},

		attachClickHandlers: function () {
			var selectors = [
				'.wr-share-link',
				'.kz-nav-feedback > a',
				'.kz-footer-feedback > a'
			];

			$( selectors.join() ).on( 'click', function ( event ) {
				var service = $( this ).data( 'service' ) || null,
					action = $( this ).data( 'action' ) || null,
					props,
					url,
					width,
					height;

				// eslint-disable-next-line no-jquery/no-class-state
				if ( $( this ).parent().hasClass( 'kz-nav-feedback' ) || $( this ).parent().hasClass( 'kz-footer-feedback' ) ) {
					service = 'feedback';
					action = 'modal';
				}

				props = wrShareBar.settings[ service ];
				if ( service === null || props === 'undefined' || action === null ) {
					return;
				}

				url = $( this ).attr( 'href' );
				if ( service === 'feedback' && props.url !== undefined ) {
					url = props.url;
				}

				/* Sanity check for screen size */
				width = Math.min( props.width || 800, screen.width );
				height = Math.min( props.height || 700, screen.height );
				// mw.log( width + 'x' + height);

				switch ( action ) {
					case 'print':
						window.print();
						break;
					case 'window':
						wrShareBar.openWindow( url, width, height, 'shareWindow' );
						break;
					case 'modal':
						wrShareBar.openModal( url, width, height );
						break;
				}

				event.preventDefault();
			} );

			// eslint-disable-next-line no-jquery/no-global-selector
			$( '.wr-sharebar-getlink' ).on( 'show.bs.dropdown', function ( event ) {
				var $target = $( event.currentTarget ),
					$btn = $target.find( '.btn' ),
					originalBtnText = $btn.html(),
					$input,
					textLength;

				$target.find( '.dropdown-menu' ).on( 'click', function ( e ) {
					e.stopPropagation();
				} );
				mw.loader.using( 'clipboard.js', function () {
					var clipboard = new ClipboardJS( $btn[ 0 ] );
					clipboard.on( 'success', function ( e ) {
						$btn.text( mw.message( 'ext-sharebar-getlink-success' ).text() );
						// Reset the button the text the second it's hidden
						$target.find( '.dropdown' ).one( 'hidden.bs.dropdown', function () {
							$btn.html( originalBtnText );
						} );
						e.clearSelection();
					} );

					clipboard.on( 'error', function () {
						$btn.text( mw.message( 'ext-sharebar-getlink-fail' ).text() );
					} );
				} );
				$input = $target.find( 'input' );
				textLength = $input.val().length + 'ch';
				$input.css( 'min-width', textLength );
			} );
		},

		openWindow: function ( url, width, height, windowName ) {
			var widthAndHeight = 'width=' + width + ',height=' + height,
				// screen.left determines location in multi-monitor setup, supposedly
				left = ( screen.width / 2 ) - ( width / 2 ) + screen.left,
				top = ( screen.height / 2 ) - ( height / 2 ),
				position = 'left=' + left + ',top=' + top,
				strWindowFeatures = wrShareBar.basicWindowFeatures + ',' + widthAndHeight + ',' + position;

			window.open( url, windowName, strWindowFeatures );
		},

		openModal: function ( url, width, height, options ) {
			var templateData;

			options = options || [];
			options.title = options.title || mw.config.get( 'wgSiteName' );

			templateData = {
				modalTitle: options.title,
				iframeSrc: url,
				iframeHeight: height - 60,
				iframeWidth: '100%',
				modalWidth: width + 'px'
			};

			// Create modal if it doesn't exist, otherwise reuse it:
			if ( !this.$activeModal ) {
				// Select .first() to fix a bug with an extra TextNode ("\n"), which
				// caused Bootstrap's modal to behave wierdly
				this.$activeModal = this.modalTemplate.render( templateData ).first();
				this.$activeModal.modal( {
					backdrop: 'static',
					keyboard: false
				} ).on( 'hidden.bs.modal', function () {
					// Remove iframe source to prevent flash of previous content on next load
					mw.wrShareBar.$activeModal.find( 'iframe' ).attr( {
						src: ''
					} );
				} );
			} else {
				this.$activeModal.find( 'iframe' ).attr( {
					src: url,
					height: height - 60,
					width: '100%'
				} );
				this.$activeModal.find( '.modal-dialog' ).css( {
					// height: height,
					width: width
				} );
				this.$activeModal.find( '.modal-title' ).text( options.title );
				this.$activeModal.modal( 'show' );
			}

		},

		closeModal: function () {
			if ( mw.wrShareBar.$activeModal ) {
				mw.wrShareBar.$activeModal.modal( 'hide' );
			}
		}

	};

	wrShareBar.init();

	window.closeActiveModal = mw.wrShareBar.closeModal;
	// b/c for forms
	window.closeCrDialog = mw.wrShareBar.closeModal;

}( mediaWiki, jQuery ) );
