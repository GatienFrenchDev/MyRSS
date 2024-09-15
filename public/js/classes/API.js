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
                element["est_lu"] == 1,
                element["est_traite"] == 1,
                element["url_image"]
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
                element["est_lu"] == 1,
                element["est_traite"] == 1,
                element["url_image"]
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
                element["est_lu"] == 1,
                element["est_traite"] == 1,
                element["url_image"]
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
                element["nb_non_lu"],
                element["role"],
                element["article_wp"] == 1
            )
            espaces.push(espace);
        });
        return espaces;

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

    static async quitterEspace(id_espace) {
        await fetch(`api/quitter-espace.php?id_espace=${id_espace}`);
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
     * @param {Boolean} lecteur_seul
     */
    static async ajouterEmailAUnEspace(id_espace, email, lecteur_seul) {
        await fetch(`api/inviter.php?id_espace=${encodeURIComponent(id_espace)}&email=${encodeURIComponent(email)}&reader_only=${encodeURIComponent(lecteur_seul)}`);
    }

    /**
     * 
     * @param {Number} id_flux - l'id du flux à recommander
     * @param {Number} mail_destinataire - le mail de l'utilisateur à qui la notification sera envoyée
     */
    static async recommanderFlux(id_flux, mail_destinataire) {
        await fetch(`api/recommander-flux.php?id_flux=${encodeURIComponent(id_flux)}&mail_destinataire=${encodeURIComponent(mail_destinataire)}`);
    }

    static async supprimerFlux(id_flux, id_categorie) {
        await fetch(`api/supprimer-flux.php?id_flux=${encodeURIComponent(id_flux)}&id_categorie=${encodeURIComponent(id_categorie)}`);
    }

    static async getArticlesNonLu(numero_page) {
        const request = await fetch(`api/get-articles-non-lu.php?numero_page=${numero_page}`, { method: 'GET' });
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
                false,
                false,
                element["url_image"]
            )
            articles.push(article);
        });
        return articles;
    }

    static async addToFavorites(id_article) {
        const data = new FormData();
        data.append("id_article", id_article);
        data.append("id_collection", 1);
        await fetch(`api/add-or-remove-to-collection.php`, {
            method: "POST",
            body: data
        });
    }

    static async addToTraite(id_article, id_espace) {
        const data = new FormData();
        data.append("id_article", id_article);
        data.append("id_espace", id_espace);
        await fetch(`api/add-or-remove-to-traite.php`, {
            method: "POST",
            body: data
        });
    }

    /**
     * 
     * @param {Number} id_article 
     * @returns {Boolean}
     */
    static async articleDansFavoris(id_article) {
        const request = await fetch(`api/est-dans-collection.php?id_article=${id_article}&id_collection=1`, { method: 'GET' });
        const json = await request.json();
        return json["res"];
    }

    /**
     * Renvoie vrai si l'article est noté comme traité dans cet espace
     * @param {Number} id_article 
     * @param {Number} id_espace 
     * @returns {Boolean}
     */
    static async articleDansTraite(id_article, id_espace) {
        const request = await fetch(`api/est-traite.php?id_article=${id_article}&id_espace=${id_espace}`, { method: 'GET' });
        const json = await request.json();
        return json["res"];
    }


    static async getArticlesFavoris(numero_page) {
        const request = await fetch(`api/get-articles-favoris.php?numero_page=${numero_page}`, { method: 'GET' });
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
                false,
                false,
                element["url_image"]
            )
            articles.push(article);
        });
        return articles;
    }

    static async getArticlesFromRecherche(query) {
        const request = await fetch(`api/recherche.php?${query}`, { method: 'GET' });
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
                true,
                false,
                element["url_image"]
            )
            articles.push(article);
        });
        return articles;
    }

    static async getCollections(id_article) {
        const request = await fetch(`api/get-collections.php?id_article=${id_article}`, { method: 'GET' });
        const json = await request.json();
        return json["collections"];
    }

    /**
     * 
     * @returns {Collection[]}
     */
    static async getAllCollections() {
        const request = await fetch(`api/get-collections.php`, { method: 'GET' });
        const json = await request.json();
        let collections = [];

        json["collections"].forEach(element => {
            const collection = new Collection(
                element["id_collection"],
                element["nom"]
            )
            collections.push(collection);
        });

        return collections;
    }

    /**
     * 
     * @param {Number} id_article 
     * @param {Number} id_collection 
     */
    static async addArticleToCollection(id_article, id_collection) {
        const data = new FormData();
        data.append("id_article", id_article);
        data.append("id_collection", id_collection);
        await fetch(`api/add-or-remove-to-collection.php`, {
            method: "POST",
            body: data
        });
    }

    /**
     * 
     * @param {String} nom - nom qui sera donné à la collection qui va être crée.
     * @returns {Number} - id de la collection qui vient d'être crée.
     */
    static async createNewCollection(nom) {
        const data = new FormData();
        data.append("nom", nom);
        const req = await fetch(`api/create-collection.php`, {
            method: "POST",
            body: data
        });

        const json = await req.json();

        return json["id_collection"]

    }

    static async ajouterLecteurAUnEspace(id_espace, email) {
        const data = new FormData();
        data.append("id_espace", id_espace);
        data.append("email", email);
        const req = await fetch(`api/ajout-lecteur.php`, {
            method: "POST",
            body: data
        });
    }


    static async getArticlesFromCollection(id_collection, numero_page) {
        const request = await fetch(`api/get-articles.php?id_collection=${id_collection}&numero_page=${numero_page}`, { method: 'GET' });
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
                true,
                false,
                element["url_image"]
            )
            articles.push(article);
        });
        return articles;
    }

    static async deleteCollection(id_collection) {
        await fetch(`api/delete-collection.php?id_collection=${id_collection}`);
    }

    static async renommerFlux(id_flux, id_categorie, nouveau_nom) {
        const data = new FormData();
        data.append("id_flux", id_flux);
        data.append("id_categorie", id_categorie);
        data.append("nom", nouveau_nom);
        await fetch(`api/renommer-flux.php`, {
            method: "POST",
            body: data
        });
    }

    static async getParticipantsEspace(id_espace) {
        const request = await fetch(`api/get-participants.php?id_espace=${id_espace}`, { method: 'GET' });
        const json = await request.json();
        return json["participants"];
    }

    static async sendArticleToWP(id_article, id_espace, categorie) {
        const data = new FormData();
        data.append("id_article", id_article);
        data.append("id_espace", id_espace);
        data.append("categorie", categorie);
        await fetch(`api/send-article-to-wp.php`, {
            method: "POST",
            body: data
        });
    }

    static async deleteRule(id_rule) {
        const data = new FormData();
        data.append("id_rule", id_rule);
        await fetch(`api/delete-rule.php`, {
            method: "POST",
            body: data
        });
    }

    static async deleteNotification(id_notification) {
        const data = new FormData();
        data.append("id_notification", id_notification);
        await fetch(`api/delete-notification.php`, {
            method: "POST",
            body: data
        });
    }
}