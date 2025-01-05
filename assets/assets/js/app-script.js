
$(function() {
    "use strict";
     function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  let expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
        }
    }
    return "";
}
	 
//sidebar menu js
$.sidebarMenu($('.sidebar-menu'));

// === toggle-menu js
$(".toggle-menu").on("click", function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });	 
	   
// === sidebar menu activation js
$('body').attr('class', 'bg-theme bg-'+(getCookie('theme')?getCookie('theme'):"theme1"));
$(function() {
        for (var i = window.location, o = $(".sidebar-menu a").filter(function() {
            return this.href == i;
        }).addClass("active").parent().addClass("active"); ;) {
            if (!o.is("li")) break;
            o = o.parent().addClass("in").parent().addClass("active");
        }
    }), 	   
	   

/* Top Header */

$(document).ready(function(){ 
    $(window).on("scroll", function(){ 
        if ($(this).scrollTop() > 60) { 
            $('.topbar-nav .navbar').addClass('bg-dark'); 
        } else { 
            $('.topbar-nav .navbar').removeClass('bg-dark'); 
        } 
    });

 });


/* Back To Top */

$(document).ready(function(){ 
    $(window).on("scroll", function(){ 
        if ($(this).scrollTop() > 300) { 
            $('.back-to-top').fadeIn(); 
        } else { 
            $('.back-to-top').fadeOut(); 
        } 
    }); 

    $('.back-to-top').on("click", function(){ 
        $("html, body").animate({ scrollTop: 0 }, 600); 
        return false; 
    }); 
});	   
	    
   
$(function () {
  $('[data-toggle="popover"]').popover()
})


$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

	 // theme setting
	 $(".switcher-icon").on("click", function(e) {
        e.preventDefault();
        $(".right-sidebar").toggleClass("right-toggled");
    });
	$('#theme1').click(theme1);
    $('#theme2').click(theme2);
    $('#theme3').click(theme3);
    $('#theme4').click(theme4);
    $('#theme5').click(theme5);
    $('#theme6').click(theme6);
    $('#theme7').click(theme7);
    $('#theme8').click(theme8);
    $('#theme9').click(theme9);
    $('#theme10').click(theme10);
    $('#theme11').click(theme11);
    $('#theme12').click(theme12);
    $('#theme13').click(theme13);
    $('#theme14').click(theme14);
    $('#theme15').click(theme15);

    function theme1() {
      $('body').attr('class', 'bg-theme bg-theme1');
	  setCookie('theme','theme1',60);
    }

    function theme2() {
      $('body').attr('class', 'bg-theme bg-theme2');
	  setCookie('theme','theme2',60);
    }

    function theme3() {
      $('body').attr('class', 'bg-theme bg-theme3');
	  setCookie('theme','theme3',60);
    }

    function theme4() {
      $('body').attr('class', 'bg-theme bg-theme4');
	  setCookie('theme','theme4',60);
    }
	
	function theme5() {
      $('body').attr('class', 'bg-theme bg-theme5');
	  setCookie('theme','theme5',60);
    }
	
	function theme6() {
      $('body').attr('class', 'bg-theme bg-theme6');
	  setCookie('theme','theme6',60);
    }

    function theme7() {
      $('body').attr('class', 'bg-theme bg-theme7');
	  setCookie('theme','theme7',60);
    }

    function theme8() {
      $('body').attr('class', 'bg-theme bg-theme8');
	  setCookie('theme','theme8',60);
    }

    function theme9() {
      $('body').attr('class', 'bg-theme bg-theme9');
	  setCookie('theme','theme9',60);
    }

    function theme10() {
      $('body').attr('class', 'bg-theme bg-theme10');
	  setCookie('theme','theme10',60);
    }

    function theme11() {
      $('body').attr('class', 'bg-theme bg-theme11');
	  setCookie('theme','theme11',60);
    }

    function theme12() {
      $('body').attr('class', 'bg-theme bg-theme12');
	  setCookie('theme','theme12',60);
    }
	
	function theme13() {
      $('body').attr('class', 'bg-theme bg-theme13');
	  setCookie('theme','theme13',60);
    }
	
	function theme14() {
      $('body').attr('class', 'bg-theme bg-theme14');
	  setCookie('theme','theme14',60);
    }
	
	function theme15() {
      $('body').attr('class', 'bg-theme bg-theme15');
	  setCookie('theme','theme15',60);
    }

});