class BoutonAjoutCollection{
    static getHTML(){
        const button = document.createElement("button");
        button.classList.add("tag");
        button.id = "ajout-tag"
        
        button.innerHTML = `<p>+ Ajouter</p>`;

        button.addEventListener("click", async () => {
            let nom_collection = "";
            while (nom_collection == "") {
                nom_collection = prompt("Nom de la nouvelle collection (max 32 caractères)");
                if (nom_collection === null) {
                    return;
                }
            }
            const id_nouvelle_collection = await API.createNewCollection(nom_collection);
            const nouvelle_collection = new CollectionItem(id_nouvelle_collection, nom_collection, false);
            const container_collection = document.getElementById("container-collections");
            this.remove();
            container_collection.appendChild(nouvelle_collection.getHTML());
            this.display();
        });

        return button;
    }

    static display(){
        const container_collection = document.getElementById("container-collections");
        container_collection.appendChild(this.getHTML());
    }


    static remove(){
        const btn = document.getElementById("ajout-tag");
        if(btn !== null){
            btn.remove();
        }
    }
}