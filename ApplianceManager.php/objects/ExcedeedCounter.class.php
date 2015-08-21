<?php
/*--------------------------------------------------------
 * Module Name : ApplianceManager
 * Version : 1.0.0
 *
 * Software Name : OpenServicesAccess
 * Version : 1.0
 *
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/objects/ExcedeedCounter.class.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      .../...
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/

require_once '../objects/Counter.class.php';
class ExcedeedCounter extends Counter{

	private $maxValue;
	
	public function getMaxValue(){
		return $this->maxValue;
	}
	public function setMaxValue($maxValue){
		$this->maxValue=$maxValue;
	}
	
	public function toArray(){
		$rc = parent::toArray();
		$rc["maxValue"]=$this->getMaxValue();
		return $rc;
	}
	
    public function __construct($rqt=NULL)
    {
		if ($rqt != NULL){
			parent::__construct($rqt);
	
			if ($this->getTimeUnit() == 'S'){
				$this->setMaxValue($rqt["reqSec"]);
			}elseif ($this->getTimeUnit() == 'D'){
				$this->setMaxValue($rqt["reqDay"]);
			}elseif ($this->getTimeUnit() == 'M'){
				$this->setMaxValue($rqt["reqMonth"]);
			}
		}
	}

}
?>
