<?php

class ExtShareBarHooks {
	/**
	 * ResourceLoaderGetConfigVars hook
	 * Make extension configuration variables available in javascript
	 *
	 * @param array &$vars
	 * @return true
	 */
	public static function onResourceLoaderGetConfigVars( &$vars ) {
		global $egShareBarServicesConfig;
		$vars['egShareBar'] = $egShareBarServicesConfig;

		return true;
	}

	/**
	 * BeforePageDisplay hook
	 *
	 * Add the modules to the page
	 *
	 * @param OutputPage &$out output page
	 * @param Skin &$skin current skin
	 *
	 * @return true
	 */
	public static function onBeforePageDisplay( OutputPage &$out, &$skin ) {
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
