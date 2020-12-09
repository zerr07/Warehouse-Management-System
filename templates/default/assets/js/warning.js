function enableWarning(btn, comment, user){
    btn.style.color = "red";
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
        fetch("/cp/POS/reserve/addWarning.php", requestParams);
        enableWarning(btn, comment, getCookie("Authenticated"));
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
            fetch("/cp/POS/reserve/addWarning.php", requestParams);
            disableWarning(btn);

        } else {
            console.log("cancelled");
        }
    }
}