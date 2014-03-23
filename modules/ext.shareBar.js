/* global mw, mediaWiki */

( function ( mw, $ ) {
    "use strict";

    $('.sidebar-btn, .wr-sharebar li > a').on( 'click', function( event ) {
        var url = $(this).attr('href');
        var height = $(this).data( 'height');
        var width = $(this).data( 'width');

        switch( $(this).data( 'open-as' ) ) {
            case 'modal': openModal( url, $(this), width, height );
                break;
            case 'window': openWindow( url, width, height, 'shareWindow' );
                break;
            case 'print': window.print();
                break;
        }

        event.preventDefault();

    });


    function openWindow( url, width, height, windowName ) {
        var strWindowFeatures = "menubar=no,toolbar=no,location=no,resizable=no,scrollbars=no,status=no,directories=no";
        /* Sanity check for screen size */
        if( width > window.screen.width ) { width = window.screen.width; }
        if( height > window.screen.height ) { height = window.screen.height; }

        var widthAndHeight = 'width=' + width + ',height=' + height;
        var left = (window.screen.width/2)-(width/2);
        var top = (window.screen.height/2)-(height/2);
        var position = 'left=' + left + ',top=' + top;
        strWindowFeatures += ',' + widthAndHeight + ',' + position;

        window.open( url, windowName, strWindowFeatures );
    }


    function openModal( url, $anchor, width, height ) {
        /* Sanity check for screen size */
        if( width > window.screen.width ) { width = window.screen.width; }
        if( height > window.screen.height ) { height = window.screen.height; }

        var $sharebarModal = $( $anchor.data( 'target' ) );
        var $frame = $sharebarModal.find('iframe');
        // Was the iframe already loaded? Do not reload
        //if( $frame.attr( 'src' ) === '' ) {
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
