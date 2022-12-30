if(! ['light','dark'].includes(localStorage.getItem("theme"))){
    localStorage.setItem("theme","light");
}

function setTheme(){
    document.body.classList.remove("dark");
    document.body.classList.remove("light");
    document.body.classList.add(localStorage.getItem("theme"));
}
