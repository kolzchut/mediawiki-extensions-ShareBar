<?php

class ExtShareBar {
	private static $numOfBars = 0;
	private static $isMergedSettings = false;
	private static $isSetDefaults = false;

	public static function getNumberOfBars() {
		return self::$numOfBars;
	}

	static function mergeSettings() {
		global $egShareBarServices, $egShareBarServicesDefaults, $egShareBarDisabledServices;

		// Only do this once: merge defaults and overridden settings
		if ( self::$isMergedSettings !== true ) {
			$egShareBarServices = array_replace_recursive(
				$egShareBarServicesDefaults,
				$egShareBarServices
			);
			self::$isMergedSettings = true;
			// Remove disabled services
			$egShareBarServices = array_diff_key( $egShareBarServices, $egShareBarDisabledServices );
		}

		return $egShareBarServices;
	}

	/**
	 * @param Title $title
	 * @return void
	 *
	 */
	static function setServicesDefaults( Title $title ) {
		global $egShareBarServices;

		// Only run once
		if ( self::$isSetDefaults === true ) {
			return;
		}
		self::$isSetDefaults = true;
		self::mergeSettings();

		foreach ( $egShareBarServices as $service => &$props ) {
			$props['name'] = $service;
			if ( empty( $props['url'] ) ) {
				$props['url'] = '#';
			}
			$props['text'] = wfMessage( 'ext-sharebar-' . $service )->text();
			$additionalScreenReaderTextMsg = wfMessage( 'ext-sharebar-service-name-' . $service );

			$props['screenReaderText'] = $additionalScreenReaderTextMsg->isBlank() ?
				false : $additionalScreenReaderTextMsg->text();
			$props['class'] = ( $service === 'changerequest' ? ' btn' : '' );
		}

		$services = [ 'facebook', 'twitter', 'google', 'send', 'changerequest', 'whatsapp', 'email' ];
		foreach ( $services as $service ) {
			$egShareBarServices[$service]['url'] = ExtShareBar::buildShareUrl( $service, $title );
		}

	}

	static function getSpecificServices( $title, $services ) {
		global $egShareBarServices;

		self::setServicesDefaults( $title );

		if ( !is_array( $services ) ) {
			return null;
		}

		// Get only the relevant values
		$intersect = array_intersect_key( $egShareBarServices, array_flip( $services ) );
		// Order according to the specified array
		$intersect = array_replace( array_flip( $services ), $intersect );


		return $intersect;
	}

	/**
	 * @param $parser Parser
	 * @return String
	 *
	 * @todo Check enabled services for validity
	 */
	public static function shareBarFunctionHook( Parser &$parser, $id ) {
		$id = !empty( $id ) ? trim( $id ) : null;
		$shareBar = self::makeDesktopShareBar( $parser->getTitle(), $id );

		return [ $shareBar, 'noparse' => true, 'isHTML' => true ];
	}


	public static function makeMobileShareBar( $title, $id = null ) {
		global $egShareBarMobileServices, $egShareBarMobileServicesLimit;
		$services = $egShareBarMobileServices;
		$services = self::getSpecificServices( $title, $services );
		$services['email']['iconClass'] = 'envelope-o';

		if ( !$services || count( $services ) === 0 ) {
			return false;
		}

		$additionalServices = [];
		if ( count( $services ) > $egShareBarMobileServicesLimit ) {
			// Reverse the additional services, because the menu is vertical and read top-to-bottom
			$additionalServices = array_reverse( array_slice( $services, $egShareBarMobileServicesLimit-1, null, true ) );
			$services = array_slice( $services, 0, $egShareBarMobileServicesLimit-1, true );
		}

		$templateData['id'] = $id;
		$templateData['moreText'] = wfMessage( 'ext-sharebar-service-name-more' )->text();
		$templateData['services'] = new ArrayIterator( $services );
		$templateData['additionalServices'] = new ArrayIterator( $additionalServices );
		$templateData['hasAdditionalServices'] = !empty( $additionalServices );

		return self::renderTemplate( 'mobileShareBar', $templateData );
	}


