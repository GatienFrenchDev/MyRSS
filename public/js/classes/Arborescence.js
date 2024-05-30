class Arborescence{
    /**
     * 
     * @param {FluxRSS} flux_rss 
    */
   static addFluxRSS(flux_rss){
        const arborescence = document.getElementById('arborescence');
        const HTMLFluxRSS = flux_rss.getHTMLElement();
        arborescence.appendChild(HTMLFluxRSS);
    }

    /**
     * 
     * @param {FluxRSS[]} fluxs 
     */
    static addFluxs(fluxs){
        fluxs.forEach(flux => {
            this.addFluxRSS(flux);
        });
    }

    static vider(){
        const arborescence = document.getElementById('arborescence');
        arborescence.innerHTML = "";
    }

    /**
     * 
     * @param {Categorie[]} categories 
     */
    static addCategories(categories){
        categories.forEach(categorie => {
            this.addCategorie(categorie);
        })
    }

    /**
     * 
     * @param {Categorie} categorie 
     */
    static addCategorie(categorie){
        const arborescence = document.getElementById('arborescence');
        const HTMLcategorie = categorie.getHTMLElement();
        arborescence.appendChild(HTMLcategorie);
    }

    /**
     * 
     * @param {Espace[]} espaces 
     */
    static addEspaces(espaces){
        espaces.forEach(espace => {
            this.addEspace(espace);
        })
    }

    static addEspace(espace){
        const arborescence = document.getElementById('arborescence');
        const HTMLespace = espace.getHTMLElement();
        arborescence.appendChild(HTMLespace);
    }

    
    static addBackButton() {
        const arbo = document.querySelector('div#arborescence')
        const div = document.createElement('div');
        div.id = "retour";
        div.classList.add("dossier");
        div.innerHTML = `
    <div>
        <span>
            <svg fill="#000000" width="16" height="16" viewBox="0 0 0.48 0.48" xmlns="http://www.w3.org/2000/svg"><path d="m0.094 0.32 0.063 0.063a0.01 0.01 0 0 1 -0.014 0.014l-0.08 -0.08a0.01 0.01 0 0 1 0 -0.014l0.08 -0.08a0.01 0.01 0 0 1 0.014 0.014L0.094 0.3H0.37a0.03 0.03 0 0 0 0.03 -0.03v-0.12A0.03 0.03 0 0 0 0.37 0.12h-0.3a0.01 0.01 0 0 1 0 -0.02h0.3A0.05 0.05 0 0 1 0.42 0.15v0.12a0.05 0.05 0 0 1 -0.05 0.05z" /></svg>
        </span>
        <p>Retour</p>
    </div>
        `
        div.addEventListener('click', async () => {

            if(ContainerArticle.fluxOnFocus !== null){
                ContainerArticle.fluxOnFocus = null;
            }

            // cas où on est à la racine(n=0)
            if (arborescence.length == 0) {
                return;
            }

            // cas où on est a la racine d'un espace (n=1)
            else if (arborescence.length == 1) {
                Header.updateTitle('Tous les posts')
                arborescence = [];

                const espaces = await API.getEspaces();
                Arborescence.vider();
                Arborescence.addEspaces(espaces);

                ContainerArticle.numero_page = 0;
                const articles = await API.getAllArticles(ContainerArticle.numero_page);
                ContainerArticle.vider();
                ContainerArticle.addArticles(articles);

                document.querySelectorAll("div.categorie-active").forEach(categorie => categorie.classList.remove("categorie-active"));
                document.getElementById("tous-les-posts").classList.add("categorie-active");
            }

            // cas où on a pas encore d'id_parent pour catégorie au dessus (n=2)
            else if (arborescence.length == 2) {
                arborescence.pop();
                Header.updateTitle(arborescence.slice(-1)[0]["nom"])

                const id_espace = arborescence[0]["id"];

                const categories = await API.getCategoriesFromEspace(id_espace);
                Arborescence.vider();
                Arborescence.addBackButton();
                Arborescence.addCategories(categories);
                
                ContainerArticle.numero_page = 0;
                const articles = await API.getArticlesFromEspace(id_espace, ContainerArticle.numero_page);
                ContainerArticle.vider();
                ContainerArticle.addArticles(articles);
            }

            else {
                arborescence.pop();

                const categorie_actuelle = arborescence.slice(-1)[0];
                const id_categorie = categorie_actuelle["id"];

                Header.updateTitle(categorie_actuelle["nom"]);

                const categories = await API.getCategoriesFromCategorie(id_categorie);
                Arborescence.vider();
                Arborescence.addBackButton();
                Arborescence.addCategories(categories);

                const fluxs = await API.getFluxRSSFromCategorie(id_categorie);
                this.addFluxs(fluxs);

                ContainerArticle.numero_page = 0;
                const articles = await API.getArticlesFromCategorie(id_categorie, ContainerArticle.numero_page);
                ContainerArticle.vider();
                ContainerArticle.addArticles(articles);
            }

        });
        arbo.appendChild(div);
    }


}