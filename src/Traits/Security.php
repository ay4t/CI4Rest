<?php
/*
 * File: Security.php
 * Project: Traits
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

namespace Ay4t\CI4Rest\Traits;

use CodeIgniter\HTTP\IncomingRequest;

trait Security 
{
    public static function isSecureCheck(IncomingRequest $request ) : bool
    {
        if($request->isSecure()){
            return true;
        }        
        return false;
    }
}