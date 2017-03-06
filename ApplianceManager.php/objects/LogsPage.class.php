<?php
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

class LogsPage {
	/**
	 * @var int length total logs count
	 */
	public $length;
	/**
	 * @var uri previous link to previous page
	 */
	public $previous;
	 /**
     * @var array {@type Log} 
     * List of Networks (see /networks/{netId} resource for details)
     */
	public $logs=array();
	/**
	 * @var uri previous link to next page
	 */
	public $next;
	
}
