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
        <i class="zmdi zmdi-assignment-account"></i> <span>Settings</span>
      </a>
    </li>

    <li class="sidebar-header">MANAGEMENT</li>
    <li><a href="/manager/artist/"><i class="zmdi zmdi-accounts text-warning"></i> <span>Artists</span></a></li>
    <li><a href="/manager/tracks/"><i class="zmdi zmdi-audio text-success"></i> <span>Tracks</span></a></li>
    <li><a href="/upgrade/" onclick="event.preventDefault(); upgrade();" id="upgradePlan" data-toggle="modal"
        data-target="#paymentModal"><i class="zmdi zmdi-tag text-info"></i> <span>Upgrade plan</span></a></li>
    <li><a href="/login/login.php?logout=yes"><i class="zmdi zmdi-run text-danger"></i> <span>Log out?</span></a></li>
  </ul>
</div>
<style>
  #paymentModal .modal-content {
    background-color: rgba(0, 0, 0, 0.95);
    color: #fff;
  }

  #paymentModal .modal-body .col-12 {
    padding: .5rem;
  }

  #paymentModal .modal-header {
    border-bottom: 1px solid #333;
  }

  #paymentModal .modal-footer {
    border-top: 1px solid #333;
  }

  #paymentBody.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 2rem;
    height: 2rem;
    border: .25rem solid #fff;
    border-top-color: transparent;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: spin .75s linear infinite;
  }

  @keyframes spin {
    to {
      transform: translate(-50%, -50%) rotate(360deg);
    }
  }
</style>
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Upgrade to PRO Plan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div><!-- in payment-modal.html -->
      <div class="modal-body position-relative" id="paymentBody" aria-live="polite">
        <div class="row">
          <div class="col-12 col-md-4 text-center">
            <img id="maqr" alt="Loading QR…" style="max-width:100%;border-radius:10px">
          </div>
          <div class="col-12 col-md-8">
            <p>Account Number: <strong id="accountNumber">–</strong></p>
            <p>Description: <strong id="paymentDescription">Đang thiết lập...</strong></p>
            <p>Amount: <strong id="paymentAmount">–</strong></p>
            <p>Order code: <strong id="orderCode">–</strong></p>
            <button id="checkoutBtn" class="btn btn-success btn-block disabled" disabled>
              <span id="btnLabel">Loading…</span>
            </button>
            <div class="text-danger small mt-2" id="paymentError" hidden></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close payment pop-up"
          id="closePayment">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="/components/payment.js"></script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
  (function () {
    var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
    s1.async = true;
    s1.src = 'https://embed.tawk.to/5c17215c7a79fc1bddf13285/default';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
  })();
</script>
<!--End of Tawk.to Script-->