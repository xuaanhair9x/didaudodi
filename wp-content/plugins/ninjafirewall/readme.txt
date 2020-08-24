=== NinjaFirewall (WP Edition) - Advanced Security ===
Contributors: nintechnet, bruandet
Tags: security, firewall, malware, antispam, virus, scanner, hacked site, brute force, seguridad, seguranca, sicherheit, sicurezza, veiligheid, classicpress
Requires at least: 3.7
Tested up to: 5.4
Stable tag: 4.2.2
Requires PHP: 5.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A true Web Application Firewall to protect and secure WordPress.

== Description ==

= A true Web Application Firewall =

NinjaFirewall (WP Edition) is a true Web Application Firewall. Although it can be installed and configured just like a plugin, it is a stand-alone firewall that stands in front of WordPress.

It allows any blog administrator to benefit from very advanced and powerful security features that usually aren't available at the WordPress level, but only in security applications such as the Apache [ModSecurity](http://www.modsecurity.org/ "") module or the PHP [Suhosin](http://suhosin.org/ "") extension.

> NinjaFirewall requires at least PHP 5.5, MySQLi extension and is only compatible with Unix-like OS (Linux, BSD). It is **not compatible with Microsoft Windows**.

NinjaFirewall can hook, scan, sanitise or reject any HTTP/HTTPS request sent to a PHP script before it reaches WordPress or any of its plugins. All scripts located inside the blog installation directories and sub-directories will be protected, including those that aren't part of the WordPress package. Even encoded PHP scripts, hackers shell scripts and backdoors will be filtered by NinjaFirewall.

= Powerful filtering engine =

