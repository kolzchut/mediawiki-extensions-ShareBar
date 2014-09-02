/* global mw, mediaWiki */

( function ( mw, $ ) {
    "use strict";
	var egShareBar = mw.config.get( 'egShareBar' );
	/* static */ var basicWindowFeatures = "menubar=no,toolbar=no,location=no,resizable=no,scrollbars=no,status=no,directories=no";
	var $sharebarModal = $( '#wr-sharebar-modal' );
	var $frame = $sharebarModal.find('iframe');


	$('.sidebar-btn, .wr-share-link,' +
		'.kz-nav-donation > a, .kz-footer-donation > a,' +
		'.kz-nav-feedback > a, .kz-footer-feedback > a')
		.on( 'click', function( event ) {
			var shareType = $(this).data( 'share-type') || null;
			if( $(this).parent().hasClass( 'kz-nav-donation' ) || $(this).parent().hasClass( 'kz-footer-donation' ) ) {
				shareType = 'donate';
			}
			if( $(this).parent().hasClass( 'kz-nav-feedback' ) || $(this).parent().hasClass( 'kz-footer-feedback' ) ) {
				shareType = 'feedback';
			}

			if ( shareType === null ) { return; }

			var props = egShareBar[shareType];
			var url = $.inArray(shareType, ['donate', 'feedback'] ) !== -1 && props.url !== undefined ?
				props.url : $(this).attr('href');


			/* Sanity check for screen size */
			var width = Math.min( props.width || 800, screen.width );
			var height = Math.min( props.height || 700, screen.height );
			//mw.log( width + 'x' + height);
			switch( props.openAs ) {
				case 'window': openWindow( url, width, height, 'shareWindow' );
					break;
				case 'print': window.print();
					break;
				default: case 'modal': openModal( url, $(this), width, height );
			}

			event.preventDefault();
		}
	);


    function openWindow( url, width, height, windowName ) {
        var widthAndHeight = 'width=' + width + ',height=' + height;
		// screen.left determines location in multi-monitor setup, supposedly
        var left = (screen.width/2)-(width/2) + screen.left;
        var top = (screen.height/2)-(height/2);
        var position = 'left=' + left + ',top=' + top;
        var strWindowFeatures = basicWindowFeatures + ',' + widthAndHeight + ',' + position;

        window.open( url, windowName, strWindowFeatures );
    }

    function openModal( url, $anchor, width, height ) {
            $frame.attr({
                src: url,
                height: height-60,
                width: '100%'
            });
        //}
        $sharebarModal.modal({
            backdrop: 'static',
            keyboard: false
        }).find( '.modal-dialog').css({
            //height: height,
            width: width
        });

        $sharebarModal.on( 'hide.bs.modal', function() {
            // Remove iframe source to prevent flash of previous content on next load
            $frame.attr({
                src: ''
            });
        });

    }

}( mediaWiki, jQuery ) );


    $( document ).ready( function() {
        "use strict";
    });

window.closeActiveModal = function() {
    "use strict";
    $( '.modal.in').modal('hide');
};
// b/c for forms
window.closeCrDialog = window.closeActiveModal;
