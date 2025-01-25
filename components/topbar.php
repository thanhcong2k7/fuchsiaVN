<header class="topbar-nav" id="topbar">
  <nav class="navbar navbar-expand fixed-top">
    <ul class="navbar-nav mr-auto align-items-center">
      <li class="nav-item">
        <a class="nav-link toggle-menu" href="javascript:void();">
          <i class="icon-menu menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <form class="search-bar">
          <input type="text" class="form-control" placeholder="Find releases">
          <a href="javascript:void();"><i class="icon-magnifier"></i></a>
        </form>
      </li>
    </ul>

    <ul class="navbar-nav align-items-center right-nav-link">
      <li class="nav-item dropdown-lg">
        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown"
          href="javascript:void();">
          <i class="fa fa-envelope-open-o"></i></a>
      </li>
      <li class="nav-item dropdown-lg">
        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown"
          href="javascript:void();">
          <i class="fa fa-bell-o"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
          <span class="user-profile"><img src="<?php echo $user->avatar; ?>" class="img-circle" alt="user avatar" crossorigin></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
          <li class="dropdown-item user-details">
            <a href="javaScript:void();">
              <div class="media">
                <div class="avatar"><img class="align-self-start mr-3" src="<?php echo $user->avatar; ?>"
                    alt="user avatar" crossorigin></div>
                <div class="media-body">
                  <h6 class="mt-2 user-title"><?php echo $user->display; ?></h6>
                  <p class="user-subtitle"><?php echo $user->email; ?></p>
                </div>
              </div>
            </a>
          </li>
          <li class="dropdown-divider"></li>
          <li class="dropdown-item"><i class="icon-wallet mr-2"></i> Account</li>
          <li class="dropdown-divider"></li>
          <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
          <li class="dropdown-divider"></li>
          <a class="dropdown-item" href="login/login.php?logout=yes"><i class="icon-power mr-2"></i> Logout</a>
        </ul>
      </li>
    </ul>
  </nav>
</header>