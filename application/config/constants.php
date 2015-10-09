<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


define('SUCCESSFULL',1);
define('ERROR_SYSTEM',0);
define('ITEM_PER_PAGE',20);
define('ITEM_PER_PAGE_32',32);
define('ITEM_PER_PAGE_8',8);
define('STATUS_HIDE',0);
define('STATUS_SHOW',1);
define('STATUS_WAIT_FOR_APPROVE',10);

define('DEFAULT_CACHE_TIME_MINUTE', 30);//30 minutes

define('SERIES_STATUS_ONGOING',0);
define('SERIES_STATUS_COMPLETE',1);

define('THUMB_SIZE_VIDEO_320','320x180');
define('UPLOAD_PATH' ,"uploads/");
define('VIDEO_IMAGE_ORIGINAL_PATH' ,"uploads/video/");
define('SERIE_IMAGE_THUMBNAIL_PATH' ,"uploads/series/");
define('EDITOR_IMAGE_THUMBNAIL_PATH' ,"uploads/editor/");


define("VIDEO_TYPE_360",		1);
define("VIDEO_TYPE_480",		2);
define("VIDEO_TYPE_720",		3);

define("SERVER_TYPE_COOL",		1);
define("SERVER_TYPE_STANDARD", 2);
define("SERVER_TYPE_MP4", 3);
define("SERVER_TYPE_HD", 4);
define("SERVER_TYPE_SERVER1", 5);

define("COUNTRY_KOREA",		1);
define("COUNTRY_JAPAN",		2);
define("COUNTRY_TAIWAN",		3);
define("COUNTRY_HONGKONG",		4);
define("COUNTRY_CHINA",		5);

define('IMPORT_TYPE_VIDEO', 1);
define('IMPORT_TYPE_SERIES', 2);
define('IMPORT_TYPE_COUNTRY', 3);

define('DRAMA_COOL_SITE_URL', 'http://www.dramacool.com');
define('DRAMA_LIST_BY_CHAR_URL_PATTERN', 'http://www.dramacool.com/drama-list/char-start-%s.html');
define('MOVIE_LIST_BY_CHAR_URL_PATTERN', 'http://www.dramacool.com/drama-list/char-start-%s.html');
define('SHOW_LIST_BY_CHAR_URL_PATTERN', 'http://www.dramacool.com/kshow/char-start-%s.html');


define('VIDEO_TYPE_DRAMA', 1);
define('VIDEO_TYPE_MOVIE', 2);
define('VIDEO_TYPE_SHOW', 3);
define('DEFAULT_TEXT_SELECTBOX','-- All --');

define("ADMIN_THEME_DIR", "themes/admin");
define("FRONTEND_THEME_DIR", "themes/frontend");

define("SITE_NAME", "Drama for everyone");
define("DEFAULT_DESCRIPTION", "watch drama online, watch movie online, watch show online");
define("DEFAULT_KEYWORD", "watch, drama, show, movie, online");
define("DEFAULT_TITLE", SITE_NAME);

define("FACEBOOK_APP_ID", '518427084990365');

define("EXTRA_DESCRIPTION", ' Dramalist will always be the first to have the episode so please add us on <a title="Dramalist" href="https://www.facebook.com/KDramaShare">Facebook</a> for update! Enjoy');