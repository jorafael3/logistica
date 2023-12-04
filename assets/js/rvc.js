// BootStrap 4 Submenu keep open on navbar
// For submenus add class "dropdown-submenu"
// To the main menu in navbar that gives problems add class: "menu-open" and "keep-open"
$(document).on('show.bs.dropdown', function (e) {
    var target = $(e.target);
    if ($(target).is('.menu-open')) {
        $('.menu-open').addClass('keep-open');
    }
});

$(document).on('hide.bs.dropdown', function (e) {
    var target = $(e.target);
    if ($(target).is('.keep-open')) {
        return false
    }
});

$('body').on('click', function (e) {
    if (!$('.keep-open').is(e.target) 
        && $('.keep-open').has(e.target).length === 0 
        && $('.keep-open').has(e.target).length === 0
    ) {
        $('.keep-open').removeClass('keep-open');
    }
});


/*
// OTHER METHOD
$(function() {
  // ------------------------------------------------------- //
  // Multi Level dropdowns
  // ------------------------------------------------------ //
  $(document).on('show.bs.dropdown', function (e) {
    event.preventDefault();
    event.stopPropagation();

    $(this).siblings().toggleClass("keep-open");


    if (!$(this).next().hasClass('keep-open')) {
      $(this).parents('.dropdown-menu').first().find('.show').removeClass("keep-open");
    }
    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
      $('.dropdown-submenu .show').removeClass("keep-open");
    });

  });
});
*/