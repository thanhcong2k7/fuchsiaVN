<div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
  <div class="brand-logo">
    <a href="/">
      <img src="/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
      <h5 class="logo-text">fuchsia Partner</h5>
    </a>
  </div>
  <ul class="sidebar-menu do-nicescrol">
    <li class="sidebar-header">MAIN MENU</li>
    <li>
      <a href="/">
        <i class="zmdi zmdi-view-dashboard"></i> <span>Homepage</span>
      </a>
    </li>
    <li>
      <a href="/discography/">
        <i class="zmdi zmdi-album"></i> <span>Discography</span>
      </a>
    </li>

    <li>
      <a href="/analytics/">
        <i class="zmdi zmdi-format-list-bulleted"></i> <span>Analytics</span>
      </a>
    </li>

    <li>
      <a href="/revenue/">
        <i class="zmdi zmdi-balance-wallet"></i> <span>Revenue</span>
      </a>
    </li>

    <li>
      <a href="/settings/">
        <i class="zmdi zmdi-assignment-account"></i> <span>Your Account</span>
      </a>
    </li>

    <li class="sidebar-header">TOOLBOX</li>
    <li><a href="/manager/artist/"><i class="zmdi zmdi-accounts text-warning"></i> <span>Artists</span></a></li>
    <li><a href="/manager/tracks/"><i class="zmdi zmdi-audio text-success"></i> <span>Tracks</span></a></li>
    <li><a href="/ticket/"><i class="zmdi zmdi-tag text-info"></i> <span>Support</span></a></li>
    <li><a href="/upgrade/" onclick="upgrade()" id="upgradePlan" data-toggle="modal" data-target="#paymentModal"><i class="zmdi zmdi-tag text-info"></i> <span>Upgrade plan</span></a></li>
    <li><a href="/login/login.php?logout=yes"><i class="zmdi zmdi-run text-danger"></i> <span>Log out?</span></a></li>
  </ul>
</div>
<script>
  function upgrade(){
    fetch("/checkout.php")
  }
</script>
<style>
  
  #paymentModal .modal-content {
      background-color: rgba(0, 0, 0, 0.95);
      color: #fff;
    }

    #paymentModal .modal-header {
      border-bottom: 1px solid #333;
    }

    #paymentModal .modal-footer {
      border-top: 1px solid #333;
    }
</style>
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Upgrade to PRO Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <img src="/assets/images/alb.png" alt="QR Thanh Toan" id="maqr" width="200px" height="200px" style="border-radius:10px">
          </div>
          <div class="col">
            thanh toan di cu
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>