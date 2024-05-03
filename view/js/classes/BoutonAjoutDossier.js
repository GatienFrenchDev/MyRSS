class BoutonAjoutDossier{
    /**
     * Permet d'ajouter un event listener sur le bouton ajout dossier
     */
    constructor(){
        document.querySelector('div.ajout-dossier').addEventListener('click', async () => {
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
                Arborescence.addEspace(nouvel_espace);
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
            Arborescence.addCategorie(nouvelle_categorie);
            return;
        })
    }
}