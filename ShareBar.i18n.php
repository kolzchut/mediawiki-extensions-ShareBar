<?php
/**
 * Internationalisation file for the WikiRights Customizations Extension.
 *
 * @addtogroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'ext-sharebar'            => 'Kol-Zchut ShareBar',
    'ext-sharebar-desc' => 'ShareBar for for the Kol-Zchut website',
    'ext-sharebar-loading' => 'Loading...',
    'ext-sharebar-feedback-form-title' => 'משוב על שימוש באתר',
    'ext-sharebar-cr-form-title' => 'הצעת שינוי לתוכן האתר',
    'ext-sharebar-donate-form-title' => 'תרומה לכל-זכות',

    'ext-sharebar-btn-donate' => 'תרומה',
    'ext-sharebar-btn-changerequest' => 'הצעת שינוי',
    'ext-sharebar-btn-feedback' => 'משוב',

    'ext-sharebar-share-on' => 'שתפו ב-',
    'ext-sharebar-send' => 'שלחו כתבה',
    'ext-sharebar-print' => 'הדפסה',
    'ext-sharebar-changerequest' => '{{int:ext-sharebar-btn-changerequest}}',

    'ext-sharebar-facebook'	 => '{{int:ext-sharebar-share-on}}',
	'ext-sharebar-twitter'	     => '{{int:ext-sharebar-share-on}}',
    'ext-sharebar-gplus'	     => '{{int:ext-sharebar-share-on}}',

    'ext-sharebar-twitter-msg'	 => '$1 ב#$2 - דעו מה מגיע לכם!',
	'ext-sharebar-facebook-msg'	 => 'מידע על $1 ב$2 - דעו מה מגיע לכם!',


);

/** Message documentation (Message documentation)
 * @author Dror Snir
 */
$messages['qqq'] = array(
	'wrsharings-desc' => '{{desc}}',
	'wrsharing-twitter-msg'	=> '$1 is title, $2 SITENAME, $3 for URL',
);

/** Hebrew (עברית)
 * @author Dror Snir
 */
$messages['he'] = array(
	'wrsharings' 		=> 'כלי שיתוף עבור כל-זכות',
	'wrsharings-desc' 	=> "כלי שיתוף עבור כל-זכות",
	'wrsharing-facebook'	=> 'שיתוף בפייסבוק',
	'wrsharing-twitter'	=> 'שיתוף בטוויטר',
	'wrsharing-twitter-msg'	=> '$1 ב#$2 | $3 (@kolzchut)',
	'wrsharing-email' 	=> 'שיתוף בדוא"ל',
	'wrsharing-print' 	=> 'הדפסה',
	'wrsharing-loading'	     => 'טוען...',
	'wrsharing-newsletter-btn' => 'הרשמה לעדכונים',
	'wrsharing-feedback-btn'       => 'משוב על שימוש באתר',
);

/**
/* Arabic (العربية) 
 * @author Jalal Hassan
 * @author Suheir Daksa-Halabi
 */
$messages['ar'] = array(
	'wrsharing-facebook'            => "شاركوا على الفيسبوك",
	'wrsharing-twitter'             => "شاركوا على تويتير",
	#'wrsharing-twitter-msg'         => "",
	'wrsharing-email'               => "شاركوا بالبريد الإلكتروني",
	'wrsharing-print'               => "للطباعة",
	'wrsharing-loading'	     => 'يجري التحميل',
	'wrsharing-newsletter-btn' => 'التسجيل لنشرة التحديثات',
	'wrsharing-feedback-btn'       => 'تقييم الموقع',	
);
