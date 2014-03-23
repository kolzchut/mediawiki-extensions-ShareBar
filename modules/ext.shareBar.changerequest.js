$( document ).ready( function() {
    "use strict";
    var egShareBar = mw.config.get( 'egShareBar' );
    var formTitle = mw.msg( 'ext-sharebar-cr-form-title' );
    if( egShareBar.changerequest != null && egShareBar.changerequest.url ) {
        $( '.sharebar-cr-btn').click( function() {
            var $dialog = $('<div></div>')
                .html( '<iframe style="border: 0; " src="' + egShareBar.changerequest.url +
                    '" width="100%" height="99%">' + mw.msg( 'ext-sharebar-loading' ) + '</iframe>' )
                .dialog({
                    modal: true,
                    dialogClass: 'sharebar-dialog',
                    draggable: false,
                    resizeable: false,
                    height: $(window).height() > 500 ? ( 0.9 * $(window).height() ) : $(window).height(),
                    width: $(window).width() > 760 ? 700 : $(window).width(),
                    zIndex: 1100,
                    title: formTitle
                });
            return false;
        });
    }
} );


window.closeCrDialog = function() { //Kol-Zchut specific
    $( '.ui-dialog-titlebar-close').trigger( 'click' );
}
