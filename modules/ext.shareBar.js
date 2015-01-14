/**
 * Utilities for a sharebar, mainly to open windows and modals
 * (c) 2014 Kol-Zchut Ltd. & Dror S.
 * GPLv2 or later
 *
 */

/* global mediaWiki */
( function ( mw, $ ) {
    "use strict";
	var wrShareBar;

	wrShareBar = mw.wrShareBar = {
		settings: {},
		$sharebarModal: null,
		$frame: null,
		/* static */ basicWindowFeatures: "menubar=no,toolbar=no,location=no,resizable=no,scrollbars=no,status=no,directories=no",

		init: function() {
			this.settings = mw.config.get( 'egShareBar' );
			this.$sharebarModal = $( '#wr-sharebar-modal' );
			this.$frame = wrShareBar.$sharebarModal.find('iframe');

			wrShareBar.attachClickHandlers();
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

					if ( shareType === null ) { return; }

					var props = wrShareBar.settings[shareType];
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
						wrShareBar.openModal(url, $(this), width, height);
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

		openModal: function( url, $anchor, width, height ) {
			wrShareBar.$frame.attr({
				src: url,
				height: height - 60,
				width: '100%'
			});
			wrShareBar.$sharebarModal.modal({
				backdrop: 'static',
				keyboard: false
			}).find('.modal-dialog').css({
				//height: height,
				width: width
			});

			wrShareBar.$sharebarModal.on('hide.bs.modal', function () {
				// Remove iframe source to prevent flash of previous content on next load
				wrShareBar.$frame.attr({
					src: ''
				});
			});
		}


	};


	wrShareBar.init();


	window.closeActiveModal = function() {
		$( '.modal.in').modal('hide');
	};

	// b/c for forms
	window.closeCrDialog = window.closeActiveModal;

}( mediaWiki, jQuery ) );



