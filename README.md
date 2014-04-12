# Simplet
Simplet is a simple, file-based, cms framework written in php, with members, forums, and blogging built in.

# Simplet is not yet stable.
## Release coming mid-April 2014,
## as and when it is finished.

## Versions

### Version 1
The first ever, internal only, release, was used to run several small blogs that didn't warrant a full WordPress installation.

### Version 2
A slightly souped up version, with some low-level plugin-style support and RSS feeds. Replaces WordPress more widely.

### Version 3
First release fully segregated from any hard-coded site-dependent information, replaces all eustasy installs of WordPress except on legacy, "sleeper" sites.

#### 3.7 +
The first truly open-sourced release (under the MIT license), 3.7 through 9 are  Alphas and Betas (as marked) for the version 4 release.

### Version 4
The upcoming release.

## Features
- Responsive Layout
- Server Side
	- Nginx Compatible
	- PHP 5.3+
	- MySQL and MariaDB Tested
- Members
	- Signup
	- Login
	- Logout
	- Session Listing
	- Session Termination
	- Change Name
	- Change Mail
	- Change Pass
	- Reset Pass
- Forums
	- Categories
	- Topics
	- SEO Friendly Slugs
	- Markdown Posts
- File-Based Blog
	- Categories
	- Trending Posts
- Open Source
- Quotes Douglas Adams

## Roadmap

### 4
- Forum
	- Feature: Unread, Read Icon (Database AND Cookies)
	- Improvement: Add Cached Counts to Categories
- Responses
	- Feature: Order by Helpfulness, Recency and Age
	- Bug: Show Error on Error
	- Bug: Special Characters do not display properly in code blocks.

### 4.0.1
- Global
	- Move to more popular [Google Fonts](//fonts.googleapis.com/css?family=Open+Sans300,400|Droid+Sans:400,700|Droid+Serif:400,700,400italic,700italic)
	- Check MySQL_Connection before attempts
	- Improve Code Commenting

### 4.1
- Admin Interface
	- User Management
		- Edit/Lock/Delete
		- Mail
		- Reset Pass
	- Forum
		- Create/Edit/Private/Hide/Lock/Delete Categories
		- Edit/Private/Hide/Lock/Delete Topics
	- Responses Edit/Hide/Delete
- Configuration
	- Move Database Config to `once/connect.php` (?)
	- Move Remaining to Database
- Security Limits
	- Time-and-Attempt-based
	- Option of System
		- Captcha
		- SweetCaptcha
		- Block
	- Signups
	- Logins
	- Resets
	- Forum Topics
	- Responses
- Responses
	- Editing
		- Configuration Option for Time Locking Edits
	- Responses to Responses
	- Order by Responses to Responses
- Feeds
	- File Based
		- Categories
		- Pagination
	- Database
		- Pagination

### Future
- User Accounts
	- Groups
	- User Profiles
	- Inter-User Messaging
- Security Limit Options
	- English
	- Maths
	- Science
- Database-Driven Blog & Pages
- Apache Config File (with Extensionless PHP)

## Libraries

### PHP
- [Browning (0.23)](https://github.com/eustasy/browning-a-mailgun-script)
- [Parsedown (0.9.4)](https://github.com/erusev/parsedown)
- [Recaptcha (1.11)](https://www.google.com/recaptcha/admin)
- [Sweet Captcha (1.1.0)](https://github.com/sweetcaptcha/sweetcaptcha-sdk-php)

### CSS
- [HTML5 Reset (2.1.2)](https://github.com/murtaugh/HTML5-Reset)

### JavaScript
- [Modernizr](http://modernizr.com/)
- [Selectivizr](https://github.com/keithclark/selectivizr)
- [PrefixFree](https://github.com/LeaVerou/prefixfree)
- [jQuery](http://jquery.com/)
- [jQuery.equalize](https://github.com/eustasy/jquery.equalize)
- [jQuery.downBoy](https://github.com/eustasy/jquery.downboy)
