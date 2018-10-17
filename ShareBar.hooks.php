<?php

class ExtShareBarHooks {
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
