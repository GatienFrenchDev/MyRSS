class API {

    /**
     * 
     * @param {Number} id_espace
     * @param {Number} numero_page
     * @returns {Array}
     */
    static async getArticlesFromEspace(id_espace, numero_page) {
        const request = await fetch(`api/get-articles.php?id_espace=${encodeURIComponent(id_espace)}&numero_page=${numero_page}`, { method: 'GET' });
        const json = await request.json();
        let articles = [];

        json["articles"].forEach(element => {
            const article = new Article(
                element["id_article"],
                element["titre"],
                element["description"],
                element["date_pub"],
                element["url_article"],
                element["nom"],
                `http://www.google.com/s2/favicons?domain=${Tools.extractDomain(element["adresse_url"])}`,
                element["est_lu"]==1
            )
            articles.push(article);
        });
        return articles;
    }

    /**
     * 
     * @param {int} id_espace
     * @returns {Categorie[]}
     */
    static async getCategoriesFromEspace(id_espace) {
        const request = await fetch(`api/get-categories.php?id_espace=${encodeURIComponent(id_espace)}`, { method: 'GET' });
        const json = await request.json();
        let categories = [];
        json["categories"].forEach(element => {
            const categorie = new Categorie(
                element["nom"],
                element["id_categorie"],
                element["nb_non_lu"]
            )
            categories.push(categorie);
        });
        return categories;
    }

    /**
     * 
     * @param {int} id_categorie
     * @returns {Categorie[]}
     */
    static async getCategoriesFromCategorie(id_categorie) {
        const request = await fetch(`api/get-categories.php?id_categorie=${encodeURIComponent(id_categorie)}`, { method: 'GET' });
        const json = await request.json();
        let categories = [];

        json["categories"].forEach(element => {
            const categorie = new Categorie(
                element["nom"],
                element["id_categorie"],
                element["nb_non_lu"]
            )
            categories.push(categorie);
        });
        return categories;
    }

    /**
     * 
     * @param {Number} id_categorie 
     * @returns {FluxRSS[]}
     */
    static async getFluxRSSFromCategorie(id_categorie) {
        const request = await fetch(`api/get-flux-rss.php?id_categorie=${encodeURIComponent(id_categorie)}`, { method: 'GET' });
        const json = await request.json();

        let fluxs = [];

        json["flux_rss"].forEach(element => {
            const flux = new FluxRSS(
                element["id_flux"],
                element["nom"],
                Tools.extractDomain(element["adresse_url"]),
                element["nb_non_lu"]
            )
            fluxs.push(flux);
        });
        return fluxs;

    }

    /**
     * 
     * @param {Number} numero_page
     * @param {Number} id_categorie 
     * @returns {Article[]}
     */
    static async getArticlesFromCategorie(id_categorie, numero_page) {
        const request = await fetch(`api/get-articles.php?id_categorie=${encodeURIComponent(id_categorie)}&numero_page=${numero_page}`, { method: 'GET' });
        const json = await request.json();
        let articles = [];

        json["articles"].forEach(element => {
            const article = new Article(
                element["id_article"],
                element["titre"],
                element["description"],
                element["date_pub"],
                element["url_article"],
                element["nom"],
                `http://www.google.com/s2/favicons?domain=${Tools.extractDomain(element["adresse_url"])}`,
                element["est_lu"]==1
            )
            articles.push(article);
        });
        return articles;
    }

        /**
     * 
     * @param {Number} id_flux 
     * @returns {Article[]}
     */
        static async getArticlesFromFlux(id_flux, numero_page) {
            const request = await fetch(`api/get-articles.php?id_flux=${encodeURIComponent(id_flux)}&numero_page=${encodeURIComponent(numero_page)}`, { method: 'GET' });
            const json = await request.json();
            let articles = [];
    
            json["articles"].forEach(element => {
                const article = new Article(
                    element["id_article"],
                    element["titre"],
                    element["description"],
                    element["date_pub"],
                    element["url_article"],
                    element["nom"],
                    `http://www.google.com/s2/favicons?domain=${Tools.extractDomain(element["adresse_url"])}`,
                    element["est_lu"]==1
                )
                articles.push(article);
            });
            return articles;
        }

    /**
     * @returns {Espace[]}
     */
    static async getEspaces() {
        const request = await fetch(`api/get-all-espaces.php`, { method: 'GET' });
        const json = await request.json();
        let espaces = [];

        json["espaces"].forEach(element => {
            const espace = new Espace(
                element["nom"],
                element["id_espace"],
                element["nb_non_lu"]
            )
            espaces.push(espace);
        });
        return espaces;

    }

    /**
     * 
     * @param {Number} numero_page - commence à 0.
     * @returns {Article[]}
     */
    static async getAllArticles(numero_page) {
        const request = await fetch(`api/get-articles.php?numero_page=${numero_page}`, { method: 'GET' });
        const json = await request.json();

        let articles = [];

        json["articles"].forEach(element => {
            const article = new Article(
                element["id_article"],
                element["titre"],
                element["description"],
                element["date_pub"],
                element["url_article"],
                element["nom"],
                `http://www.google.com/s2/favicons?domain=${Tools.extractDomain(element["adresse_url"])}`
            )
            articles.push(article);
        });
        return articles;
    }

    /**
     * 
     * @param {String} nom 
     * @param {Number} id_categorie_parent 
     * @param {Number} id_espace 
     * @returns {Categorie}
     */
    static async createNewCategorie(nom, id_categorie_parent, id_espace) {
        const request = await fetch(`api/create-new-categorie.php?nom=${encodeURIComponent(nom)}&id_categorie_parent=${encodeURIComponent(id_categorie_parent)}&id_espace=${encodeURIComponent(id_espace)}`, { method: 'GET' });
        const json = await request.json();
        if (!json.hasOwnProperty("id_categorie")) {
            return null;
        }
        return new Categorie(nom, json["id_categorie"], 0);
    }

    /**
     * 
     * @param {String} nom_espace 
     * @returns {Espace}
     */
    static async createNewEspace(nom_espace) {
        const request = await fetch(`api/create-new-espace.php?nom=${encodeURIComponent(nom_espace)}`, { method: 'GET' });
        const json = await request.json();

        if (!json.hasOwnProperty("id_espace")) {
            return null;
        }
        return new Espace(nom_espace, json["id_espace"], 0);
    }

    /**
     * 
     * @param {Number} id_article 
     * @param {Number} id_espace 
     * @returns {void}
     */
    static async setArticleLu(id_article, id_espace) {
        const request = await fetch(`api/est-lu.php?id_article=${encodeURIComponent(id_article)}&id_espace=${encodeURIComponent(id_espace)}`, { method: 'GET' });
    }

    /**
     * 
     * @param {Number} id_article 
     * @param {Number} id_espace 
     * @returns {void}
     */
    static async setArticleNonLu(id_article, id_espace) {
        const request = await fetch(`api/est-non-lu.php?id_article=${encodeURIComponent(id_article)}&id_espace=${encodeURIComponent(id_espace)}`, { method: 'GET' });
    }

    /**
     * 
     * @param {Number} id_categorie 
     * @param {String} nouveau_nom 
     */
    static async renameCategorie(id_categorie, nouveau_nom) {
        await fetch(`api/rename-categorie.php?id_categorie=${id_categorie}&nom=${nouveau_nom}`);
    }

    /**
     * 
     * @param {Number} id_espace 
     * @param {String} nouveau_nom 
     */
    static async renameEspace(id_espace, nouveau_nom) {
        await fetch(`api/rename-espace.php?id_espace=${id_espace}&nom=${nouveau_nom}`);
    }

    /**
     * 
     * @param {Number} id_espace 
     * @returns {void}
     */
    static async supprimerEspace(id_espace) {
        await fetch(`api/suppresion.php?id_espace=${id_espace}`);
    }

    /**
     * 
     * @param {Number} id_categorie 
     */
    static async supprimerCategorie(id_categorie) {
        await fetch(`api/suppresion.php?id_categorie=${id_categorie}`);
    }

    /**
     * 
     * @param {Number} id_espace 
     * @param {String} email
     */
    static async inviterEmailaUnEspace(id_espace, email) {
        await fetch(`api/inviter.php?id_espace=${encodeURIComponent(id_espace)}&email=${encodeURIComponent(email)}`);
    }

    /**
     * 
     * @param {Number} id_flux - l'id du flux à recommander
     * @param {Number} mail_destinataire - le mail de l'utilisateur à qui la notification sera envoyée
     */
    static async recommanderFlux(id_flux, mail_destinataire){
        await fetch(`api/recommander-flux.php?id_flux=${encodeURIComponent(id_flux)}&mail_destinataire=${encodeURIComponent(mail_destinataire)}`);
    }

    static async supprimerFlux(id_flux, id_categorie){
        await fetch(`api/supprimer-flux.php?id_flux=${encodeURIComponent(id_flux)}&id_categorie=${encodeURIComponent(id_categorie)}`);
    }



}