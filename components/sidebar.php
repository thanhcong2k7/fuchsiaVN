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
    <li><a href="/upgrade/"><i class="zmdi zmdi-tag text-info"></i> <span>Upgrade plan</span></a></li>
    <li><a href="/login/login.php?logout=yes"><i class="zmdi zmdi-run text-danger"></i> <span>Log out?</span></a></li>
<li><select id="google_translate_selector">
  <option value="">🌐 Language</option>
</select>
<div id="google_translate_element" style="display:none;"></div></li>
  </ul>

</div>
<script>
  document.getElementById('google_translate_selector')
    .addEventListener('change', function() {
      var lang = this.value;
      // Google’s widget listens for this event:
      var gt = document.querySelector('.goog-te-combo');
      if (gt) { gt.value = lang; gt.dispatchEvent(new Event('change')); }
    });

  // Populate the <select> with the same languages Google uses:
  window.addEventListener('load', function() {
    var combo = document.querySelector('.goog-te-combo');
    if (!combo) return;
    var sel = document.getElementById('google_translate_selector');
    // Copy options from Google’s hidden select
    Array.from(combo.options).forEach(function(opt) {
      sel.appendChild(new Option(opt.text, opt.value));
    });
  });
</script>
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'en',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
      autoDisplay: false
    }, 'google_translate_element');
  }
</script>
<script 
  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>
