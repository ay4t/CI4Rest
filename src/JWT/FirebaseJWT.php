<?php
/*
* File: FirebaseJWT.php
* Project: Libraries
* Created Date: Mo Sep 2022
* Author: Ayatulloh Ahad R (Email: ayatulloh@indiega.net Phone: 085791555506 )
* -------------------------------------------------------
* Last Modified: Mon Sep 19 2022
* Modified By: Ayatulloh Ahad R (Email: ayatulloh@indiega.net Phone: 085791555506 )
* -------------------------------------------------------
* Copyright (c) 2022 Your Company
* -------------------------------------------------------
*
* HISTORY:
* Date By Comments
* ---------- --- ---------------------------------------------------------
*/

namespace Ay4t\CI4Rest\JWT;

use Exception;

class FirebaseJWT
{

    /**
     * jwt_secret_key
     *
     * @var string
     */
    protected $jwt_secret_key;

    /**
     * jwt_time_to_live
     *
     * @var int
     */
    protected $jwt_time_to_live = 0;

    /**
     * __construct
     *
     * @return 
     */
    public function __construct( string $JWT_SECRET_KEY, string $JWT_TIME_TO_LIVE)
    {
        $this->jwt_secret_key       = $JWT_SECRET_KEY;
        $this->jwt_time_to_live     = $JWT_TIME_TO_LIVE;
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function headerOtenticate($params = null)
    {
        if (empty($params)) throw new Exception("JWT header Authentication failed !");
        return explode(" ", $params)[1];
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function validateJWT($encodedToken)
    {
        $decoded = \Firebase\JWT\JWT::decode($encodedToken, new \Firebase\JWT\Key($this->jwt_secret_key, 'HS256'));
        return $decoded;
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function setToken(array $payload = [])
    {
        $time_start         = time();
        $expired_time       = $time_start + $this->jwt_time_to_live;

        $payload['iat'] = $time_start;
        // $payload['nbf'] = $expired_time;
        $payload['exp'] = $expired_time;

        return $jwt = \Firebase\JWT\JWT::encode($payload, $this->jwt_secret_key, 'HS256');
    }
}
