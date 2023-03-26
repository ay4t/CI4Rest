<?php
/*
 * File: RestController.php
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
 */

namespace Ay4t\Ci4rest;

use Ay4t\Ci4rest\Traits\Security;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class RestController extends ResourceController
{
    use Security;

    /** @return \Ay4t\Ci4rest\App(); */
    protected $config;

    /** @return \Config\Services::request(); */
    protected $requst;

    /** @var bool $rest_ajax_only description */
    protected $rest_ajax_only = false;

    /** @var bool $check_cors description */
    protected $check_cors = false;

    /** @var string */
    protected $use_JWT_refresh_token = false;

    /** @var array */
    protected $rest_response = [];

    /** @var int */
    protected $statusCode = 200;

    /** @return \Config\Database::connect() */
    protected $db;

    public function __construct()
    {
        $this->config               = new \Ay4t\Ci4rest\App();
        $this->db                   = \Config\Database::connect($this->config->rest_database_group, true);
        $this->request              = \Config\Services::request();
        $this->rest_ajax_only       = $this->config->rest_ajax_only;
        $this->check_cors           = $this->config->check_cors;

    }

    /**
     * Fungsi default yang akan digunakan pada request dengan method GET
     * @return CodeIgniter\API\ResponseTrait::respond
     */
    public function index()
    {
        $this->setResponseMessage(true, 'OK');
        $this->initMethod();

        /** tambahkan code disini jika ingin berjalan secara global di semua child class */


        /** filter hanya status dan message saja yang akan tampil di response */
        if(!$this->rest_response[$this->config->rest_status_field_name]){
            $whitelist  = [$this->config->rest_status_field_name, $this->config->rest_message_field_name];
            $filtered   = array_intersect_key( $this->rest_response, array_flip( $whitelist ) );
            return $this->respond($filtered, $this->statusCode);
        }
        
        return $this->respond($this->rest_response, $this->statusCode);
    }

    /**
     * Fungsi default yang akan digunakan pada request dengan method POST
     * @return CodeIgniter\API\ResponseTrait::respond
     */
    public function create()
    {
        $this->setResponseMessage(true, 'OK');
        $this->initMethod();

        /** tambahkan code disini jika ingin berjalan secara global di semua child class */

        /** filter hanya status dan message saja yang akan tampil di response */
        if(!$this->rest_response[$this->config->rest_status_field_name]){
            $whitelist  = [$this->config->rest_status_field_name, $this->config->rest_message_field_name];
            $filtered   = array_intersect_key( $this->rest_response, array_flip( $whitelist ) );
            return $this->respond($filtered, $this->statusCode);
        }
        
        return $this->respond($this->rest_response, $this->statusCode);
    }

    /**
     * initMethod
     * @return this
     */
    private function initMethod()
    {
        $this->useCORS();
        $this->useJWT();
        $this->useOutputFormat();
        $this->useMethodFilter();
        $this->useSecureRequest();
        $this->useOnlyIPWhiteLists();
        $this->useIPBlacklistFilter();
        $this->useOnlyAjax();
        $this->useKey();
        
    }

    private function useKey()
    {
        // jika $this->config->rest_enable_keys = true check table exist
        if($this->config->rest_enable_keys){
            if(!$this->db->tableExists($this->config->rest_keys_table)){
                $this->statusCode = 500;
                $this->setResponseMessage(false, 'Table '.$this->config->rest_keys_table.' not found');
            } else {

                if($this->config->rest_key_column){
                    $key = $this->request->getGet($this->config->rest_key_column);
                    if(empty($key)){
                        $this->statusCode = 403;
                        $this->setResponseMessage(false, 'API key is required');
                    }

                    // check key kedalam table 
                    $query = $this->db->table($this->config->rest_keys_table)
                        ->where($this->config->rest_key_column, $key)
                        ->get()
                        ->getRow();
                    if($query){
                        // jika key ditemukan, cek apakah key tersebut masih aktif
                        if($query->active == 0){
                            $this->statusCode = 403;
                            $this->setResponseMessage(false, 'API key is inactive');
                        }

                        // jika ip_addresses != NULL validate dengan IP address yang request
                        if(!empty($query->ip_addresses)){
                            $ip_addresses = explode(',', $query->ip_addresses);
                            if(!in_array($this->request->getIPAddress(), $ip_addresses)){
                                $this->statusCode = 403;
                                $this->setResponseMessage(false, 'Your IP address is not allowed to access this endpoint');
                            }
                        }
                        
                    } else {
                        $this->statusCode = 403;
                        $this->setResponseMessage(false, 'API key is invalid');
                    }
                }

            }
        }
    }

    private function useCORS()
    {
        if($this->check_cors){
            $allow_any_cors_domain = ($this->config->allow_any_cors_domain) ? '*' : $this->request->getHeaderLine('Origin');
            if(!empty($this->config->allowed_cors_origins)){
                if( !in_array($this->request->getHeaderLine('Origin'), $this->config->allowed_cors_origins) ){
                    $this->statusCode = 403;
                    $this->setResponseMessage(false, 'Your origin is not allowed to access this endpoint');
                }
            }

            $this->response->setHeader('Access-Control-Allow-Origin', $allow_any_cors_domain);
            $this->response->setHeader('Access-Control-Allow-Methods', implode(', ', $this->config->allowed_cors_methods));
            $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Length, Accept-Encoding');

            if(!empty($this->config->forced_cors_headers)){
                foreach ($this->config->forced_cors_headers as $key => $value) {
                    $this->response->setHeader($key, $value);
                }
            }
        }
    }

    private function useOnlyAjax()
    {
        if($this->rest_ajax_only){
            if( !$this->request->isAJAX() ){
                $this->statusCode = 403;
                $this->setResponseMessage(false, 'Only available for ajax request.');
            }
        }
    }

    private function useIPBlacklistFilter()
    {
        if( !$this->config->rest_ip_blacklist_enabled ) return true;

        $ip_address = $this->request->getIPAddress();
        if( in_array($ip_address, $this->config->rest_ip_blacklist) ){
            $this->statusCode = 403;
            $this->setResponseMessage(false, 'Your IP address is blocked to access this endpoint');
        }

    }

    private function useOnlyIPWhiteLists()
    {
        if( !$this->config->rest_ip_whitelist_enabled ) return true;

        $ip_address = $this->request->getIPAddress();
        $alwaysAllowed = ['127.0.0.1', '::1'];
        if( !in_array($ip_address, array_merge($this->config->rest_ip_whitelist, $alwaysAllowed) ) ){
            $this->statusCode = 403;
            $this->setResponseMessage(false, 'Your IP address is not allowed to access this endpoint');
        }
    }

    private function useMethodFilter(){
        $method = $this->request->getMethod();

        // make an array value from $this->config->rest_allowed_method to uppercase
        $this->config->rest_allowed_method = array_map('strtoupper', $this->config->rest_allowed_method);

        if(!in_array( strtoupper($method) , $this->config->rest_allowed_method)){
            $this->statusCode = 403;
            $this->setResponseMessage(false, 'Only available method for  ( '. implode(', ', $this->config->rest_allowed_method) .' ).');
        }
    }

    private function useOutputFormat(){
        /** output format */
        if($this->config->rest_default_format != 'based_controller'){
            if( ! in_array($this->config->rest_default_format, $this->config->rest_supported_formats) ){
                $this->statusCode = 403;
                $this->setResponseMessage(false, 'Only available for format ( '. implode(', ', $this->config->rest_supported_formats) .' ).');
            }
            $this->setFormat($this->config->rest_default_format);
        }
    }

    private function useJWT(){
        if($this->config->rest_auth != 'JWT'){
            return true;
        }

        $header         = $this->request->getServer('HTTP_AUTHORIZATION');
        $JWT_SECRET_KEY     = $this->config->rest_JWT_secret;
        $JWT_TIME_TO_LIVE   = $this->config->rest_JWT_timetolive;

        try {

            $LibJWT         = new \Ay4t\Ci4rest\JWT\FirebaseJWT($JWT_SECRET_KEY, $JWT_TIME_TO_LIVE);
            $encodedToken   = $LibJWT->headerOtenticate($header);
            $validateJWT    = $LibJWT->validateJWT($encodedToken);

            if($this->use_JWT_refresh_token){
                unset($validateJWT->iat, $validateJWT->exp );
                $time_start         = time();
                $expired_time       = $time_start + $JWT_TIME_TO_LIVE;
                $payload            = (array) $validateJWT;
                $payload['iat']     = $time_start;
                $payload['exp']     = $expired_time;
                $this->rest_response['refresh_token'] = $LibJWT->setToken($payload);
            }

            return $validateJWT;

        } catch (Exception $e) {
            $this->statusCode = 403;
            $this->setResponseMessage(false, $e->getMessage());
        }
    }

    private function useSecureRequest()
    {
        if(!$this->config->force_https){
            return true;
        }

        if(!Security::isSecureCheck($this->request)){
            $this->statusCode = 403;
            $this->setResponseMessage(false, 'You must use the HTTPS protocol to access this endpoint');
        }
    }


    protected function setResponseMessage( $status = true, $message = 'OK' ){
        $this->rest_response[ $this->config->rest_status_field_name ]     = $status;
        $this->rest_response[ $this->config->rest_message_field_name ]    = $message;
        return $this->rest_response;
    }

}