NinjaFirewall includes the most powerful filtering engine available in a WordPress plugin. Its most important feature is its ability to normalize and transform data from incoming HTTP requests which allows it to detect Web Application Firewall evasion techniques and obfuscation tactics used by hackers, as well as to support and decode a large set of encodings. See our blog for a full description: [An introduction to NinjaFirewall filtering engine](https://blog.nintechnet.com/introduction-to-ninjafirewall-filtering-engine/ "").

= Fastest and most efficient brute-force attack protection for WordPress =

By processing incoming HTTP requests before your blog and any of its plugins, NinjaFirewall is the only plugin for WordPress able to protect it against very large brute-force attacks, including distributed attacks coming from several thousands of different IPs.

See our benchmarks and stress-tests: [Brute-force attack detection plugins comparison](https://blog.nintechnet.com/wordpress-brute-force-attack-detection-plugins-comparison-2015/ "")

The protection applies to the `wp-login.php` script but can be extended to the `xmlrpc.php` one. The incident can also be written to the server `AUTH` log, which can be useful to the system administrator for monitoring purposes or banning IPs at the server level (e.g., Fail2ban).

= Real-time detection =

**File Guard** real-time detection is a totally unique feature provided by NinjaFirewall: it can detect, in real-time, any access to a PHP file that was recently modified or created, and alert you about this. If a hacker uploaded a shell script to your site (or injected a backdoor into an already existing file) and tried to directly access that file using his browser or a script, NinjaFirewall would hook the HTTP request and immediately detect that the file was recently modified or created. It would send you an alert with all details (script name, IP, request, date and time).

= File integrity monitoring  =

**File Check** lets you perform file integrity monitoring by scanning your website hourly, twicedaily or daily. Any modification made to a file will be detected: file content, file permissions, file ownership, timestamp as well as file creation and deletion.

= Watch your website traffic in real time =

**Live Log** lets you watch your website traffic in real time. It displays connections in a format similar to the one used by the `tail -f` Unix command. Because it communicates directly with the firewall, i.e., without loading WordPress, **Live Log** is fast, lightweight and it will not affect your server load, even if you set its refresh rate to the lowest value.

= Event Notifications =

NinjaFirewall can alert you by email on specific events triggered within your blog. Some of those alerts are enabled by default and it is highly recommended to keep them enabled. It is not unusual for a hacker, after breaking into your WordPress admin console, to install or just to upload a backdoored plugin or theme in order to take full control of your website. NinjaFirewall can also [attach a PHP backtrace](https://blog.nintechnet.com/ninjafirewall-wp-edition-adds-php-backtrace-to-email-notifications/ "NinjaFirewall adds PHP backtrace to email notifications") to important notifications.

Monitored events:

* Administrator login.
* Modification of any administrator account in the database.
* Plugins upload, installation, (de)activation, update, deletion.
* Themes upload, installation, activation, deletion.
* WordPress update.
* Pending security update in your plugins and themes.

= Stay protected against the latest WordPress security vulnerabilities =

To get the most efficient protection, NinjaFirewall can automatically update its security rules daily, twice daily or even hourly. Each time a new vulnerability is found in WordPress or one of its plugins/themes, a new set of security rules will be made available to protect your blog immediately.

= Strong Privacy =

Unlike a Cloud Web Application Firewall, or Cloud WAF, NinjaFirewall works and filters the traffic on your own server and infrastructure. That means that your sensitive data (contact form messages, customers credit card number, login credentials etc) remains on your server and is not routed through a third-party company's servers, which could pose unnecessary risks (e.g., decryption of your HTTPS traffic in order to inspect it, employees accessing your data or logs in plain text, theft of private information, man-in-the-middle attack etc).

Your website can run NinjaFirewall and be compliant with the General Data Protection Regulation (GDPR). [See our blog for more details](https://blog.nintechnet.com/ninjafirewall-general-data-protection-regulation-compliance/ "GDPR Compliance").

= IPv6 compatibility =

IPv6 compatibility is a mandatory feature for a security plugin: if it supports only IPv4, hackers can easily bypass the plugin by using an IPv6. NinjaFirewall natively supports IPv4 and IPv6 protocols, for both public and private addresses.

= Multi-site support =

NinjaFirewall is multi-site compatible. It will protect all sites from your network and its configuration interface will be accessible only to the Super Admin from the network main site.

= Possibility to prepend your own PHP code to the firewall =

You can prepend your own PHP code to the firewall with the help of an [optional distributed configuration file](https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja). It will be processed before WordPress and all its plugins are loaded. This is a very powerful feature, and there is almost no limit to what you can do: add your own security rules, manipulate HTTP requests, variables etc.

= Low Footprint Firewall =

NinjaFirewall is very fast, optimised, compact, and requires very low system resource.
See for yourself: download and install [Query Monitor](https://wordpress.org/plugins/query-monitor/ "") and [Xdebug Profiler](https://xdebug.org/ "") and compare NinjaFirewall performances with other security plugins.

= Non-Intrusive User Interface =

NinjaFirewall looks and feels like a built-in WordPress feature. It does not contain intrusive banners, warnings or flashy colors. It uses the WordPress simple and clean interface and is also smartphone-friendly.

= Contextual Help =

Each NinjaFirewall menu page has a contextual help screen with useful information about how to use and configure it.
If you need help, click on the *Help* menu tab located in the upper right corner of each page in your admin panel.

= Need more security ? =

Check out our new supercharged edition: [NinjaFirewall WP+ Edition](https://nintechnet.com/ninjafirewall/wp-edition/ "NinjaFirewall WP+ Edition")

* Unix shared memory use for inter-process communication and blazing fast performances.
* IP-based Access Control.
* Role-based Access Control.
* Country-based Access Control via geolocation.
* URL-based Access Control.
* Bot-based Access Control.
* [Centralized Logging](https://blog.nintechnet.com/centralized-logging-with-ninjafirewall/ "Centralized Logging").
* Antispam for comment and user regisration forms.
* Rate limiting option to block aggressive bots, crawlers, web scrapers and HTTP attacks.
* Response body filter to scan the output of the HTML page right before it is sent to your visitors browser.
* Better File uploads management.
* Better logs management.
* [Syslog logging](https://blog.nintechnet.com/syslog-logging-with-ninjafirewall/ "Syslog logging").

[Learn more](https://nintechnet.com/ninjafirewall/wp-edition/ "") about the WP+ Edition unique features. [Compare](https://nintechnet.com/ninjafirewall/wp-edition/?comparison "") the WP and WP+ Editions.


= Requirements =

* WordPress 3.7+
* Admin/Superadmin with `manage_options` + `unfiltered_html capabilities`.
* PHP 5.5+, PHP 7.x
* MySQL or MariaDB with MySQLi extension
* Apache / Nginx / LiteSpeed / Openlitespeed compatible
* Unix-like operating systems only (Linux, BSD etc). NinjaFirewall is **NOT** compatible with Microsoft Windows.

== Frequently Asked Questions ==

= Why is NinjaFirewall different from other security plugins for WordPress ? =

NinjaFirewall stands between the attacker and WordPress. It can filter requests before they reach your blog and any of its plugins. This is how it works :

`Visitor -> HTTP server -> PHP -> NinjaFirewall #1 -> WordPress -> NinjaFirewall #2 -> Plugins & Themes -> WordPress exit -> NinjaFirewall #3`

And this is how all WordPress plugins work :

`Visitor > HTTP server > PHP > WordPress > Plugins -> WordPress exit`


Unlike other security plugins, it will protect all PHP scripts, including those that aren't part of the WordPress package.

= How powerful is NinjaFirewall? =
NinjaFirewall includes a very powerful filtering engine which can detect Web Application Firewall evasion techniques and obfuscation tactics used by hackers, as well as support and decode a large set of encodings. See our blog for a full description: [An introduction to NinjaFirewall 3.0 filtering engine](https://blog.nintechnet.com/introduction-to-ninjafirewall-filtering-engine/ "").

= Do I need root privileges to install NinjaFirewall ? =

NinjaFirewall does not require any root privilege and is fully compatible with shared hosting accounts. You can install it from your WordPress admin console, just like a regular plugin.


= Does it work with Nginx ? =

NinjaFirewall works with Nginx and others Unix-based HTTP servers (Apache, LiteSpeed etc). Its installer will detect it.

= Do I need to alter my PHP scripts ? =

You do not need to make any modifications to your scripts. NinjaFirewall hooks all requests before they reach your scripts. It will even work with encoded scripts (ionCube, ZendGuard, SourceGuardian etc).

= I moved my wp-config.php file to another directory. Will it work with NinjaFirewall ? =

NinjaFirewall will look for the wp-config.php script in the current folder or, if it cannot find it, in the parent folder.

= Will NinjaFirewall detect the correct IP of my visitors if I am behind a CDN service like Cloudflare ? =

You can use an optional configuration file to tell NinjaFirewall which IP to use. Please [follow these steps](https://nintechnet.com/ninjafirewall/wp-edition/help/?htninja "").

= Will it slow down my site ? =

Your visitors will not notice any difference with or without NinjaFirewall. From WordPress administration console, you can click "NinjaFirewall > Status" menu to see the benchmarks and statistics (the fastest, slowest and average time per request). NinjaFirewall is very fast, optimised, compact, requires very low system resources and [outperforms all other security plugins](https://blog.nintechnet.com/wordpress-brute-force-attack-detection-plugins-comparison/ "").
By blocking dangerous requests and bots before WordPress is loaded, it will save bandwidth and reduce server load.

= Is there any Microsoft Windows version ? =

NinjaFirewall works on Unix-like servers only. There is no Microsoft Windows version and we do not expect to release any.


== Installation ==

1. Upload `ninjafirewall` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Plugin settings are located in 'NinjaFirewall' menu.

== Screenshots ==

1. Overview page.
2. Statistics and benchmarks page.
3. Options page.
4. Policies pages 1/3: NinjaFirewall has a large list of powerful and unique policies that you can tweak accordingly to your needs.
5. Policies pages 2/3: NinjaFirewall has a large list of powerful and unique policies that you can tweak accordingly to your needs.
6. Policies pages 3/3: NinjaFirewall has a large list of powerful and unique policies that you can tweak accordingly to your needs.
7. File Guard: this is a totally unique feature, because it can detect, in real-time, any access to a PHP file that was recently modified or created, and alert you about this.
8. File Check: lets you perform file integrity monitoring upon request or on a specific interval (hourly, twicedaily, daily).
9. Event notifications can alert you by email on specific events triggered within your blog.
10. Login page protection: the fastest and most efficient brute-force attack protection for WordPress.
11. Firewall Log.
12. Live Log: lets you watch your website traffic in real time. It is fast, light and it does not affect your server load.
13. Rules Editor.
14. Security rules updates.
15. Contextual help.
16. Dashboard widget.

== Changelog ==

Need more security? Take the time to explore our supercharged Premium edition: [NinjaFirewall WP+ Edition](https://nintechnet.com/ninjafirewall/wp-edition/?comparison)

= 4.2.2 =

* WP+ Edition (Premium): NinjaFirewall can now scan ZIP archives. If you have enabled the "Allow uploads, but block dangerous files" firewall policy, you can also enable the "Apply to ZIP archives file contents" option so that the firewall will extract and scan the files found in ZIP archives. See "Firewall Policies > Basic Policies > File Uploads > Apply to ZIP archives file contents".
* Added "preload" to the Strict-Transport-Security policy (HSTS) and the "max-age" value was increased up to 2 years (this is the recommended value for preload) in the "Firewall Policies > Advanced Policies > HTTP response headers" section.
* The daily report will try to prevent WP Cron to send it twice on blogs that may have an issue with the task scheduler.
* Fixed an issue with the captcha protection: some plugins are wrongly redirecting HTTP requests to get the favicon.ico file to the login page and thus trigger the firewall protection.
* Better handling of the backslash character in the database password field.
* Fixed potential "Call to a member function get_error_message on null" PHP error when checking security updates.
* You can change the length of the payload that NinjaFirewall writes to its log, by defining the `NFW_MAXPAYLOAD` constant in the .htninja file. By default, the firewall will write up to 200 characters to the log.
* The dashboard widget will cache the data for 30 minutes.
* The login notification hook has a higher priority so that it will always be triggered before two-factor authentication plugins. The priority can be changed in the wp-config.php or .htninja file by defining the `NFW_LOGINHOOK` constant (current value is "-999999999", previous one was "999").
* When creating a snapshot, File Check will exclude the Ninjascanner's cache folder if it is installed on the blog.
* Many additional small fixes and adjustments.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.

= 4.2.1 =

* Fixed a bug introduced in version 4.2 where a user such as an editor could potentially be blocked while editing a post owned by another user.

= 4.2 =

* Added a new policy to block attempts to publish or edit a published page/post by suspicious users. This feature can be very useful to protect against attacks where hackers (authenticated or not) try to exploit zero-day vulnerabilities to inject code into posts and pages on the blog. It is disabled by default and can be enabled from the "Firewall Policies > Basic Policies > General > Block attempts to publish or edit a published post by users who do not have the right capabilities" menu.
* Added a new policy to protect against username enumeration through the blog RSS feed. See "Firewall Policies > Basic Policies > Protect against username enumeration > Through the blog feed".
* Added a security news feed below NinjaFirewall's widget in the WordPress Dashboard. It can be configured (or even removed) from the "Firewall Options > Miscellaneous > Dashboard Widget" menu.
* Added a hook to remove all potential and annoying admin notices from third-party themes or plugins on every page of NinjaFirewall in the backend.
* Fixed a bug where some firewall policies were reset to their default values when reimporting the user configuration.
* Fixed a bug in the "Statistics" page where the threats percentage numbers were missing beside the three graphs.
* Fixed a bug with language files: when a user selected a specific language, NinjaFirewall was still loading the language file defined in the blog settings page.
* Many small fixes and adjustments.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.

= 4.1.1 =

* Improved the Full WAF installer when the server is running Litespeed or OpenLitespeed.
* Fixed a potential "undefined constant NFW_IS_HTTPS" PHP warning when using the ".htninja" script with the WP Edition.
* Fixed a potential issue in a multisite environment when running the firewall in WordPress WAF mode: the main site and a child site configuration could be out of sync.
* Many small fixes and adjustments.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.

= 4.1 =

* Added a new feature that will alert you by email if there were an important security update available for your themes, plugins or WordPress. It is enabled by default and can be found in the "Event Notifications > Security updates > Send me an alert whenever an important security update is available for a plugin, theme or WordPress".
* Fixed an issue with the "Block user accounts creation" policy: when using the WordPress "Lost your password" link, some users were wrongly blocked.
* On old PHP installations (<5.4.8), it is now possible to update the security rules: NinjaFirewall will not verify their digital signature anymore because of the missing OPENSSL_ALGO_SHA256 algo required by the openssl_verify function.
* Fixed "Date Range Processed" wrong timezone in the daily report.
* The contextual help was reformatted and is now easier to read.
* Added a dismissible welcome banner to the "Dashboard" page to explain how to use the contextual help.
* Many small fixes and adjustments.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.

= 4.0.6 =

* The option to detect and block attemtps to gain administrative privileges can now be turned off from the admin dashboard. See "Firewall Policies > Basic Policies > General > Block attempts to gain administrative privileges".
* Added some code to prevent users who have a caching plugin configured to cache wp-admin requests, from receiving many empty "Database changes detected" email notifications. Note that if you're using a caching plugin, we don't recommend to enable objects caching in the admin back-end because it can have bad side effects.
* Several small fixes and adjustments (UI, CSS, JS and PHP code).
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.

= 4.0.5 =

* The "Event Notifications" code was rewritten from scratch.
* The "Full WAF" installer will rely on the `
get_home_path` function rather than the `ABSPATH` constant in order to better detect if WordPress was installed into its own directory.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.
* Small fixes and adjustments.

= 4.0.4 =

* Improved firewall engine: Fixed a bug in the HTML entities decoder and added ES6 unicode detection and decoding.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.

= 4.0.3 =

We have simplified the menu structure and reduced the total number of menuitems from 15 to 10 (WP Edition) and from 19 to 12 (WP+ Edition):

* New menuitem: "Dashboard". It includes the former "Overview", "Statistics" and "About". In the premium WP+ Edition, it also includes "License".
* New menuitem: "Monitoring". It includes "File Guard" and "File Check". In the premium WP+ Edition, it also includes "Web Filter".
* New menuitem: "Logs". It includes "Firewall Log" and "Live Log". In the premium WP+ Edition, it also includes "Centralized Logging".
* New menuitem: "Security Rules". It includes "Rules Updates" and "Rules Editor".
* Fixed a potential "Undefined index: size" PHP notice.
* Fixed missing CSS on the Login Protection page input fields.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.
* Small fixes and adjustments.

= 4.0.2 =

* Added a new policy to enable the "SameSite" flag on cookies in order to protect against cross-site request forgery (CSRF) attacks. See "Firewall Policies > Advanced Policies > HTTP response headers > Force SameSite flag on all cookies".
* Fixed a bug in multisite installations: when additional superadmin users were created, they were not whitelisted by the firewall because WordPress does not assign them a "capabilities" meta_key in the database.
* Fixed a bug in the firewall engine sanitizing function: when dealing with an empty string, the function was returning NULL rather than returning the empty value.
* Fixed a bug in the "Login Protection" menu: after changing the "GET/POST" options, reloading the page reset them to the default value.
* Fixed a "Undefined variable: phpini" PHP notice in the uninstaller.
* Improved the code used to detect if another instance of the firewall is running in a parent directory.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.
* Several small fixes and adjustments.

= 4.0.1 =

* Fixed a bug where it was not possible to disable the "Strict-Transport-Security HTSC" advanced policy.
* Fixed a potential "Undefined index: size" PHP notice that could occur during uploads.
* Fixed a bug where the firewall log was wrongly displaying "DEBUG_ON" instead of "INFO" in the "Level" column.
* Fixed a potential "The plugin does not have a valid header" error message when activating NinjaFirewall. On some installations, WordPress was not loading the right file.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.

= 4.0 =

* Improved NinjaFirewall overall interface and pages layout; added some simple toggle switches to replace radio buttons, better handling of error messages, cleaned up useless code etc.
* All JavaScript code was 100% rewritten from scratch, including all features that rely on it (e.g., "Live Log" etc).
* The installer was removed: When activating NinjaFirewall for the first time, it will automatically install itself in "WordPress WAF" mode. To upgrade to "Full WAF" mode, simply click on the corresponding link in the “Overview” page. The process is now very straightforward! A "sandbox" was added too, so that if there were a crash during the process, NinjaFirewall would undo the changes and warn the user.
* When NinjaFirewall is running in "Full WAF" mode, if the PHP INI file used to load its firewall was deleted by mistake, it would automatically fallback to "WordPress WAF" mode so that the blog will remain protected.
* Fixed the admin login page bug where some users had to enter their credentials twice.
* The "Block the DOCUMENT_ROOT server variable in HTTP request" policy will not be enabled by default with new installations of NinjaFirewall.
* NinjaFirewall will not block users with author and editor role while they are editing a post or page using either the Classic or the new Block Editor.
* Added Openlitespeed detection to the "Full WAF" mode installer.
* WP+ Edition (Premium): The "Access Control" pages interface was simplified: it now uses simple textarea elements where you can copy/paste your data (URL, IP, Bot and User Input) very easily. The "Geolocation" page was simplified too.
* WP+ Edition (Premium): In addition to an IP address or CIDR, you can now also enter an AS number (Autonomous System number). This new feature is very helpful if you want to allow or block all IPs from an ISP or hosting company: just enter their AS number instead of hundreds of IP addresses. Syntax is "AS" + the number, e.g. "AS12345". See "Access Control > IP Access Control".
* WP+ Edition (Premium): You can now add an IP to the Access Control blacklist or whitelist from the "Firewall Log" page by entering the IP in the input field below the log textarea.
* WP+ Edition (Premium): When running in "WordPress WAF" mode, NinjaFirewall will automatically disable the shared memory option, because that feature is only useful when used in "Full WAF" mode (there is no benefit at all to run it in "WordPress WAF" mode).
* WP+ Edition (Premium): Fixed a bug where the ISO 3166 country code was not found when using an external PHP Variable instead of the built-in GeoIP database.
* WP+ Edition (Premium): Improved malicious SVG files detection.
* WP+ Edition (Premium): Updated IPv4/IPv6/ASN GeoIP databases.
* Many fixes and adjustments.


= 3.9.1 =

* Fixed potential "Nesting level too deep – recursive dependency" error message in the backend.
* You can select the verbosity of the PHP backtrace attached to email notifications: low, medium or high verbosity. See "Event Notification > PHP backtrace".
* Added a new policy to protect the `admin-ajax.php` script against malicious bots and scanners. See "Firewall Policy > Basic Policies > WordPress AJAX".
* WP+ Edition (Premium): NinjaFirewall can check for security rules updates as often as every 15 minutes (versus one hour for the free WP Edition). See "Rules Update > Check for updates".
* WP+ Edition (Premium): Added a new access control section: "User Input Access Control". It can be used to ignore or block specific user input (GET, POST and COOKIE). See "Access Control > User Input".
* WP+ Edition (Premium): Role-based Access Control has been improved: it will display all user roles available on the blog, including custom ones from all third-party applications (e.g., WooCommerce, bbPress etc) so that they can be whitelisted too.
* WP+ Edition (Premium): The `/` character is now allowed in the Bot Access Control.
* Improved user_roles protection to prevent blocking third-party applications than may modify it when a non-administrator user is logged-in.
* Many small fixes, adjustments and improvements.

= 3.8.4 =

* Fixed a potential "Call to undefined function wp_get_current_user()" error that may occur with plugins such as RevSlider.

= 3.8.3 =

* NinjaFirewall will attach a PHP backtrace to some important email notifications (see "Event Notifications > PHP backtrace").
* Fixed an issue where the firewall could not connect to the database if its password contained an escaped single quote.
* Fixed an issue where it was not possible to use the WordPress plugin and theme editor. This is due to a bug introduced in WordPress 4.9.2 which does not play well with PHP sessions (see https://core.trac.wordpress.org/ticket/43358).
* The firewall will detect if the PHP mysqli extension is missing or is not loaded and will warn the admin in the backend.
* Improved TLS detection for servers that are behind a load-balancer or reverse proxy.
* Various fixes and adjustments.

= 3.8.2 =

* Improved the firewall engine to detect shell command obfuscation tricks using uninitialized variables (e.g. `?a=cat$foo $foo/etc/$foo/passwd$foo`).
* Added a policy to disable the fatal error handler introduced in WordPress 5.1. See "Firewall Policies > Basic Policies > Disable the fatal error handler".
* Disabled the firewall when running WP-CLI.
* If the firewall settings were corrupted, the garbage collector would restore the last known good configuration backup. If there is no backup available, it will restore its default settings so that NinjaFirewall will keep working and protecting the site.
* Various fixes and adjustments.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 3.8.1 =

* Fixed a potential issue where the firewall configuration could be corrupted when attempting to restore a backup from the Firewall Options page right after updating to version 3.8.

= 3.8 =

* A lot of code was cleaned-up, fixed and improved as well as the whole files structure of the plugin.
* Increased the height of the textarea in the "Firewall Log" and "Live Log" pages.
* Fixed an issue where some caching plugins could mess with the database monitoring process which could return erroneous results.
* Improved the database monitoring process for blogs that have a huge amount of rows in the "wp_usermeta" table.
* The "File Check" notification will include the number of new, modified and deleted files in the body of the email.
* [WP+ Edition] Added an option to disable login alerts for users whose IP address is whitelisted. See "NinjaFirewall > Event Notifications > Do not send a notification if the user is in the IP Access Control whitelist".
* [WP+ Edition] Fixed an issue where, after deleting the log, it was once again deleted if the page was reloaded in the browser.
* Fixed an issue where any `auto_prepend_file` directive left by another application in the .htaccess was not removed before starting the installation of NinjaFirewall on servers running LiteSpeed or Apache + mod_php.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 3.7.2 =

* Added a new option to block any attempt by non-admin users to modify some important WordPress settings (e.g., by exploiting a vulnerability, using a backdoor etc). See "Firewall Policies > Basic Policies > Block attempts to modify important WordPress settings".
* [WP+ Edition] Fixed a bug in the "Web Filter" callback function where the firewall was writing its log in the `/wp-content/` folder instead of `/wp-content/nfwlog/`.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Small fixes and adjustments.

= 3.7.1 =

* Fixed two potential PHP notices in the firewall on systems running PHP 7.2+.
* Added a function to the firewall engine to detect octal-encoded values that could be used as WAF evasion techniques (e.g. `?foo=\050\141\154\145\162\164\051\050\170\163\163\051`).
* If you have a complex database setup that NinjaFirewall is not able to properly retrieve, you can give it a MySQLi link identifier in the `.htninja` instead. See "Giving NinjaFirewall a MySQLi link identifier" at http://nin.link/htninja/ for more details.
* Added right to left language support.
* Improved HTTPS detection in the firewall.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Fixed potential "ini_set" PHP warning when a session was started by another plugin.
* Many small fixes and adjustments.

= 3.7 =

* Added a new option to the "Live Log" page: you can apply filters in order to include or exclude files and folders. See "Live Log > Inclusion and exclusion filters".
* Added a new option to the "Firewall Options" page: NinjaFirewall will automatically backup its configuration (options, policies and rules) everyday for the last 5 days so that you can restore its configuration to an earlier date if needed. See "Firewall Options > Configuration backup".
* [WP+ Edition] The "IP Access Control" whitelist and blacklist can now support CIDR notation for IPv4 and IPv6 (e.g., 66.155.0.0/17, 2c0f:f248::/32).
* Added a warning to the "Login Protection" page if Jetpack is installed and the XML-RPC API protection is activated.
* Added a notice to the "Login Protection" page to remind that the "Authentication log" option can only work when the protection is set to "Yes, if under attack".
* Fixed a potential "401 Unauthorized" HTTP response when attempting to access the XMLRPC API using a non-POST method.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.

= 3.6.8 =

* Fixed an issue where third-party plugins making use of PHP session but without properly checking the status of the current session could behave erratically.

= 3.6.7 =

* Added a new option to block any attempt (e.g., exploiting a vulnerability, using a backdoor etc) to create a user account. See "Firewall Policies > Basic Policies > Block user accounts creation".
* The "Daily Activity Report" will include the domain name of the blog in the email subject.
* Fixed a potential "Zend OPcache API " warning message when saving the "Login Protection" options.
* The "Updates" menu was renamed to "Rules Update".
* Improved PHP session handling.
* Fixed a potential "Call to a member function close() on null" PHP error in the firewall.
* [WP+ Edition] Fixed a bug in the "Web Filter" page where the button to submit the HTML form was not visible.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes and adjustments.

= 3.6.6 =

* The "Statistics" page and dashboard widget will display the same values. Previously, the total of blocked threats displayed in the "Statistics" page was reset if the corresponding firewall log was deleted.
* Fixed a bug in the Garbage Collector: in some cases, the firewall log was deleted a few days later than expected.
* The Garbage Collector will still be able to run even if WP-Cron is disabled.
* Fixed an issue introduced in WordPress 4.9.6: NinjaFirewall was not visible in the list of plugins when using WP-CLI. Note that if you want to enable/disable it from WP-CLI you will need to append the `--user` switch to your command (e.g., `$ wp plugin activate nfwplus --user=some_admin`).
* Minor fixes.

= 3.6.5 =

* The brute-force protection will not be triggered when users click on the email confirmation link, which points to the wp-login.php script, sent by the new WordPress "Export Personal Data" feature.
* The firewall will automatically detect if the blog runs on an old multisite installation where the main site options table is named "wp_1_options" instead of "wp_options".

= 3.6.4 =

* Fixed potential "session_status()" error with old PHP installations.

= 3.6.3 =

* Added the "Referrer-Policy" header (see "Firewall Policies > Advanced Policies > HTTP response headers").
* Added the "418 I'm a teapot" HTTP error code (see "Firewall Options > HTTP error code to return").
* Modified how PHP sessions were handled in order to prevent conflicts with third-party applications that may attempt to start a session without checking if one was already started (e.g., Piwik/Zend Framework, phpMyadmin).
* Added more options to the X-XSS-Protection header; it can be set to "0", "1", "1; mode=block" or disabled (see "Firewall Policies > Advanced Policies > HTTP response headers").
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.

= 3.6.2 =

* Added an option to automatically delete the firewall log(s) after a period of time (see "NinjaFirewall > Firewall Log > Auto-delete log").
* Added an option to enter the admin email address during the installation process.
* [WP+ Edition] The "Access Control" page was split into 5 tabs: "General", "Geolocation", "IP Access Control", "URL Access Control" and "Bot Access Control".
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Many small fixes throughout the code: bugs, typos, contextual help corrections, various adjustments etc.

= 3.6.1 =

* Added "IP Anonymization" option. It will anonymize IP addresses in the firewall log by removing their last 3 characters. See "NinjaFirewall > Firewall Options > IP Anonymization".
* Fixed a bug where the "Login Protection" wrongly applied to password protected pages.
* Fixed a bug where the garbage collector cron job was not deleted when NinjaFirewall was disabled.
* Added a warning that NinjaFirewall requires `unfiltered_html` capability when attempting to activate it.
* [WP+ Edition] The "Uploads > Allow, but block scripts, ELF and system files" firewall policy was renamed to "Allow, but block dangerous files" and will also block dangerous SVG files. Therefore, the complete list of blocked files is now: scripts (PHP, CGI, Ruby, Python, bash/shell), C/C++ source code, binaries (MZ/PE/NE and ELF formats), system files (.htaccess, .htpasswd and PHP INI) and SVG files containing Javascript/XML events.
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.

= v3.6 =

* Important: We have removed the "Anti-Malware" option from NinjaFirewall. Instead, we have now a brand new and much better antivirus plugin: NinjaScanner. You can download it from wordpress.org: https://wordpress.org/plugins/ninjascanner/
* [WP+ Edition] Fixed a bug where IPs that were whitelisted in the "Access Control" page could not connect to the REST API if its access was disabled in the "Firewall Policies".
* [WP+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Minor fixes.

