/**
 * Utilities for a sharebar, mainly to open windows and modals
 * (c) 2014-2015 Kol-Zchut Ltd. & Dror S.
 * GPLv3
 *
 */

( function ( mw, $ ) {
    "use strict";
	var wrShareBar;

	wrShareBar = mw.wrShareBar = {
		settings: {},
		$activeModal: null,
		modalTemplate: null,
		/* static */ basicWindowFeatures: "menubar=no,toolbar=no,location=no,resizable=no,scrollbars=no,status=no,directories=no",

		init: function() {
			this.settings = mw.config.get( 'egShareBar' );
			this.modalTemplate = mw.template.get( 'ext.wr.ShareBar.js', 'modal.mustache' );

			this.attachClickHandlers();
		},

		attachClickHandlers: function() {
			$('body').on( 'click',
				'.sidebar-btn, .wr-share-link,' +
				'.kz-nav-donation > a, .kz-footer-donation > a,' +
				'.kz-nav-feedback > a, .kz-footer-feedback > a',
				function( event ) {
					var shareType = $(this).data( 'share-type') || null;
					if( $(this).parent().hasClass( 'kz-nav-donation' ) || $(this).parent().hasClass( 'kz-footer-donation' ) ) {
						shareType = 'donate';
					}
					if( $(this).parent().hasClass( 'kz-nav-feedback' ) || $(this).parent().hasClass( 'kz-footer-feedback' ) ) {
						shareType = 'feedback';
					}

					var props = wrShareBar.settings[shareType];
					if ( shareType === null || props === 'undefined' || props.openAs === 'no' ) {
						return;
					}

					var url = $.inArray(shareType, ['donate', 'feedback'] ) !== -1 && props.url !== undefined ?
						props.url : $(this).attr('href');


					/* Sanity check for screen size */
					var width = Math.min( props.width || 800, screen.width );
					var height = Math.min( props.height || 700, screen.height );
					//mw.log( width + 'x' + height);
					if (props.openAs === 'window') {
						wrShareBar.openWindow(url, width, height, 'shareWindow');
					} else if (props.openAs === 'print') {
						window.print();
					} else {
						wrShareBar.openModal(url, width, height);
					}

					event.preventDefault();
				}
			);

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
					mw.wrShareBar.$activeModal.find('iframe').attr({
						src: ''
					});
				});
			} else {
				this.$activeModal.find('iframe').attr({
					src: url,
					height: height - 60,
					width: '100%'
				});
				this.$activeModal.find('.modal-dialog').css({
					//height: height,
					width: width
				});
				this.$activeModal.modal('show');
			}

		},

		closeModal: function() {
			if( mw.wrShareBar.$activeModal ) {
				mw.wrShareBar.$activeModal.modal('hide');
			}
		}


	};

	wrShareBar.init();

	window.closeActiveModal = mw.wrShareBar.closeModal();
	// b/c for forms
	window.closeCrDialog = mw.wrShareBar.closeModal();


}( mediaWiki, jQuery ) );



