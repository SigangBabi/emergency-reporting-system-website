<?php
// Lightweight back button include.
// Behavior:
// - If on register.php -> go to login.php
// - If on login.php    -> go to homePage.php
// - Otherwise use history.back() if available, else go to homePage.php
?>
<button id="site-back-button" aria-label="Go back">← Back</button>
<style>
#site-back-button{
  position:fixed;
  left:1rem;
  top:1rem;
  z-index:9999;
  background:#ffffff;
  color:#111;
  border:1px solid #d1d5db;
  padding:.5rem .75rem;
  border-radius:6px;
  box-shadow:0 6px 18px rgba(0,0,0,.12);
  cursor:pointer;
  font-weight:600;
  backdrop-filter: blur(4px);
}
#site-back-button:hover{ transform: translateY(-1px); }
@media (max-width:600px){
  #site-back-button{ left:.5rem; top:.5rem; padding:.4rem .6rem; }
}
</style>
<script>
(function(){
  var btn = document.getElementById('site-back-button');
  if(!btn) return;
  btn.addEventListener('click', function(e){
    e.preventDefault();
    try {
      var path = window.location.pathname || '';
      var page = path.substring(path.lastIndexOf('/') + 1).toLowerCase();

      // adjust these paths if your structure differs
      var loginPath = '/Commission/login/LoginPage/login.php';
      var homePath  = '/Commission/login/HomePage/homePage.php';

      if (page === 'register.php') {
        window.location.href = loginPath;
        return;
      }
      if (page === 'login.php') {
        window.location.href = homePath;
        return;
      }

      if (window.history && window.history.length > 1) {
        window.history.back();
      } else {
        window.location.href = homePath;
      }
    } catch (err) {
      window.location.href = '/Commission/login/HomePage/homePage.php';
    }
  }, false);
})();
</script>