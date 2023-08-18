# Change log

## [[1.3.5]](https://github.com/lightspeeddevelopment/lsx-sharing/releases/tag/1.3.5) - 2023-08-18

### Security
- General testing to ensure compatibility with latest WordPress version (6.3).

## [[1.3.4]](https://github.com/lightspeeddevelopment/lsx-sharing/releases/tag/1.3.4) - 2023-04-20

### Security
- General testing to ensure compatibility with latest WordPress version (6.2).

## [[1.3.3]](https://github.com/lightspeeddevelopment/lsx-sharing/releases/tag/1.3.3) - 2022-12-23

### Security
- General testing to ensure compatibility with latest WordPress version (6.1.1).

## [1.3.2]

### Fixed
- Updated the SCPO drag and drop functionality for PHP 8.0

### Security
- General testing to ensure compatibility with latest WordPress version (6.0.2).

## [1.3.1]

### Updated
- Documentation and support links.
- Semantic layout of the heading 1 and heading 2.

### Security
- General testing to ensure compatibility with latest WordPress version (5.6).

## [1.3.0]

### Added

- Added the WordPress link as a new field for each member.
- Added the Github link as a new field for each member.
- Added default WP 5.5 lazyloading.
- Added template for 'Role' taxonomy.

### Changed

- Removed UIX and Changed to CMB2.

### Fixed

- Fixed the multiple carousels not displaying issue.

### Security

- Updating dependencies to prevent vulnerabilities.
- Updating PHPCS options for better code.
- General testing to ensure compatibility with latest WordPress version (5.5).
- General testing to ensure compatibility with latest LSX Theme version (2.9).

## [[1.2.3]](https://github.com/lightspeeddevelopment/lsx-team/releases/tag/1.2.3) - 2020-03-30

### Added

- Adding support for the WordPress REST API.

### Fixed

- Fixed issue `PHP Deprecated: dbx_post_advanced is deprecated since version 3.7.0! Use add_meta_boxes instead`.

### Security

- Updating dependencies to prevent vulnerabilities.
- General testing to ensure compatibility with latest WordPress version (5.4).
- General testing to ensure compatibility with latest LSX Theme version (2.7).

## [[1.2.2]](https://github.com/lightspeeddevelopment/lsx-team/releases/tag/1.2.2) - 2019-12-19

### Added

- Limiting the Testimonials and Projects tabs, they will only appear if the plugins are active.

### Security

- General testing to ensure compatibility with latest WordPress version (5.3).
- Checking compatibility with LSX 2.6 release.

## [[1.2.1]](https://github.com/lightspeeddevelopment/lsx-team/releases/tag/1.2.1) - 2019-11-13

### Security

- Checking LSX 2.6 compatibility.

## [[1.2.0]](https://github.com/lightspeeddevelopment/lsx-team/releases/tag/1.2.0) - 2019-10-01

### Added

- Adding the .gitattributes file to remove unnecessary files from the WordPress version.
- Added in lazyloading for the sliders
- Added in a "Person" Schema using the Yoast API.

## [[1.1.2]](https://github.com/lightspeeddevelopment/lsx-team/releases/tag/v1.1.2) - 2019-04-09

### Added

- Removed the deprecated "create_function".
- Adding rel tags to the team single social media.

### Fixed

- Removing the return false statements stopping the widget from displaying.
- Fixed Array Issue - array_key_exists().
- Fixed travis WPCS errors and NPM security vulnerabilities.
- service tab only will appear if plugin is active.

## [[1.1.1]]()

### Added

- Added compatibility with LSX Videos.
- Added compatibility with LSX Search.
- Improved single tabs section visual (spacing).
- Improved single social icons visual (spacing).

### Fixed

- Fixed PHP warning issues.
- Fixed "lazy load images" on loading size (width).

## [[1.1.0]]()

### Added

- Added compatibility with LSX 2.0.
- New project structure.
- UIX copied from TO 1.1 + Fixed issue with sub tabs click (settings).
- Added compatibility with LSX Services.
- New single option - Featured post.
- Widgets, Shortcodes and Archives - Don't link to single when it is disabled.
- New taxonomy archive/group - Role.
- New single section - Posts.

### Fixed

- Fixed scripts/styles loading order.
- Fixed small issues.

## [[1.0.4]]()

### Changed

- Changed the "Insert into Post" button text from media modal to "Select featured image".

## [[1.0.3]]()

### Fixed

- Adjusted the plugin settings link inside the LSX API Class.

## [[1.0.2]]()

### Fixed

- Fixed all prefixes replaces (to* > lsx_to*, TO* > LSX_TO*).

## [[1.0.1]]()

### Fixed

- Reduced the access to server (check API key status) using transients.
- Made the API URLs dev/live dynamic using a prefix "dev-" in the API KEY.

## [[1.0.0]]()

### Added

- First Version
