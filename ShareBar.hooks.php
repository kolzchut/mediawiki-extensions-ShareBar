<?php

class ExtShareBarHooks {


    /**
     * ParserFirstCallInit hook
     * Add the parser function for displaying the sharebar
     *
     * @param Parser $parser
     * @return true
     */
    public static function onParserFirstCallInit( Parser &$parser ) {
        $parser->setFunctionHook( 'sharebar', array( 'ExtShareBar', 'shareBarFunctionHook' ) );
        return true;
    }


    /**
     * ResourceLoaderGetConfigVars hook
     * Make extension configuration variables available in javascript
     *
     * @param $vars
     * @return true
     */
    public static function onResourceLoaderGetConfigVars( &$vars ) {
        ExtShareBar::registerJsConfigVars( $vars );
        return true;
    }


    /**
     * BeforePageDisplay hook
     *
     * Add the modules to the page
     *
     * @param $out OutputPage output page
     * @param $skin Skin current skin
     *
     * @return true
     */
    public static function onBeforePageDisplay( OutputPage $out, $skin ) {
		// Add a single modal container for use by all modals
		$out->addHTML( ExtShareBar::makeModalContainer() );

		//Add modules:
        $out->addModuleStyles( 'ext.wr.ShareBar' );
        $out->addModules( 'ext.wr.ShareBar.js' );

        return true;
    }

    public static function onSkinHelenaSidebarButtons( &$skin, &$buttons ) {
        //$buttons .= ExtShareBar::makeModalButton( 'donate' );
        //$buttons .= ExtShareBar::makeModalButton( 'feedback' );

        return true;
    }

} 
