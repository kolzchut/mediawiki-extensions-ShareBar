<?php
/**
 * Created by PhpStorm.
 * User: dror
 * Date: 3/20/14
 * Time: 12:38 AM
 */

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
        global $egShareBarEnabledServices, $egShareBarServices;
        //ExtShareBar::registerJsConfigVars( &vars );

        foreach( $egShareBarEnabledServices as $service ) {
            $properties = $egShareBarServices[$service];
            if( is_array( $properties ) && !empty( $properties['link'] ) ) {
                //$url = Skin::makeInternalOrExternalUrl( $properties['link'] );
                $url = $properties['link'];
                $vars['egShareBar'][$service]['url'] = $url;
            }
        }

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
        global $egShareBarEnabledServices;
        /*
        foreach( $egShareBarEnabledServices as $service ) {
            $out->addModules( 'ext.wr.ShareBar.' . $service );
        }
        */
        $out->addModuleStyles( 'ext.wr.ShareBar' );
        $out->addModules( 'ext.wr.ShareBar.js' );

        return true;
    }

    public static function onSkinHelenaSidebarButtons( &$skin, &$buttons ) {
        $buttons .= ExtShareBar::makeModalButton( 'donate' );
        $buttons .= ExtShareBar::makeModalButton( 'feedback' );

        return true;
    }

} 
