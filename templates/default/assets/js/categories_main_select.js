function DisableNotSelectable(){
    document.querySelectorAll("input[name='cat[]']").forEach(el=>{
        el.addEventListener("change", function () {
            if (el.innerHTML !== "None"){
                document.querySelectorAll("input[name='cat[]']").forEach(el_select=> {
                    if (el_select.checked){
                        el_select.nextElementSibling.disabled = false
                    } else {
                        el_select.nextElementSibling.disabled = true
                        el_select.nextElementSibling.checked = false
                    }
                })
            }
        })
    })
}
