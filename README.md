WikiRights ShareBar extension for MediaWiki
===========================================

This extension adds a social sharebar, using the parser-function {{#sharebar:}}. It was designed
for use with the non-public skin:Helena, but can be used independently.

Services included:

- Facebook (share)
- Twitter (share)
- Google+ (share)
- Print (javascript-dependant)
- Feedback (custom URL, point at your own form)
- Change Request (custom URL, point at your own form)
- Donate (custom URL, point at your own form)

If JavaScript is available, all of these will open either in a new pop-up window (Facebook, Twitter
, etc.) or a modal window (note: the modal windows used are Bootstrap's, which aren't included here!).
If JavaScript is not available, these will open in a plain new window/tab.


## Configuration
- `$egShareBarServices`: used to override the default configuration, listed in
  `$egShareBarDefaultServices` (see `extension.json`).
    - It is possible to change URLs for services or the size of windows/dialogs to be opened
    (sensible defaults based on official recommendations by Facebook, etc)
- `$egShareBarDisabledServices`: an array of service names *not* to display.



## Changelog

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
 * Hide JavaScript-dependant sharing options (based on the .no-js <body> class)
 * Add a "valid services" const to evaluate $egShareBarServices against
 * Consider using jQueryUI's dialog window, which already comes with 

