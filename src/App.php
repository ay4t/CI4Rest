<?php
/*
 * File: App.php
 * Project: src
 * Created Date: Th Mar 2023
 * Author: Ayatulloh Ahad R
 * Email: ayatulloh@indiega.net
 * Phone: 085791555506
 * -------------------------
 * Last Modified: Thu Mar 02 2023
 * Modified By: Ayatulloh Ahad R
 * -------------------------
 * Copyright (c) 2023 Indiega Network 

 * -------------------------
 * HISTORY:
 * Date      	By	Comments 

 * ----------	---	---------------------------------------------------------
 * 
 * HARAP DIPERHATIKAN:
 * --- PENGATURAN YANG DISETTING DI BAWAH INI BERSIFAT GLOBAL ---
 * 1. Setiap pengaturan akan berlaku di semua controller yang menggunakan extends RestController sebagai pengaturan default. 
 * 2. Anda dapat override / replace pengaturan pada method atau controller anda masing-masing dengan pengaturan yang berbeda-beda
 * 3. Semua pengaturan dibawah ini dapat Anda setting secara global tanpa edit config ini pada file .env Anda ( E.g: app.force_https = true )
 * 
 */

namespace Ay4t\CI4Rest;

class App extends \Config\App
{    
    /*
    |--------------------------------------------------------------------------
    | HTTP protocol
    |--------------------------------------------------------------------------
    |
    | Set to force the use of HTTPS for REST API calls
    |
    */
    public $force_https =  false;

    /*
    |--------------------------------------------------------------------------
    | REST Output Format
    |--------------------------------------------------------------------------
    |
    | The default format of the response
    |
    | 'based_controller'
    | 'json':       Uses json_encode().
    | 'xml':        Uses simplexml_load_string()
    |
    */
    public $rest_default_format =  'based_controller';

    /*
    |--------------------------------------------------------------------------
    | REST Supported Output Formats
    |--------------------------------------------------------------------------
    |
    | The following setting contains a list of the supported/allowed formats.
    | You may remove those formats that you don't want to use.
    | If the default format public $rest_default_format'] is missing within
    | public $rest_supported_formats'], it will be added silently during
    | REST_Controller initialization.
    |
    */
    public $rest_supported_formats =  [
        'json',
        'xml',
    ];

    /*
    |--------------------------------------------------------------------------
    | REST Status Field Name
    |--------------------------------------------------------------------------
    |
    | The field name for the status inside the response
    |
    */
    public $rest_status_field_name =  'status';

    /*
    |--------------------------------------------------------------------------
    | REST Message Field Name
    |--------------------------------------------------------------------------
    |
    | The field name for the message inside the response
    |
    */
    public $rest_message_field_name =  'message';

    /*
    |--------------------------------------------------------------------------
    | Enable Emulate Request
    |--------------------------------------------------------------------------
    |
    | Should we enable emulation of the request (e.g. used in Mootools request)
    | coming soon
    |
    */
    // public $enable_emulate_request =  true;

    /*
    |--------------------------------------------------------------------------
    | REST Realm
    |--------------------------------------------------------------------------
    |
    | Name of the password protected REST API displayed on login dialogs
    |
    | e.g: My Secret REST API
    |
    */
    public $rest_realm =  'REST API';

    /*
    |--------------------------------------------------------------------------
    | REST Login
    |--------------------------------------------------------------------------
    |
    | Set to specify the REST API requires to be logged in
    |
    | FALSE     
    | No login required
    |
    | 'config_auth'
    | login restful menggunakan username dan password yang harus Anda seting di bagian " $rest_valid_logins = [ 'username' => 'password' ] "
    |
    | 'JWT'
    | - Untuk menggunakan otentikasi ini Anda harus menginstall library JWT dengan perintah "composer require firebase/php-jwt"
    | - Bearer header token dengan JWT untuk semua request yang menggunakan parent RestController.
    | - Anda juga dapat menggunakan fitur Otentikasi JWT pada setiap method pada tiap-tiap kontroller yang berbeda-beda dengan cara:
    |   tambahkan $this->config->rest_auth = 'JWT'; pada method yang hendak Anda seting methodnya harus otentikasi JWT
    |
    */
    public $rest_auth               =  false;

