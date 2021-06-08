function actu_iframe(){
    if(navigator.appName === "Microsoft Internet Explorer" ){
        if(document.all) document.all.id_iframe.style.height = document.frames("id_iframe" ).document.body.scrollHeight;
        else document.getElementById("id_iframe" ).style.height = document.getElementById("id_iframe" ).contentDocument.body.scrollHeight;
    }
    else{
        document.getElementById("id_iframe" ).style.height = document.getElementById("id_iframe" ).contentDocument.body.offsetHeight+"px";
    }
}