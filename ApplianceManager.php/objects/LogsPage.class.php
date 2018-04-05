<?php
/**
 * Reverse Proxy as a service
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
*/
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2017 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/objects/LogsPage.class.php
 *
 * Created     : 2017-03
 * Authors     : Zorglub42 <contact(at)zorglub42.fr>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2017-03-03 : Release of the file
*/

/**
 * Paginated logs
 * 
 * PHP Version 7.0
 * 
 * @category ReverseProxy
 * @package  OSA
 * @author   Benoit HERARD <benoit.herard@orange.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0.htm Apache 2 license
 * @link     https://github.com/zorglub42/OSA/
 */
class LogsPage
{
    /**
     * Total count
     * 
     * @var int length total logs count
     */
    public $length;
    /**
     * Link to previous page
     * 
     * @var uri previous link to previous page
     */
    public $previous;
    /**
     * Log page
     *
     * @var array {@type Log} 
     * List of Networks (see /networks/{netId} resource for details)
     */
    public $logs=array();
    /**
     * Link to next page
     * 
     * @var uri previous link to next page
     */
    public $next;
    
}
