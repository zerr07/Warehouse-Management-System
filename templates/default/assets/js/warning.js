function enableWarning(btn, comment, user, color){
    btn.style.color = color;
    btn.style.opacity = "1";
    btn.setAttribute('data-toggle', "tooltip");
    btn.setAttribute('data-placement', "top");
    btn.setAttribute('data-html', "true");
    btn.setAttribute('title', "By: "+user+" <br/> "+comment);
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
        document.querySelectorAll("button[data-toggle='tooltip'] > title").forEach(el=>{
            el.parentNode.removeChild(el);
        });
    })
}
function disableWarning(btn){
    btn.style.color = "gray";
    btn.style.opacity = "0.1";
    btn.removeAttribute('data-toggle');
    btn.removeAttribute('data-placement');
    btn.removeAttribute('data-html');
    btn.removeAttribute('data-original-title');
    btn.removeAttribute("title");
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
        document.querySelectorAll("button[data-toggle='tooltip'] > title").forEach(el=>{
            el.parentNode.removeChild(el);
        });
    })
}
function setWarning(id){
    let btn = document.querySelector("button[onclick=\"setWarning('"+id+"')\"]")
    if (btn.style.color === "gray" && btn.style.opacity === "0.1"){
        let comment = prompt("Warning message:");
        if (comment){
            const requestParams = {
                method: "POST",
                headers: new Headers({
                    "Content-Type": "application/json"
                }),
                body: JSON.stringify({
                    add: "",
                    id: id,
                    comment: comment
                })
            };
            fetch("/cp/POS/reserve/notifications.php", requestParams);
            enableWarning(btn, comment, getCookie("Authenticated"), "red");
        }
    } else if (btn.style.color === "red"){
        let conf = confirm("Are you sure you want to delete warning?");
        if (conf){
            const requestParams = {
                method: "POST",
                headers: new Headers({
                    "Content-Type": "application/json"
                }),
                body: JSON.stringify({
                    remove: "",
                    id: id,
                })
            };
            fetch("/cp/POS/reserve/notifications.php", requestParams);
            disableWarning(btn);

        } else {
            console.log("cancelled");
        }
    }
}

function setPaid(id){
    let btn = document.querySelector("button[onclick=\"setPaid('"+id+"')\"]")
    if (btn.style.color === "gray" && btn.style.opacity === "0.1"){
        let comment = prompt("Payment description:");
        if (comment){
            const requestParams = {
                method: "POST",
                headers: new Headers({
                    "Content-Type": "application/json"
                }),
                body: JSON.stringify({
                    add_paid: "",
                    id_paid: id,
                    comment_paid: comment
                })
            };
            fetch("/cp/POS/reserve/notifications.php", requestParams);
            enableWarning(btn, comment, getCookie("Authenticated"), "green");
        }
    } else if (btn.style.color === "green"){
        let conf = confirm("Are you sure you want to set as not paid?");
        if (conf){
            const requestParams = {
                method: "POST",
                headers: new Headers({
                    "Content-Type": "application/json"
                }),
                body: JSON.stringify({
                    remove_paid: "",
                    id_paid: id,
                })
            };
            fetch("/cp/POS/reserve/notifications.php", requestParams);
            disableWarning(btn);

        } else {
            console.log("cancelled");
        }
    }
}