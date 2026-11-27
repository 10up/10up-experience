(()=>{
var e=window.jQuery&&window.jQuery.default||window.jQuery,{tenupWelcome:t,ajaxurl:n}=window,r=document.querySelector(`.notice-10up-experience-welcome`);if(r){let i={action:`tenup_dismiss_welcome`,nonce:t.nonce};e(r).on(`click`,`button`,()=>{e.ajax({method:`post`,data:i,url:n})})}
})();
