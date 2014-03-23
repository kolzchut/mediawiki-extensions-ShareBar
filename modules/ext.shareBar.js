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
        var widthAndHeight = 'width=' + width + ',height=' + height;
        var left = (window.screen.width/2)-(width/2);
        var top = (window.screen.height/2)-(height/2);
        var position = 'left=' + left + ',top=' + top;
        strWindowFeatures += ',' + widthAndHeight + ',' + position;

        window.open( url, windowName, strWindowFeatures );
    }

    function openModal( url, $anchor, width, height ) {
        var $sharebarModal = $( $anchor.data( 'target' ) );
        var $frame = $sharebarModal.find('iframe');
        // Was the iframe already loaded? Do not reload
        if( $frame.attr( 'src' ) === '' ) {
            $frame.attr({
                src: url,
                height: height-60,
                width: '100%'
            });
        }
        $sharebarModal.modal({
            backdrop: 'static',
            keyboard: false
        }).find( '.modal-content').css({
            width: width,
            height: height
        });
    }

}( mediaWiki, jQuery ) );


    $( document ).ready( function() {
        "use strict";
    });




function emailShare() {
    "use strict";
    var wgPageName = mw.config.get( 'wgPageName', '' );
    var wgFullPageTitle = wgPageName.replace(/_/g, ' ');
    var wgServer = mw.config.get( 'wgServer' );
    var wgPageUrl = wgPageName ? ( wgServer + mw.util.wikiGetlink( wgPageName ) ) : '';
    var wgUserName = mw.config.get( 'wgUserName' ); if( wgUserName === null ) { wgUserName = ''; }
    var target_url = '/forms/mailArticle/?page=' +
        encodeURIComponent( wgFullPageTitle ) + '&pageUrl=' + encodeURIComponent( wgPageUrl ) + '&senderEmail=' +
        encodeURIComponent( mw.config.get( 'wgUserEmail', '' ) ) + '&senderName=' + encodeURIComponent( wgUserName );
}

window.closeActiveModal = function() {
    "use strict";
    $( '.modal.in').modal('hide');
}


/*
 $( '.sharebar-btn').each( function() {

            $(this).click( function() {
                var width = $(this).data( 'dialog-width') || 700;
                var height = $(this).data( 'dialog-height') || 600;
                var src = $(this).prop('href');

                $("#sharebar-modal iframe").attr({
                    'src': src,
                    'height': height,
                    'width': width
                });

                return false;
            });
 */


/*
 if( egShareBar.feedback && egShareBar.feedback.url ) {
 $( '.sharebar-feedback-btn').click( function() {
 var $dialog = $('<div></div>')
 .html( '<iframe style="border: 0; " src="' + egShareBar.feedback.url +
 '" width="100%" height="99%">' + mw.msg( 'ext-sharebar-loading' ) + '</iframe>' )
 .dialog({
 modal: true,
 dialogClass: 'sharebar-dialog',
 draggable: false,
 resizeable: false,
 height: ( 0.9 * $(window).height() ),
 width: $(window).width() > 760 ? 760 : $(window).width(),
 maxWidth: 760,
 zIndex: 1100,
 title: formTitle
 });
 return false;
 });
 }
 } );

 */