	public static function makeDesktopShareBar( $title, $id = null ) {
		$services = [ 'print', 'send', 'facebook', 'twitter', 'google', 'changerequest' ];
		$services = self::getSpecificServices( $title, $services );

		if ( !$services || count( $services ) === 0 ) {
			return false;
		}

		$templateData = [
			'id' => $id,
			'sections' => [
				[
					'name' => 'localshare',
					'services' => [
						$services['print'],
						$services['send']
					]
				],
				[
					'name' => 'cloudshare',
					'services'=> [
						$services['facebook'],
						$services['twitter'],
						$services['google']
					]
				],
				[
					'name' => 'changerequest',
					'services'=> [
						$services['changerequest']
					],
				]
			]
		];

		return self::renderTemplate( 'desktopShareBar', $templateData );
	}


	public static function renderTemplate( $templateName, $data ) {
		$templateParser = new \TemplateParser( __DIR__ . '/templates' );
		$result = $templateParser->processTemplate( $templateName, $data );

		return $result;
	}


	/**
	 * @deprecated This function was replaced by makeDesktopShareBar() in v1.0.0, but left for b/c
	 *
	 * @param Title $title
	 *
	 * @return String
	 * @todo Check enabled services for validity
	 */
	public static function makeShareBar( Title $title ) {
		return self::makeDesktopShareBar( $title );
	}


	private static function buildShareUrl( $service, Title $title ) {
		/** Legit globals */
		global $egShareBarServices, $wgSitename;
		/** Evil globals */
		global $wgLanguageCode, $wgContLang;

		$analyticsTagging = '?utm_source=sharebar&utm_medium=' . $service; // Tag us as source

		/// Data gathering
		$pageName = $title->getPrefixedText();
		$url = wfExpandUrl( $title->getLocalURL() . $analyticsTagging );
		$niceUrl = self::getNicePageURL( $title ) . $analyticsTagging;
		$msg = wfMessage( "ext-sharebar-$service-msg" );
		$msg = $msg->exists() ? $msg : wfMessage( 'ext-sharebar-share-text' );
		$text = $msg->params( $pageName, $wgSitename, $url )->text();
		$categories = implode( ',', array_keys( $title->getParentCategories() ) );
		$categories = str_replace( $wgContLang->getNsText( NS_CATEGORY ) . ':', '', $categories );
		$categories = str_replace( '_', ' ', $categories );

		$placeholders = [
			'{url}' => rawurlencode( $url ),
			'{nice_url}' => rawurlencode( $niceUrl ),
			'{title}' => rawurlencode( $pageName ),
			'{text}' => rawurlencode( $text ),
			'{language}' => $wgLanguageCode,
			'{categories}' => rawurlencode( $categories )
		];

		$serviceUrl = [
			'facebook' => 'http://www.facebook.com/sharer/sharer.php?u={URL}',
			'twitter' => 'https://twitter.com/intent/tweet?url={URL}&text={TEXT}',  //More optional params: &text, &hashtags
			'google' => 'https://plus.google.com/share?url={URL}',
			'send' => $egShareBarServices['send']['url']
				. '?page={TITLE}&pageUrl={URL}',
			'changerequest' => $egShareBarServices['changerequest']['url']
				. '?page={TITLE}&lang={language}&categories={CATEGORIES}',
			'whatsapp' => 'whatsapp://send?text={TEXT}%20{NICE_URL}',
			'email' => 'mailto:?subject={TEXT}&body={NICE_URL}'

		];

		$url = str_ireplace(
			array_keys( $placeholders ),
			array_values( $placeholders ),
			$serviceUrl[$service]
		);

		return $url;
	}


	private static function getNicePageURL( Title $title ) {
		return wfExpandIRI( $title->getFullURL() );
	}

}
