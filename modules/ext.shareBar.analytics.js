( function ( mw, $ ) {
	'use strict';

	mw.wrShareBar.analytics = {
		container: '.wr-sharebar',
		init: function() {
			if( mw.loader.getState( 'ext.googleUniversalAnalytics.utils' ) === null ) {
				return;
			}
			mw.loader.using( 'ext.googleUniversalAnalytics.utils' ).then( function() {
				$( mw.wrShareBar.analytics.container ).on( 'click', '.wr-share-link', function( event ) {
					var target = (event.delegateTarget.id || 'unknown').replace( 'sharebar-', '' );
					mw.googleAnalytics.utils.recordSocialInteraction( {
						socialNetwork: $( this ).data( 'service' ),
						socialAction: 'sharebar',
						socialTarget: target
					});
				} );
			});
		}
	};

	mw.wrShareBar.analytics.init();

}( mediaWiki, jQuery ) );

