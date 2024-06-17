class BoutonAjoutDossier{
    /**
     * Permet d'ajouter un event listener sur le bouton ajout dossier
     */
    constructor(){

        const div = document.createElement("div");
        div.classList.add("ajout-dossier");
        div.innerHTML = `
        <span>
            <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="icon small" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5V19M5 12H19" stroke="#A3A3A3" stroke-width="2px" stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
            </svg>
        </span>
        <p>Ajouter</p>`;


        document.getElementById("arborescence").appendChild(div);


        div.addEventListener('click', async () => {
            // cas où il faut créer un nouvel espace
            if (arborescence.length == 0) {
                let nom_espace = "";
                while (nom_espace == "") {
                    nom_espace = prompt("Nom du nouvel espace");
                    if (nom_espace === null) {
                        return;
                    }
                }
                const nouvel_espace = await API.createNewEspace(nom_espace);
                document.querySelector("div.ajout-dossier").remove();
                Arborescence.addEspace(nouvel_espace);
                new BoutonAjoutDossier()
                return;
            }
        
            let id_categorie_parent = -1;
            let nom_categorie = "";
        
            // cas où il faut créer une nouvelle categorie à la racine d'un espace
            if (arborescence.length > 1) {
                id_categorie_parent = arborescence.slice(-1)[0]["id"];
            }
        
            while (nom_categorie == "") {
                nom_categorie = prompt("Nom de la nouvelle catégorie");
                if (nom_categorie === null) {
                    return;
                }
            }
            const id_espace = arborescence[0]["id"];
            const nouvelle_categorie = await API.createNewCategorie(nom_categorie, id_categorie_parent, id_espace);
            document.querySelector("div.ajout-dossier").remove();
            Arborescence.addCategorie(nouvelle_categorie);
            new BoutonAjoutDossier()
    
            return;
        })
    }
}