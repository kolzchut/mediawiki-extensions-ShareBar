<?php

class ExtShareBar {
	/**
	 * @param Title $title
	 * @param string[] $services
	 *
	 * @return array
	 */
	private static function getServices( Title $title, $services ) {
		global $egShareBarServicesConfig;
		$serviceSettings = [];

		foreach ( $services as $serviceName ) {
			$service = $egShareBarServicesConfig[$serviceName];
			$service['name'] = $serviceName;
			$service['text'] = wfMessage( 'ext-sharebar-' . $serviceName )->text();
			$service['class'] = '';
			$service['icon'] = empty( $service['icon'] ) ? $serviceName : $service['icon'];
			$service['url'] = self::buildShareUrl( $serviceName, $title );

			$serviceSettings[$serviceName] = $service;
		}

		return $serviceSettings;
	}

	/**
	 * @param Parser &$parser
	 * @param int $id
	 * @return String
	 *
	 * @todo Check enabled services for validity
	 */
	public static function shareBarFunctionHook( Parser &$parser, $id ) {
		$id = !empty( $id ) ? trim( $id ) : null;
		$shareBar = self::makeDesktopShareBar( $parser->getTitle(), $id );

		return $parser->insertStripItem( $shareBar );
	}

	/**
	 * @param Title $title
	 * @param null|int $id
	 *
	 * @return bool|string
	 */
	public static function makeMobileShareBar( $title, $id = null ) {
		global $egShareBarMobileServices, $egShareBarMobileServicesLimit,
			   $egShareBarMobileServicesFlipOrder;

		$services = self::getServices( $title, explode( ',', $egShareBarMobileServices ) );
		$services['email']['iconClass'] = 'envelope-o';

		if ( !$services || count( $services ) === 0 ) {
			return false;
		}

		if ( $egShareBarMobileServicesFlipOrder === true ) {
			$services = array_reverse( $services );
		}

		$additionalServices = [];
		if ( count( $services ) > $egShareBarMobileServicesLimit ) {
			// Reverse the additional services, because the menu is vertical and read top-to-bottom
			$additionalServices = array_reverse(
				array_slice( $services, $egShareBarMobileServicesLimit - 1, null, true )
			);
			$services = array_slice( $services, 0, $egShareBarMobileServicesLimit - 1, true );
		}

		$templateData['id'] = $id;
		$templateData['moreText'] = wfMessage( 'ext-sharebar-service-name-more' )->text();
		$templateData['services'] = new ArrayIterator( $services );
		$templateData['additionalServices'] = new ArrayIterator( $additionalServices );
		$templateData['hasAdditionalServices'] = !empty( $additionalServices );

		return self::renderTemplate( 'mobileShareBar', $templateData );
	}

	/**
	 * @param Title $title
	 * @param null|int $id
	 *
	 * @return bool|string
	 */
	public static function makeDesktopShareBar( $title, $id = null ) {
		global $egShareBarServices;

		$services = self::getServices( $title, explode( ',', $egShareBarServices ) );

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

		$templateData[ 'id' ] = $id;
		$templateData[ 'services' ] = array_values( $services );
		$templateData[ 'services' ][] = [
			'name' => 'getlink',
			'arbitraryhtml?' => [ 'html' => $shareLink ]
		];

		return self::renderTemplate( 'desktopShareBar', $templateData );
	}

	/**
	 * @param string $templateName
	 * @param array $data
	 *
	 * @return string
	 */
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
		global $wgSitename;
		/** Evil globals */
		global $wgLanguageCode, $wgContLang;

		$serviceUrl = [
			'facebook' => 'https://www.facebook.com/sharer/sharer.php?u={URL}',
			'twitter' => 'https://twitter.com/intent/tweet?url={URL}&text={TEXT}',
			'whatsapp' => "https://api.whatsapp.com/send?text={TEXT}%0A{NICE_URL}",
			'telegram' => 'https://telegram.me/share/url?url={NICE_URL}&text={TEXT}',
			'email' => 'mailto:?subject={TEXT}&body={NICE_URL}'
		];

		// Set default
		if ( !array_key_exists( $service, $serviceUrl ) ) {
			return '#';
		}

		// Tag us as source
		$analyticsTagging = '?utm_source=sharebar&utm_medium=' . $service;

		// Data gathering
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
