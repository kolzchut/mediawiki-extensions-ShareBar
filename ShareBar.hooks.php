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
		$parser->setFunctionHook( 'sharebar', [ 'ExtShareBar', 'shareBarFunctionHook' ] );
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
		$vars['egShareBar'] = ExtShareBar::mergeSettings();

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
	public static function onBeforePageDisplay( OutputPage &$out, &$skin ) {
		/*
		 * I'd love to do something like "if ( ExtShareBar::getNumberOfBars() > 0 ) ",
		 * but apprently it's too early for that, and while the parser hook can add the modules
		 * itself, that doesn't cover the case of the skin directly calling makeShareBar(),
		 * which skin:Helena does... so we add the modules unconditionally.
		 */
		$out->addModuleStyles( 'ext.wr.ShareBar' );
		$out->addModules(
			[
			'ext.wr.ShareBar.js',
			'ext.wr.ShareBar.analytics'
			]
		);

		return true;
	}

}
