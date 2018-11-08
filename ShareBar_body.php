<?php

class ExtShareBar {
	private static $isMergedSettings = false;
	private static $isSetDefaults = false;

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
			$egShareBarServices = array_diff_key(
				$egShareBarServices,
				array_flip( $egShareBarDisabledServices )
			);
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
			$props['class'] = '';
			$props['icon'] = empty( $props['icon'] ) ? $props['name'] : $props['icon'];
		}

		$services = [
			'facebook', 'twitter', 'send',
			'whatsapp', 'telegram', 'email'
		];
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

		return $parser->insertStripItem( $shareBar );
	}


	public static function makeMobileShareBar( $title, $id = null ) {
		global $egShareBarMobileServices, $egShareBarMobileServicesLimit,
		       $egShareBarMobileServicesFlipOrder;
		$services = $egShareBarMobileServices;
		$services = self::getSpecificServices( $title, $services );
		$services['email']['iconClass'] = 'envelope-o';

		if ( !$services || count( $services ) === 0 ) {
			return false;
		}

		if ( $egShareBarMobileServicesFlipOrder = true ) {
			$services = array_reverse( $services );
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
		$services = [
			'whatsapp',
			'facebook',
			'twitter',
			'email',
			'print',
		];
		$services = self::getSpecificServices( $title, $services );

		$shareLink = self::renderTemplate( 'desktopShareBar.getlink', [
			'text' => wfMessage( 'ext-sharebar-getlink' )->text(),
			'btn-text' => wfMessage( 'ext-sharebar-getlink-btn' )->text(),
			'link' => htmlspecialchars( self::getNicePageURL( $title ) )
		] );
		// Remove all lines breaks, etc., because MW wreaks havoc by making things into <P>s.
		$shareLink = str_replace( [ "\t", "\n" ], '', $shareLink );

		if ( !$services || count( $services ) === 0 ) {
			return false;
		}

		$templateData = [
			'id' => $id,
			'services' => [
				$services['whatsapp'],
				$services['facebook'],
				$services['twitter'],
				$services['email'],
				$services['print'],
				[ 'name' => 'getlink',
				  'arbitraryhtml?' => [ 'html' => $shareLink ]
				],
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
			'{categories}' => rawurlencode( $categories ),
		];

		$serviceUrl = [
			'facebook' => 'https://www.facebook.com/sharer/sharer.php?u={URL}',
			'twitter' => 'https://twitter.com/intent/tweet?url={URL}&text={TEXT}',  // More optional params: &text, &hashtags
			'send' => $egShareBarServices['send']['url']
				. '?page={TITLE}&pageUrl={URL}',
			'whatsapp' => "https://api.whatsapp.com/send?text={TEXT}%0A{NICE_URL}",
			'telegram' => 'https://telegram.me/share/url?url={NICE_URL}&text={TEXT}',
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
