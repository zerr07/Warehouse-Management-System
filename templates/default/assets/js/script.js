var preloaderProgress;
var loaderProgress;
var loaderTextProgress;
var loaderProgressBar;
$(window).on("load", function (){
    preloaderProgress = $('#loaderAreaProgress');
    loaderProgress = preloaderProgress.find('.loaderProgress');
    loaderTextProgress = preloaderProgress.find('.preloaderProgress-text');
    loaderProgressBar = preloaderProgress.find('#preloaderProgress_progressBar');
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