    public $rest_JWT_secret         =  '';
    public $rest_JWT_timetolive     =  3600;

    /*
    |--------------------------------------------------------------------------
    | REST allowed method
    |--------------------------------------------------------------------------
    |
    | daftar method yang akan di-izinkan pada saat request
    |
    */
    public $rest_allowed_method =  [
        'POST',
        'GET',
        'PUT',
        'DELETE',
    ];

    /*
    |--------------------------------------------------------------------------
    | REST Login Usernames
    |--------------------------------------------------------------------------
    |
    | Array of usernames and passwords for login, if ldap is configured this is ignored
    |
    */
    public $rest_valid_logins =  ['admin' => '1234'];

    /*
    |--------------------------------------------------------------------------
    | Global IP White-listing
    |--------------------------------------------------------------------------
    |
    | Limit connections to your REST server to White-listed IP addresses
    |
    | Usage:
    | 1. Set to TRUE and select an auth option for extreme security (client's IP
    |    address must be in white-list and they must also log in)
    | 2. Set to TRUE with auth set to FALSE to allow White-listed IPs access with no login
    | 3. Set to FALSE but set 'auth_override_class_method' to 'white-list' to
    |    restrict certain methods to IPs in your white-list
    |
    */
    public $rest_ip_whitelist_enabled =  false;

    /*
    |--------------------------------------------------------------------------
    | REST IP White-list
    |--------------------------------------------------------------------------
    |
    | Limit connections to your REST server with a array
    | list of IP addresses
    |
    | e.g: ['123.456.789.0', '987.654.32.1']
    |
    | 127.0.0.1 and 0.0.0.0 are allowed by default
    |
    */
    public $rest_ip_whitelist =  [];

    /*
    |--------------------------------------------------------------------------
    | Global IP Blacklisting
    |--------------------------------------------------------------------------
    |
    | Prevent connections to the REST server from blacklisted IP addresses
    |
    | Usage:
    | 1. Set to TRUE and add any IP address to 'rest_ip_blacklist'
    |
    */
    public $rest_ip_blacklist_enabled =  false;

    /*
    |--------------------------------------------------------------------------
    | REST IP Blacklist
    |--------------------------------------------------------------------------
    |
    | Prevent connections from the following IP addresses
    |
    | e.g: '123.456.789.0, 987.654.32.1'
    |
    */
    public $rest_ip_blacklist =  [];

    /*
    |--------------------------------------------------------------------------
    | REST Database Group
    |--------------------------------------------------------------------------
    |
    | Connect to a database group for keys, logging, etc. It will only connect
    | if you have any of these features enabled
    |
    */
    public $rest_database_group =  'default';

    /*
    |--------------------------------------------------------------------------
    | REST Enable Keys
    |--------------------------------------------------------------------------
    |
    | When set to TRUE, the REST API will look for a column name called 'key'.
    | If no key is provided, the request will result in an error. To override the
    | column name see 'rest_key_column'
    |
    | Default table schema:
    |   CREATE TABLE `tb_keys` (
    |       `id` INT(11) NOT NULL AUTO_INCREMENT,
    |       `user_id` INT(11) NOT NULL,
    |       `key` VARCHAR(40) NOT NULL,
    |       `level` INT(2) NOT NULL,
    |       `ignore_limits` TINYINT(1) NOT NULL DEFAULT '0',
    |       `is_private_key` TINYINT(1)  NOT NULL DEFAULT '0',
    |       `ip_addresses` TEXT NULL DEFAULT NULL,
    |       `date_created` INT(11) NOT NULL,
    |       PRIMARY KEY (`id`)
    |   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    |
    */
    public $rest_enable_keys =  false;
    
    /*
    |--------------------------------------------------------------------------
    | REST API Keys Table Name
    |--------------------------------------------------------------------------
    |
    | The table name in your database that stores API keys
    |
    */
    public $rest_keys_table =  'tb_keys';

    /*
    |--------------------------------------------------------------------------
    | REST Table Key Column Name
    |--------------------------------------------------------------------------
    |
    | If not using the default table schema in 'rest_enable_keys', specify the
    | column name to match e.g. my_key
    |
    */
    public $rest_key_column =  'key';

