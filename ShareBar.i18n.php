<?php
/**
 * Internationalisation file for the WikiRights ShareBar Extension.
 *
 * @addtogroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'ext-sharebar' => 'Kol-Zchut ShareBar',	// Name of this extension, only shows in credits
	'ext-sharebar-desc' => 'ShareBar for for the Kol-Zchut website', // Description of this extension, only shows in credits
	# forms
	'ext-sharebar-loading' => 'Loading...',	// Text to show while loading sharing forms
	'ext-sharebar-feedback-form-title' => 'Give us feedback',	// Title for the feedback form
	'ext-sharebar-cr-form-title' => 'Submit a change proposal',	// Tooltip for change proposal button
	'ext-sharebar-donate-form-title' => 'Donate to {{SITENAME}}',	// Title for the donation form
	# buttons
	'ext-sharebar-btn-donate' => 'Donate',	// Donation button
	'ext-sharebar-btn-changerequest' => 'Change Proposal',	// Button
	'ext-sharebar-btn-feedback' => 'Feedback',	// Button which loads a feedback form
	# bar
	// The following is a prefix to sharing buttons, which show the logo for each service, so it looks like this:
	// "Share on [facebook logo]"
	'ext-sharebar-share-on' => 'Share on&nbsp;&nbsp;',
	'ext-sharebar-send' => 'Send article',	// Share an article with a friend, through email at the moment
	'ext-sharebar-print' => 'Print',	// Print button
	'ext-sharebar-changerequest' => '{{int:ext-sharebar-btn-changerequest}}',	// Do not translate
	'ext-sharebar-facebook'	 => '{{int:ext-sharebar-share-on}}', // Do not translate
	'ext-sharebar-twitter' => '{{int:ext-sharebar-share-on}}', // Do not translate
	'ext-sharebar-gplus' => '{{int:ext-sharebar-share-on}}', // Do not translate

	'ext-sharebar-service-name-facebook' => 'Facebook',
	'ext-sharebar-service-name-twitter' => 'Twitter',
	'ext-sharebar-service-name-gplus' => 'Google+',

	# sharing texts
	'ext-sharebar-twitter-msg' => '$1 at #$2 - know your rights!',	// Template text for sharing on Twitter
	'ext-sharebar-facebook-msg' => 'Learn about $1 at $2 - know your rights!', // Template text for sharing on Facebook
);

/** Message documentation (Message documentation)
 * @author Dror Snir
 */
$messages['qqq'] = array(
);

/** Hebrew (עברית)
 * @author Dror Snir
 */
$messages['he'] = array(
	'ext-sharebar' => 'סרגל שיתוף עבור כל-זכות',
	'ext-sharebar-desc' => "כלי שיתוף עבור כל-זכות",
	# forms
	'ext-sharebar-loading' => 'בטעינה...',
	'ext-sharebar-feedback-form-title' => 'משוב על שימוש באתר',
	'ext-sharebar-cr-form-title' => 'הצעת שינוי לתוכן האתר',
	'ext-sharebar-donate-form-title' => 'תרומה לכל-זכות',
	# buttons
	'ext-sharebar-btn-donate' => 'תרומה',
	'ext-sharebar-btn-changerequest' => 'הצעת שינוי',
	'ext-sharebar-btn-feedback' => 'משוב',
	# bar
	'ext-sharebar-share-on' => 'שתפו ב-',
	'ext-sharebar-send' => 'שלחו כתבה',
	'ext-sharebar-print' => 'הדפסה',

	# sharing texts
	'ext-sharebar-twitter-msg' => '$1 ב#$2 - דעו מה מגיע לכם!',
	'ext-sharebar-facebook-msg' => 'מידע על $1 ב$2 - דעו מה מגיע לכם!',
);

/**
/* Arabic (العربية)
 * @author Ahlam Rahal
 * @author Jalal Hassan
 * @author Suheir Daksa-Halabi
 */
$messages['ar'] = array(
	'ext-sharebar' => 'لوحة مشاركة كل الحق',	// Name of this extension, only shows in credits
	'ext-sharebar-desc' => 'أدوات مشاركة كل الحق',	// Description of this extension, only shows in credits
	# forms
	'ext-sharebar-loading' => 'تحميل...',	// Text to show while loading sharing forms
	'ext-sharebar-feedback-form-title' => ' رد فعل حول استخدام الموقع',	// Title for the feedback form
	'ext-sharebar-cr-form-title' => 'اكتب اقتراح تغيير',	// Tooltip for change proposal button
	'ext-sharebar-donate-form-title' => 'تبرع لـ {{SITENAME}}',	// Title for the donation form
	# buttons
	'ext-sharebar-btn-donate' => 'تبرع',	// Donation button
	'ext-sharebar-btn-changerequest' => 'اقتراح تغيير',	// Button
	'ext-sharebar-btn-feedback' => 'رد فعل',	// Button which loads a feedback form
	# bar
	// The following is a prefix to sharing buttons, which show the logo for each service, so it looks like this:
	// "Share on [facebook logo]"
	'ext-sharebar-share-on' => 'شارك&nbsp;',
	'ext-sharebar-send' => 'أرسل المقال',	// Share an article with a friend, through email at the moment
	'ext-sharebar-print' => 'طباعة',	// Print button
	'ext-sharebar-changerequest' => '{{int:ext-sharebar-btn-changerequest}}',	// Do not translate
	'ext-sharebar-facebook'     => '{{int:ext-sharebar-share-on}}', // Do not translate
	'ext-sharebar-twitter' => '{{int:ext-sharebar-share-on}}', // Do not translate
	'ext-sharebar-gplus' => '{{int:ext-sharebar-share-on}}', // Do not translate
	# sharing texts
	'ext-sharebar-twitter-msg' => '$1 حتى #$2 – اعرف حقوقك!',    // Template text for sharing on Twitter
	'ext-sharebar-facebook-msg' => ' تعلم حول $1 حتى $2 – اعرف حقوقك!', // Template text for sharing on Facebook
);

