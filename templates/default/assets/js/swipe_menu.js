let touchstartX = 0;
let touchstartY = 0;
let touchendX = 0;
let touchendY = 0;

const gestureZone = document.querySelector('html');

gestureZone.addEventListener('touchstart', function(event) {
    touchstartX = event.changedTouches[0].screenX;
    touchstartY = event.changedTouches[0].screenY;
}, false);

gestureZone.addEventListener('touchend', function(event) {
    touchendX = event.changedTouches[0].screenX;
    touchendY = event.changedTouches[0].screenY;
    handleGesture();
}, false);
var swipeLeft = new CustomEvent("swipe-left", {

});
var swipeRight= new CustomEvent("swipe-right", {

});
function handleGesture() {

    if ((touchendY-touchstartY) < 100 || (touchendY-touchstartY) > -100){
        if (touchendX < touchstartX) {
            if ((touchstartX-touchendX) >= 200) {
                document.dispatchEvent(swipeLeft);
            }
        }

        if (touchendX > touchstartX) {
            if ((touchendX-touchstartX) >= 200) {
                document.dispatchEvent(swipeRight);
            }

        }
    }
}

document.addEventListener("swipe-right", function (){
    openNav();
})
document.addEventListener("swipe-left", function (){
    if ($("body").hasClass("in")){
        closeNav();
    }
})