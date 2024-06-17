class CollectionItem {

    constructor(id, nom, est_selectionne) {
        this.id = id;
        this.nom = nom;
        this.est_selectionne = est_selectionne;
    }

    getHTML() {
        const button = document.createElement("button");
        button.classList.add("tag");
        button.id = `tag-${this.id}`;

        if (this.est_selectionne) {
            button.classList.add("select");
        }

        button.innerHTML = `<p>${this.nom}</p>`;

        button.addEventListener("click", () => {
            if (this.est_selectionne) {
                this.est_selectionne = false;
                button.classList.remove("select");
                API.addArticleToCollection(document.querySelector("#article-reader>h1").id, this.id)
            }
            else {
                this.est_selectionne = true;
                button.classList.add("select");
                API.addArticleToCollection(document.querySelector("#article-reader>h1").id, this.id)
            }
        });

        button.addEventListener("contextmenu", (e) => {
            e.stopPropagation();
            e.preventDefault();
            const context_menu = document.getElementById('context-menu');
            context_menu.style.display = "grid";
            context_menu.style.left = e.x + "px";
            context_menu.style.top = e.y + "px";
            ContextMenu.vider();
            const item_supprimer_collection = ContextMenu.addItem("Supprimer cette collection");
            item_supprimer_collection.addEventListener("click", () =>{
                API.deleteCollection(this.id);
                button.remove();
            });
        });

        return button;
    }
}