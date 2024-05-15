const btn = document.querySelector("div.ajout-flux");

btn.addEventListener("click", () => {
    // if we are at the root
    if(arborescence.length == 0) return
    // if we are in a shared space
    else if(arborescence.length == 1) window.location.href = `choix-content?id_espace=${arborescence[0]["id"]}`;
    // if we are in a category
    else window.location.href = `choix-content?id_categorie=${arborescence.slice(-1)[0]["id"]}&id_espace=${arborescence[0]["id"]}`;
});