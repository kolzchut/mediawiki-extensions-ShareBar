<?php

class ExtShareBar {
	static $numOfBars = 0;
	static $isModalContainerCreated = false;

    public static function makeModalButton( $type ) {
        global $egShareBarServices;
        $output = null;

        $props = $egShareBarServices[$type];
        if( is_array( $props ) && !empty( $props['link'] ) ) {
            $text = wfMessage( 'ext-sharebar-btn-' . $type )->text();
            $linkModalWidth = isset( $props['width'] ) ? $props['width'] : 800;
            $linkModalHeight = isset( $props['height'] ) ? $props['height'] : 700;

            $output = "<a href=\"{$props['link']}\" target=\"_blank\" class=\"sidebar-btn wr-sidebar-btn-$type\""
                . " data-open-as=\"modal\""
                . "data-width=\"$linkModalWidth\" data-height=\"$linkModalHeight\"><button class=\"btn col-sm-12\">"
                . "<span class=\"img-icon pull-left\"></span>$text</button></a>";

        }

        return $output;
    }

    static function makeModalContainer() {
		global $wgSitename;

		if ( self::$isModalContainerCreated ) { return; }
		self::$isModalContainerCreated = true;	// So we only do this once

        $output = <<<HTML
<div id="wr-sharebar-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><!--
    --><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><!--
    --><h4 class="modal-title">{$wgSitename}</h4>
      </div><iframe src="" frameborder="0"></iframe></div></div>
</div>
HTML;

        return $output;
    }

    /**
     * @param Title $title
     * @return void
     *
     */
    static function setServicesDefaults( Title $title ) {
        global $egShareBarServices;

		foreach( array( 'facebook', 'twitter', 'gplus', 'send', 'changerequest') as $service ) {
            $egShareBarServices[$service]['openAs'] = 'window';
			$egShareBarServices[$service]['link'] = self::buildShareUrl( $service, $title );
        }

		$egShareBarServices['send']['openAs'] = 'modal';
		$egShareBarServices['changerequest']['openAs'] = 'modal';
		$egShareBarServices['print']['openAs'] = 'print';

	}

    /**
     * @param $parser Parser
     * @return String
     *
     * @todo Check enabled services for validity
     */
    public static function shareBarFunctionHook( &$parser ) {
        $output = self::makeShareBar( $parser->getTitle() );
        return array( $output, 'noparse' => true, 'isHTML' => true );
    }


    /**
     * @param Title $title
     * @return String
     *
     * @todo Check enabled services for validity
     */
	public static function makeShareBar( Title $title ) {
        global $egShareBarDisabledServices, $egShareBarServices;

		self::$numOfBars++;

		self::setServicesDefaults( $title );

		$sharebarButtons = array(
            'localshare' => array(
                'print',
                'send'
            ),
            'cloudshare' => array(
                'facebook',
                'twitter',
                'gplus'
            ),
            'changerequest' => array(
                'changerequest'
            )
        );


        $output = '<div class="wr-sharebar hidden-xs hidden-print noprint">';

        foreach( $sharebarButtons as $section => $sectionItems ) {
            $output .= '<ul class="list-inline sharebar-section section-' . $section . '">';

            foreach( $sectionItems as $item ) {
                $link = '';

                if( is_array( $egShareBarDisabledServices ) && in_array( $item, $egShareBarDisabledServices ) ) {
                    continue;
                }
                $props = $egShareBarServices[$item];
                if( is_array( $props ) && !empty( $props['link'] ) ) {
                    $link = htmlspecialchars( $props['link'] );
                } else {
                    $link = '#';
                }
                $text = wfMessage( 'ext-sharebar-' . $item )->text();
                $linkOpenAs = htmlspecialchars( isset( $props['openAs'] ) ? $props['openAs'] : 'none' );
                $linkClass = ( $item === 'changerequest' ? 'btn' : '' );
                $linkModalWidth = isset( $props['width'] ) ? $props['width'] : 800;
                $linkModalHeight = isset( $props['height'] ) ? $props['height'] : 700;
                $link = "<a class=\"$linkClass\" href=\"$link\" target=\"_blank\""
                    . "data-open-as=\"$linkOpenAs\""
                    . "data-width=\"$linkModalWidth\" data-height=\"$linkModalHeight\">$text</a>";

                $li = "<li class=\"wr-sharebar-$item\">$link</li>";

                $output .= $li;

				/*
                if ( $linkOpenAs === 'modal' && !self::$isModalContainerCreated ) {
                    $output .= self::makeModalContainer();
                }
				*/
            }

            $output .= '</ul>';

        }

        $output .= '</div>';

		return $output;
    }


    private function buildShareUrl( $service, Title $title ) {
		/** Legit globals */
		global $egShareBarServices, $wgSitename;
		/** Evil globals */
		global $wgUser, $wgLanguageCode, $wgContLang;


		/// Data gathering
		$pageName = $title->getPrefixedText();
		$url = wfExpandUrl( $title->getLocalURL() );
		$msg = wfMessage( "ext-sharebar-$service-msg");
        $text = $msg->exists() ? $msg->params( $pageName, $wgSitename, $url )->text() : '';
		$categories = implode( ',', array_keys( $title->getParentCategories() ) );
		$categories = str_replace( $wgContLang->getNsText( NS_CATEGORY ) . ':', '', $categories );
		$categories = str_replace( '_', ' ', $categories);

		// Array of placeholder keys to replace with actual values
		$placeholders = array(
			'{url}' => rawurlencode( $url ),
			'{title}' => rawurlencode( $pageName ),
			'{text}' => rawurlencode( $text ),
			'{user_name}' => rawurlencode( $wgUser->isLoggedIn() ? $wgUser->getName() : '' ),
			'{user_email}' => rawurlencode( $wgUser->isLoggedIn() ? $wgUser->getEmail() : '' ),
			'{language}' => $wgLanguageCode,
			'{categories}' => rawurlencode( $categories  )
		);

        $serviceUrl = array(
            'facebook' => 'http://www.facebook.com/sharer/sharer.php?s=100&p[url]={URL}&p[title]={TITLE}&p[summary]={TEXT}',	//Optional: &p[images][0]={IMAGE}
            'twitter' => 'https://twitter.com/intent/tweet?url={URL}&text={TEXT}',  //More optional params: &text, &hashtags
            'gplus' => 'https://plus.google.com/share?url={URL}',
			'send' => $egShareBarServices['send']['link']
				. '?page={TITLE}&pageUrl={URL}&senderName={USER_NAME}&senderEmail={USER_EMAIL}',
			'changerequest' => $egShareBarServices['changerequest']['link']
				. '?page={TITLE}&name={USER_NAME}&email={USER_EMAIL}&lang={language}&categories={categories}'

		);

		$url = str_ireplace( array_keys( $placeholders ), array_values( $placeholders ), $serviceUrl[$service] );

		return $url;
    }

}

