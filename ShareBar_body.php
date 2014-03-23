<?php

class ExtShareBar {

    public static function makeModalButton( $type ) {
        global $egShareBarServices;
        $output = null;

        $props = $egShareBarServices[$type];
        if( is_array( $props ) && !empty( $props['link'] ) ) {
            $text = wfMessage( 'ext-sharebar-btn-' . $type )->text();
            $linkModalWidth = isset( $props['width'] ) ? $props['width'] : 750;
            $linkModalHeight = isset( $props['height'] ) ? $props['height'] : 600;

            $output = "<a href=\"{$props['link']}\" target=\"_blank\" class=\"sidebar-btn wr-sidebar-btn-$type\""
                . " data-open-as=\"modal\" data-target=\"#wr-sharebar-modal-$type\""
                . "data-width=\"$linkModalWidth\" data-height=\"$linkModalHeight\"><button class=\"btn col-sm-12\">"
                . "$text</button></a>";

            $output .= self::makeModalContainer( $type, $text );
        }

        return $output;
    }

    static function makeModalContainer( $name, $title ) {
        $output = <<<HTML
<div id="wr-sharebar-modal-{$name}" class="modal fade wr-sharebar-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><!--
    --><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><!--
    --><h4 class="modal-title">{$title}</h4>
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

        $egShareBarServices['send'] += array(
            'link' => self::buildEmailShareUrl(),
            'openAs' => 'modal'
        );
        $egShareBarServices['changerequest']['openAs'] = 'modal';
        $egShareBarServices['print']['openAs'] = 'print';

        $url = wfExpandUrl( $title->getLocalURL() );

        foreach( array( 'facebook', 'twitter', 'gplus') as $service ) {
            $egShareBarServices[$service] += array(
                'openAs' => 'window',
                'link' => self::buildShareUrl( $service, $url, 'title???' )
            );
        }

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


        $output = '<div class="wr-sharebar noprint">';

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
                $linkModalWidth = isset( $props['width'] ) ? $props['width'] : 600;
                $linkModalHeight = isset( $props['height'] ) ? $props['height'] : 600;
                $link = "<a class=\"$linkClass\" href=\"$link\" target=\"_blank\""
                    . "data-target=\"#wr-sharebar-modal-$item\" data-open-as=\"$linkOpenAs\""
                    . "data-width=\"$linkModalWidth\" data-height=\"$linkModalHeight\">$text</a>";

                $li = "<li class=\"wr-sharebar-$item\">$link</li>";

                $output .= $li;

                if ( $linkOpenAs === 'modal' ) {
                    $output .= self::makeModalContainer( $item, $text );
                }
            }

            $output .= '</ul>';

        }

        $output .= '</div>';
        return $output;
    }


    private function buildShareUrl( $service, $url, $title ) {
        $vars = array( '{url}', '{title}', '{text}' );
        $msg = wfMessage( "ext-sharebar-$service-msg");
        $text = $msg->exists() ? $msg->text() : '';
        $data = array( rawurlencode( $url ), rawurlencode( $title ), rawurlencode( $text ) );

        $serviceUrl = array(
            'facebook' => 'http://www.facebook.com/sharer/sharer.php?s=100&p[url]={URL}&p[images][0]={IMAGE}&p[title]={TITLE}&p[summary]={TEXT}',
            'twitter' => 'https://twitter.com/intent/tweet?url={URL}&text={TEXT}',  //More optional params: &text, &hashtags
            'gplus' => 'https://plus.google.com/share?url={URL}'
        );

        $url = str_ireplace( $vars, $data, $serviceUrl[$service] );

        return $url;


    }

    function buildEmailShareUrl() {
        $link = '/forms/mailArticle/?page={TITLE}&pageUrl={URL}&senderEmail={USER_EMAIL}&senderName={USER_NAME}';
        return $link;
    }
}



/*
                if( in_array( $item, $openAs['modal'] ) ) {
                    $a = self::makeModalButton( $item );
                }

                $text = wfMessage( 'ext-sharebar-' . $item );
                $url = '#';
                if ( isset( $egShareBarServices[$item] ) && isset( $egShareBarServices[$item]['link'] ) ) {
                    $url = $egShareBarServices[$item]['link'];
                }
                $linkClass = 'wr-sharebar-modal-btn';


                $output .= '<li class="wr-sharebar-' . $item . '"><a class="' . $linkClass . '" href="' . $url . '">' . $text . '</a></li>';
            }

            if( $section === 'changerequest' ) {
                // @todo Add params to cr url (name, email, etc.)
                $output .= '<li class="wr-sharebar-changerequest">' . self::makeModalButton( 'changerequest' ) . '</li>';
            }
*/




        /*
        foreach( $egShareBarEnabledServices as $service ) {
            $props = $egShareBarServices[$service];
            if( is_array( $props ) && !empty( $props['link'] ) ) {
                $text = wfMessage( 'ext-sharebar-btn-' . $service )->text();
                $output .= '<a href="'. $props['link'] . '" target="_blank" class="btn sharebar-btn sharebar-btn-'
                    . $service . '">' . $text . '</a><br><br>';
            }
        }
        */

        /*
        global $wgWikiRightsSharingServices;
        $page = $parser->getTitle();
        $title = htmlspecialchars( $page );
        $url = htmlspecialchars( $page->getFullURL() );
        */
/*

		$output = '<a href="" class="sharebar-btn sharebar-feedback-btn">Feedback</button><br><br>';
        $output .= '<button class="sharebar-btn sharebar-cr-btn">Change Request</button><br><br>';
        $output .= '<button class="sharebar-btn sharebar-donate-btn">Donation</button>';
*/

        /*
		
		if( $wgWikiRightsSharingServices['addthis']['enabled'] ) {
			$output .= '<div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="display: inline-block;" addthis:url="' . $url . 
			'" addthis:title="' . $title .'">';
			$output .= '<a class="addthis_button_facebook" title="' . wfMessage( 'wrsharing-facebook' )->escaped() . '"></a>';
			$output .= '<a class="addthis_button_twitter" title="' . wfMessage( 'wrsharing-twitter' )->escaped() . '"></a>';
			$output .= '<a class="kolzchut_button_email at300b" title="' . wfMessage( 'wrsharing-email' )->escaped() . '">';
			$output .= '<span class="at300bs at15nc at15t_email"></span></a>';
			$output .= '<a class="addthis_button_print" title="' . wfMessage( 'wrsharing-print' )->escaped() . '"></a>';
			$output .= '</div>';

			$parser->getOutput()->addModules('ext.wrSharing.addThis');
		}
        */

