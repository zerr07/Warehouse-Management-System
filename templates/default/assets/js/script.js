var preloader;
var loader;
var loaderText;
var preloaderProgress;
var loaderProgress;
var loaderTextProgress;
var loaderProgressBar;
$(window).on("load", function (){
    preloaderProgress = $('#loaderAreaProgress');
    loaderProgress = preloaderProgress.find('.loaderProgress');
    loaderTextProgress = preloaderProgress.find('.preloaderProgress-text');
    loaderProgressBar = preloaderProgress.find('#preloaderProgress_progressBar');
    preloader = $('#loaderArea');
    loader = preloader.find('.loader');
    loaderText = preloader.find('.preloader-text');
});
function setPreloaderProgress(percent){
    $(loaderProgressBar[0]).css("width", percent+"%");
    $(loaderProgressBar[0]).attr("aria-valuenow", percent);
}
function turnOnProgressPreloader(){
    loaderProgress[0].style.display = "block";
    preloaderProgress[0].style.display = "block";
}
function turnOffProgressPreloader(){
    loaderProgress.fadeOut();
    preloaderProgress.delay(150).fadeOut(100);
}
function turnOnPreloader(){
    loader[0].style.display = "block";
    preloader[0].style.display = "block";
    document.getElementById("main_block").style.filter = "blur(1.5rem)";
}
function turnOffPreloader(){
    document.getElementById("main_block").style.filter = "blur(1.5rem)";
    loader.fadeOut();
    preloader.delay(150).fadeOut(100);

    let blur = 1.5;
    var intervalId = setInterval(function(){
        if (document.getElementById("main_block").style.filter !== "blur(0rem)"){
            blur = Math.round((blur-0.1) * 100) / 100;
            document.getElementById("main_block").style.filter = "blur("+blur+"rem)";
        } else {
            $("#main_block").removeAttr("style");
            clearInterval(intervalId);
        }
    }, 1);

}

function deleteProduct(id){
    var r = confirm("Do you really want to delete item?");
    if (r === true) {
        $.ajax({
            dataType: "text",
            async: false,
            url: "/controllers/products/delete.php?delete="+id
        });
        location.reload();
    } else {
        return;
    }

}
$("html").on("mouseup", function (e) {
    var l = $(e.target);
    if(l[0].getAttribute("class")){
        console.log("sd");
    console.log(l[0].className.indexOf("dont-hide"));
        if (l[0].className.indexOf("popover") >= 0 || l[0].className.indexOf("dont-hide") >= 0) {

        } else {
            $(".popover").each(function () {
                $(this).popover("hide");
            });
        }
    }
});
var mybutton;
$(window).on("load", function (){
    mybutton = document.getElementById("myBtn");
})

window.onscroll = function() {scrollFunction()};


function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}
