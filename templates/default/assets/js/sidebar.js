
function openNav() {
    document.getElementById("sidebar_menu").style.width = "300px";
    document.getElementById("sidebar_menu").style.height = "100vh";
    document.getElementById("sidebar_menu").style.marginLeft = "0";
    $("#sidebar_menu").addClass('in');
    $("body").addClass('in');
}
function closeNav() {
    document.getElementById("sidebar_menu").style.width = "0";
    document.getElementById("sidebar_menu").style.height = "0";
    document.getElementById("sidebar_menu").style.marginLeft = "-300px";
    $("#sidebar_menu").removeClass('in');
    $("body").removeClass('in');
}
let sidebar_dropdowns = $('.sidebar_dropdown_menu');
sidebar_dropdowns.on('show.bs.collapse', function () {
    let target = $('[data-target="#'+this.id+'"]')
    target.children("svg.dropdown_svg").removeClass("sidebar_rotate-off");
    target.children("svg.dropdown_svg").addClass("sidebar_rotate-on");
})
sidebar_dropdowns.on('hide.bs.collapse', function () {
    let target = $('[data-target="#'+this.id+'"]')
    target.children("svg.dropdown_svg").removeClass("sidebar_rotate-on");
    target.children("svg.dropdown_svg").addClass("sidebar_rotate-off");
})


$('html').click(function(e) {
    if ((e.target.id !== 'sidebar_menu' && $(e.target).parents('#sidebar_menu').length === 0) &&
        (e.target.id !== 'sidebar_close_btn' && $(e.target).parents('#sidebar_close_btn').length === 0)) {
        closeNav();
    }
});
