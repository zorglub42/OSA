<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only"><?php echo Localization::getString("nav.toggle")?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="."><?php echo $firstName?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li>
          <a href="#" id="listGroup"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Localization::getString("nav.groups")?></a>
        </li>
        <li>
          <a href="#" id="listService" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Localization::getString("nav.services")?></a>
        </li>
        <li>
          <a href="#" id="listUser" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Localization::getString("nav.users")?></a>
        </li>
        <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Localization::getString("nav.counters")?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a id="searchCounter" href="#"><?php echo Localization::getString("nav.counters.search")?></a></li>
					<li><a id="searchExcedeedCounters" href="#"><?php echo Localization::getString("nav.counters.exceeded")?></a></li>
				</ul>
        </li>
        <li>
          <a id="listNode" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Localization::getString("nav.nodes")?></a>
        </li>
        <li>
          <a id="searchLogs" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Localization::getString("nav.logs")?></a>
        </li>
        <li>
          <a id="apiDoc" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Localization::getString("nav.apiDoc")?></a>
        </li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