    /*
    |--------------------------------------------------------------------------
    | REST API Limits method
    |--------------------------------------------------------------------------
    |
    | Specify the method used to limit the API calls
    |
    | Available methods are :
    | public $rest_limits_method =  'IP_ADDRESS'; // Put a limit per ip address
    | public $rest_limits_method =  'API_KEY'; // Put a limit per api key
    | public $rest_limits_method =  'METHOD_NAME'; // Put a limit on method calls
    | public $rest_limits_method =  'ROUTED_URL';  // Put a limit on the routed URL
    |
    */
    public $rest_limits_method =  'ROUTED_URL';

    /*
    |--------------------------------------------------------------------------
    | REST Key Length
    |--------------------------------------------------------------------------
    |
    | Length of the created keys. Check your default database schema on the
    | maximum length allowed
    |
    | Note: The maximum length is 40
    |
    */
    public $rest_key_length =  40;

    /*
    |--------------------------------------------------------------------------
    | REST API Key Variable
    |--------------------------------------------------------------------------
    |
    | Custom header to specify the API key
    | Note: Custom headers with the X- prefix are deprecated as of
    | 2012/06/12. See RFC 6648 specification for more details
    |
    */
    public $rest_key_name =  'X-API-KEY';

    /*
    |--------------------------------------------------------------------------
    | REST Enable Logging
    |--------------------------------------------------------------------------
    |
    | When set to TRUE, the REST API will log actions based on the column names 'key', 'date',
    | 'time' and 'ip_address'. This is a general rule that can be overridden in the
    | public $this->method array for each controller
    |
    | Default table schema:
    |   CREATE TABLE `logs` (
    |       `id` INT(11) NOT NULL AUTO_INCREMENT,
    |       `uri` VARCHAR(255) NOT NULL,
    |       `method` VARCHAR(6) NOT NULL,
    |       `params` TEXT DEFAULT NULL,
    |       `api_key` VARCHAR(40) NOT NULL,
    |       `ip_address` VARCHAR(45) NOT NULL,
    |       `time` INT(11) NOT NULL,
    |       `rtime` FLOAT DEFAULT NULL,
    |       `authorized` VARCHAR(1) NOT NULL,
    |       `response_code` smallint(3) DEFAULT '0',
    |       PRIMARY KEY (`id`)
    |   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    |
    */
    public $rest_enable_logging =  false;

    /*
    |--------------------------------------------------------------------------
    | REST API Logs Table Name
    |--------------------------------------------------------------------------
    |
    | If not using the default table schema in 'rest_enable_logging', specify the
    | table name to match e.g. my_logs
    |
    */
    public $rest_logs_table =  'logs';

    /*
    |--------------------------------------------------------------------------
    | REST Method Access Control
    |--------------------------------------------------------------------------
    | When set to TRUE, the REST API will check the access table to see if
    | the API key can access that controller. 'rest_enable_keys' must be enabled
    | to use this
    |
    | Default table schema:
    |   CREATE TABLE `access` (
    |       `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    |       `key` VARCHAR(40) NOT NULL DEFAULT '',
    |       `all_access` TINYINT(1) NOT NULL DEFAULT '0',
    |       `controller` VARCHAR(50) NOT NULL DEFAULT '',
    |       `date_created` DATETIME DEFAULT NULL,
    |       `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    |       PRIMARY KEY (`id`)
    |    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    |
    */
    public $rest_enable_access =  false;

    /*
    |--------------------------------------------------------------------------
    | REST API Access Table Name
    |--------------------------------------------------------------------------
    |
    | If not using the default table schema in 'rest_enable_access', specify the
    | table name to match e.g. my_access
    |
    */
    public $rest_access_table =  'access';

    /*
    |--------------------------------------------------------------------------
    | REST API Param Log Format
    |--------------------------------------------------------------------------
    |
    | When set to TRUE, the REST API log parameters will be stored in the database as JSON
    | Set to FALSE to log as serialized PHP
    |
    */
    public $rest_logs_json_params =  false;

