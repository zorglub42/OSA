<?php
/**
 *  Reverse Proxy as a service
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
 * Copyright (c) 2011 – 2014 Orange
 * This software is distributed under the Apache 2 license
 * <http://www.apache.org/licenses/LICENSE-2.0.html>
 *
 *--------------------------------------------------------
 * File Name   : ApplianceManager/ApplianceManager.php/include/menu.php
 *
 * Created     : 2012-02
 * Authors     : Benoit HERARD <benoit.herard(at)orange.com>
 *
 * Description :
 *      Generate application GUI menu
 *--------------------------------------------------------
 * History     :
 * 1.0.0 - 2012-10-01 : Release of the file
*/
?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" 
              data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">
            <?php echo Localization::getString("nav.toggle")?>
        </span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="."><?php echo $firstName?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav" id="mainMenu">
        <li>
          <a href="#" id="listGroup" class="dropdown-toggle" data-toggle="dropdown" 
             role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo Localization::getString("nav.groups")?>
          </a>
        </li>
        <li>
          <a href="#" id="listService" class="dropdown-toggle" data-toggle="dropdown"
             role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo Localization::getString("nav.services")?>
          </a>
        </li>
        <li>
          <a href="#" id="listUser" class="dropdown-toggle" data-toggle="dropdown"
             role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo Localization::getString("nav.users")?>
          </a>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"
            role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo Localization::getString("nav.counters")?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a id="searchCounter" href="#">
                <?php echo Localization::getString("nav.counters.search")?>
              </a>
            </li>
            <li>
              <a id="searchExcedeedCounters" href="#">
                <?php echo Localization::getString("nav.counters.exceeded")?>
              </a>
            </li>
          </ul>
        </li>
        <li>
          <a id="listNode" href="#" class="dropdown-toggle" data-toggle="dropdown"
             role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo Localization::getString("nav.nodes")?>
          </a>
        </li>
        <li>
          <a id="searchLogs" href="#" class="dropdown-toggle" data-toggle="dropdown"
             role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo Localization::getString("nav.logs")?>
          </a>
        </li>
        <li class="dropdown">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"
             role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo Localization::getString("nav.apiDoc")?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" ID="apiDocList">
            <li><a id="apiDocMenu" onclick='loadDoc("api/doc/")' href="#">OSA</a></li>
          </ul>
        </li>
		</li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
