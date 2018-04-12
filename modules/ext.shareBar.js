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

		init: function() {
			this.settings = mw.config.get( 'egShareBar' );
			this.modalTemplate = mw.template.get( 'ext.wr.ShareBar.js', 'modal.mustache' );

			this.attachClickHandlers();
		},

		attachClickHandlers: function() {
			var selectors = [
				'.wr-share-link',
				'.kz-nav-donation > a',
				'.kz-footer-donation > a',
				'.kz-nav-feedback > a',
				'.kz-footer-feedback > a'
			];

			$('body').on( 'click', selectors.join(','), function( event ) {
				var service = $( this ).data( 'service') || null;
				var action = $( this ).data( 'action' ) || null;

				if( $( this ).parent().hasClass( 'kz-nav-donation' ) || $( this ).parent().hasClass( 'kz-footer-donation' ) ) {
					service = 'donate';
					action = 'modal';
				}
				if( $( this ).parent().hasClass( 'kz-nav-feedback' ) || $( this ).parent().hasClass( 'kz-footer-feedback' ) ) {
					service = 'feedback';
					action = 'modal';
				}

				var props = wrShareBar.settings[service];
				if ( service === null || props === 'undefined' || action === null ) {
					return;
				}

				var url = $( this ).attr( 'href' );
				if( ( service === 'donate' || service === 'feedback') && props.url !== undefined ) {
					url = props.url;
				}

				/* Sanity check for screen size */
				var width = Math.min( props.width || 800, screen.width );
				var height = Math.min( props.height || 700, screen.height );
				//mw.log( width + 'x' + height);

				switch( action ) {
					case 'print':
						window.print();
						break;
					case 'window':
						wrShareBar.openWindow(url, width, height, 'shareWindow');
						break;
					case 'modal':
						wrShareBar.openModal(url, width, height);
						break;
				}


				event.preventDefault();
			});

			$( '.wr-sharebar-getlink' ).on('show.bs.dropdown', function( event ) {
				var $target = $( event.currentTarget );
				var $btn = $target.find('.btn' );

				$target.find( '.dropdown-menu' ).on( 'click', function( e ) { e.stopPropagation(); });
				mw.loader.using( 'clipboard.js', function() {
					var clipboard = new ClipboardJS( $btn[0] );
					clipboard.on('success', function(e) {
						$btn.text( mw.message( 'ext-sharebar-getlink-success' ).text() );
						e.clearSelection();
					});

					clipboard.on('error', function(e) {
						$btn.text( mw.message( 'ext-sharebar-getlink-fail' ).text() );
					});
				});
				var $input = $target.find( 'input' );
				var textLength = $input.val().length + 'ch';
				$input.css( 'min-width',  textLength );
			});
		},


		openWindow: function( url, width, height, windowName ) {
			var widthAndHeight = 'width=' + width + ',height=' + height;
			// screen.left determines location in multi-monitor setup, supposedly
			var left = (screen.width/2)-(width/2) + screen.left;
			var top = (screen.height/2)-(height/2);
			var position = 'left=' + left + ',top=' + top;
			var strWindowFeatures = wrShareBar.basicWindowFeatures + ',' + widthAndHeight + ',' + position;

			window.open( url, windowName, strWindowFeatures );
		},

		openModal: function( url, width, height ) {
			var templateData = {
				modalTitle: mw.config.get( 'wgSiteName' ),
				iframeSrc: url,
				iframeHeight: height - 60,
				iframeWidth: '100%',
				modalWidth: width + 'px'
			};

			// Create modal if it doesn't exist, otherwise reuse it:
			if( !this.$activeModal ) {
				// Select .first() to fix a bug with an extra TextNode ("\n"), which
				// caused Bootstrap's modal to behave wierdly
				this.$activeModal = this.modalTemplate.render( templateData ).first();
				this.$activeModal.modal({
					backdrop: 'static',
					keyboard: false
				}).on('hidden.bs.modal', function () {
					// Remove iframe source to prevent flash of previous content on next load
					mw.wrShareBar.$activeModal.find( 'iframe' ).attr({
						src: ''
					});
				});
			} else {
				this.$activeModal.find('iframe').attr({
					src: url,
					height: height - 60,
					width: '100%'
				});
				this.$activeModal.find( '.modal-dialog' ).css({
					// height: height,
					width: width
				});
				this.$activeModal.modal( 'show' );
			}

		},

		closeModal: function() {
			if( mw.wrShareBar.$activeModal ) {
				mw.wrShareBar.$activeModal.modal( 'hide' );
			}
		}


	};

	wrShareBar.init();

	window.closeActiveModal = mw.wrShareBar.closeModal;
	// b/c for forms
	window.closeCrDialog = mw.wrShareBar.closeModal;


}( mediaWiki, jQuery ) );



