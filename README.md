WikiRights ShareBar extension for MediaWiki
===========================================

This extension was developed for Kol-Zchut, and is quite dependant on
the the non-public skin:Helena.

The extension adds a mobile and desktop sharebars - the mobile version
is fixed at the bottom of the screen, while the desktop version is fixed
to the right of the screen.

Services included:
- Facebook (share)
- Twitter (share)
- Print (javascript-dependant)
- Change Request (custom URL, point at your own form)
- WhatsApp
- Telegram
- Email

If JavaScript is available, all of these will open either in a new pop-up window (Facebook, Twitter
, etc.) or a modal window (note: the modal windows used are Bootstrap's, which aren't included here!).
If JavaScript is not available, these will open in a plain new window/tab.

## Usage
To use from another extension or skin, call one of the relevant functions in PHP:
- `ExtShareBar::makeDesktopShareBar( $this->getSkin()->getTitle() );`
- `ExtShareBar::makeMobileShareBar( $this->getSkin()->getTitle() );`
  You can add an HTML id to either sharebar by passing a string as a 2nd parameter.


## Configuration
- `$egShareBarServices`: comma-separated list of services. You can override
  the default list, e.g. 'facebook,twitter,telegram'.
  (see `extension.json`).
- `$egShareBarMobileServices`: same as `$egShareBarServices`, but for mobile.
- `$egShareBarMobileServicesLimit`: if more than this number of is selected, any additional
  services will show in an overflow menu - so you will see that number of services + a "more" button.
- `$egShareBarServicesConfig`: an array of  defaults for the different services -
   Change URLs for services or the size of windows/dialogs to be opened
   (sensible defaults based on official recommendations by Facebook, etc)
- `$egShareBarMobileServicesFlipOrder`: flip the order. Useful when you prefer this list
  to not be in the normal wiki directionality (for example, in Hebrew we prefer this list to show
  left-to-right, because it seems more natural)

### Set custom URLs for Feedback
You can set custom URLs for these by overriding `$egShareBarServicesConfig` like this:
`$egShareBarServicesConfig['feedback']['url'] = 'http://www.google.com';`

## Changelog

### 2.0.0 [2018-11-xx]
This release is aimed at skin:Helena version 4.0.
- The desktop sharebar is now injected by the skin
  - Parser function {{#sharebar:}} was removed
- The desktop sharebar is a sticky side bar.
- Removed Google+ & Change Request services
- `$egShareBarServices` and `$egShareBarMobileServices` are now comma-separated
  lists (string) instead of arrays, to bypass problems with MediaWiki.
- `$egShareBarDisabledServices` was removed - to disable services override
  `$egShareBarServices` and/or `$egShareBarMobileServices`
- `$egShareBarServicesDefaults` is now `$egShareBarServicesConfig`
### 1.3.0 [2018-03-23]
- Add Telegram as a service.
- Add $egShareBarMobileServicesFlipOrder to control the order of display in mobile
- Fix a bug where the CSS caused each icon to take up 25% percent of width in
  mobile, even after you changed the limit to, say, 5.
- Move repeating (mobile) template code to a partial template.
### 1.2.2 [2017-10-23]
- Add $egShareBarMobileServicesLimit to control the number of displayed icons
### 1.2.0 [2017-05-26]
- Add Google Analytics tagging to the shared links, so we estimate
  their effect. This add "utm_source=sharebar&utm_medium={service_name}".

### 1.1.0 [2017-05-18]
- It is now possible to configure the list of services for mobile.
- The buttons are now round.

### 1.0.0 [2017-05-17]
- Add a mobile share bar option (responsive). It has a different list
  of services, to be configurable in the future
- Some code refactoring
- Google Analytics social events are sent for all clicks
- ShareBars may now have their own unique HTML id
- Hide JavaScript-only actions, such as print, when JavaScript is disabled
- (Technical:) we now use mustache templates to generate HTML


### 0.2.0 [2015-12-15]
- Breaking change: compatible only with MediaWiki => 1.25
- Move the default services definition to $egShareBarServicesDefaults, to be merged with any overrides
  in $egShareBarServices
- Remove old i18n PHP files, use JSON i18n files
- Use extension.json for extension loading, using wfLoadExtension() - remove old PHP entry point
- Generate the dialog iframe on the client-side as needed, to avoid it interefering with page loading
- Re-license as GPLv3 (instead of GPLv2+)


### 0.1.0
Initial release


## Todo
* Add a "valid services" const to evaluate $egShareBarServices against
* Consider using OOUI's dialog, which already comes with MediaWiki,
   instead of using Bootstrap's dialog (which is an extra dependency)

