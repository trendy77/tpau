(function($){
  $(function(){
    $('.carousel').carousel();
    $('.dropdown-button').dropdown();
    $('.collapsible').collapsible();
    $('.scrollspy').scrollSpy();
    $('.button-collapse').sideNav();
    $('.parallax').parallax();

  }); // end of document ready
})(jQuery); // end of jQuery name space

function init() {
    console.log('hi');
}

window.addEventListener("load", init, true);