    /*
    |--------------------------------------------------------------------------
    | REST Enable Limits
    |--------------------------------------------------------------------------
    |
    | When set to TRUE, the REST API will count the number of uses of each method
    | by an API key each hour. This is a general rule that can be overridden in the
    | public $this->method array in each controller
    |
    | Default table schema:
    |   CREATE TABLE `limits` (
    |       `id` INT(11) NOT NULL AUTO_INCREMENT,
    |       `uri` VARCHAR(255) NOT NULL,
    |       `count` INT(10) NOT NULL,
    |       `hour_started` INT(11) NOT NULL,
    |       `api_key` VARCHAR(40) NOT NULL,
    |       PRIMARY KEY (`id`)
    |   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    |
    | To specify the limits within the controller's __construct() method, add per-method
    | limits with:
    |
    |       public $this->methods['METHOD_NAME']['limit'] =  [NUM_REQUESTS_PER_HOUR];
    |
    | See application/controllers/api/example.php for examples
    */
    public $rest_enable_limits =  false;

    /*
    |--------------------------------------------------------------------------
    | REST API Limits Table Name
    |--------------------------------------------------------------------------
    |
    | If not using the default table schema in 'rest_enable_limits', specify the
    | table name to match e.g. my_limits
    |
    */
    public $rest_limits_table =  'limits';

    /*
    |--------------------------------------------------------------------------
    | REST AJAX Only
    |--------------------------------------------------------------------------
    |
    | Set to TRUE to allow AJAX requests only. Set to FALSE to accept HTTP requests
    |
    | Note: If set to TRUE and the request is not AJAX, a 505 response with the
    | error message 'Only AJAX requests are accepted.' will be returned.
    |
    | Hint: This is good for production environments
    |
    */
    public $rest_ajax_only =  false;

    /*
    |--------------------------------------------------------------------------
    | REST Language File
    |--------------------------------------------------------------------------
    |
    | Language file to load from the language directory
    | Coming Soon
    |
    */
    public $rest_language =  'english';

    /*
    |--------------------------------------------------------------------------
    | CORS Check
    |--------------------------------------------------------------------------
    |
    | Set to TRUE to enable Cross-Origin Resource Sharing (CORS). Useful if you
    | are hosting your API on a different domain from the application that
    | will access it through a browser
    |
    */
    public $check_cors =  false;

    /*
    |--------------------------------------------------------------------------
    | CORS Allowable Headers
    |--------------------------------------------------------------------------
    |
    | If using CORS checks, set the allowable headers here
    |
    */
    public $allowed_cors_headers =  [
        'Origin',
        'X-Requested-With',
        'Content-Type',
        'Accept',
        'Access-Control-Request-Method',
    ];

    /*
    |--------------------------------------------------------------------------
    | CORS Allowable Methods
    |--------------------------------------------------------------------------
    |
    | If using CORS checks, you can set the methods you want to be allowed
    |
    */
    public $allowed_cors_methods =  [
        'GET',
        'POST',
        'OPTIONS',
        'PUT',
        'PATCH',
        'DELETE',
    ];

    /*
    |--------------------------------------------------------------------------
    | CORS Allow Any Domain
    |--------------------------------------------------------------------------
    |
    | Set to TRUE to enable Cross-Origin Resource Sharing (CORS) from any
    | source domain
    |
    */
    public $allow_any_cors_domain =  true;

    /*
    |--------------------------------------------------------------------------
    | CORS Allowable Domains
    |--------------------------------------------------------------------------
    |
    | Used if public $check_cors'] is set to TRUE and public $allow_any_cors_domain']
    | is set to FALSE. Set all the allowable domains within the array
    |
    | e.g. public $allowed_origins =  ['http://www.example.com', 'https://spa.example.com']
    |
    */
    public $allowed_cors_origins =  [];

    /*
    |--------------------------------------------------------------------------
    | CORS Forced Headers
    |--------------------------------------------------------------------------
    |
    | If using CORS checks, always include the headers and values specified here
    | in the OPTIONS client preflight.
    | Example:
    | public $forced_cors_headers =  [
    |   'Access-Control-Allow-Credentials' => 'true'
    | ];
    |
    | Added because of how Sencha Ext JS framework requires the header
    | Access-Control-Allow-Credentials to be set to true to allow the use of
    | credentials in the REST Proxy.
    | See documentation here:
    | http://docs.sencha.com/extjs/6.5.2/classic/Ext.data.proxy.Rest.html#cfg-withCredentials
    |
    */
    public $forced_cors_headers =  [
        // 'Access-Control-Allow-Credentials' => 'true'
    ];


}
