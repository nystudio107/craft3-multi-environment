# Craft3-Multi-Environment Changelog

## 1.0.3 - 2017.10.28
### Changed
* Added `CRAFTENV_SECURITY_KEY` to `example.env.php`
* Added `securityKey` defaults to `general.php`
* Added `defaultSearchTermOptions` defaults to `general.php`
* Added `isSystemOn` defaults to `general.php`
* Added `backupDbOnUpdate` defaults to `general.php`
* Added Composer support

## 1.0.2 - 2017.07.20
### Changed
* Fixed the `forge-example` to used `fastcgi_param` instead of `SetEnv` (which is for Apache)
* PSR2 code cleanup
* Added `.gitignore`

## 1.0.1 - 2017.02.20
### Changed
* Handle load balancing and shared environments better via a check for `HTTP_X_FORWARDED_PROTO` in the protocol
* General code cleanup

## 1.0.0 - 2017.02.1
### Added
* Ported to Craft 3

Brought to you by [nystudio107](https://nystudio107.com/)
