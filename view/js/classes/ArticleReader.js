class ArticleReader {

    /**
     * @param {Article} article 
     * @returns {void}
     */
    static async afficher (article) {
        // container
        const article_component = document.createElement('div');
        article_component.id = "article-reader";

        // titre
        const titre_component = document.createElement("h1");
        titre_component.innerText = article.titre;
        titre_component.addEventListener("click", () => {
            window.open(
                article.url_article,
                '_blank'
            );
        });

        // banniere contenant la source et date de publication
        const banner_component = document.createElement("div");
        banner_component.id = "article-banner";

        // logo de la source
        const img_component = document.createElement("img");
        img_component.id = "article-image-source";
        img_component.src = article.url_favicon;

        // nom de la source
        const nom_source_component = document.createElement("span");
        nom_source_component.id = "article-source";
        nom_source_component.innerText = article.nom_source;
        // date de publication de l'article
        const date_pub_component = document.createElement("span");
        date_pub_component.id = "article-date";
        date_pub_component.innerText = article.getMomentAgo();


        const container_actions = document.createElement("div");
        container_actions.id = "container-actions";

        const btn_add_to_favorites = BoutonAjoutFavoris.getHTML();

        const appartientAuFavoris = await API.articleDansFavoris(article.id_article);

        if(appartientAuFavoris){
            btn_add_to_favorites.classList.add("starred");
            btn_add_to_favorites.children[1].innerText = "";
        }

        btn_add_to_favorites.addEventListener("click", async () => {
            await API.addToFavorites(article.id_article);
            // cas où on a cliqué pour retirer le favori
            if (btn_add_to_favorites.classList.contains("starred")) {
                btn_add_to_favorites.classList.remove("starred");
                btn_add_to_favorites.children[1].innerText = "Ajouter aux favoris";
            }
            // cas où on a cliqué pour ajouter aux favoris
            else {
                btn_add_to_favorites.classList.add("starred");
                btn_add_to_favorites.children[1].innerText = "";
            }
        });

        /**
         * Partie concernant le bouton ajouter aux articles traités
         */
        if(arborescence.length > 0){
            if(typeof arborescence[0] !== "string"){

                const btn_traite = BoutonArticleTraite.getHTML();
                
                const appartientAuTraite = await API.articleDansTraite(article.id_article, arborescence[0]["id"]);

                if(appartientAuTraite){
                    btn_traite.classList.add("starred");
                    btn_traite.children[1].innerText = "";
                }

                btn_traite.addEventListener("click", async () => {
                    // le code HTML dans le container d'article
                    const articleDOM = document.querySelector(`.article-${article.id_article}`);
                    console.log(article.id_article)

                    await API.addToTraite(article.id_article, arborescence[0]["id"]);
                    if (btn_traite.classList.contains("starred")) {
                        if(articleDOM != null){
                            articleDOM.classList.remove("est-traite");
                        }
                        btn_traite.classList.remove("starred");
                        btn_traite.children[1].innerText = "Marquer comme traité";
                    }
                    else {
                        btn_traite.classList.add("starred");
                        if(articleDOM != null){
                            articleDOM.classList.add("est-traite");
                        }
                        btn_traite.children[1].innerText = "";
                    }
                });

                container_actions.appendChild(btn_traite);

            }
        }

        container_actions.appendChild(btn_add_to_favorites);


        // description de l'article
        const description_component = document.createElement("p");
        description_component.innerHTML = article.description;

        if(article.url_image != ""){
            const img_illustration = document.createElement("img");
            img_illustration.src = article.url_image;
            description_component.appendChild(img_illustration);
        }
        
        const iframe = document.createElement("iframe");

        // ajout de l'iframe de la video youtube
        if (article.estUneVideoYT()) {
            const id_video = article.url_article.split("https://www.youtube.com/watch?v=")[1];
            iframe.src = `https://www.youtube.com/embed/${id_video}`;
            iframe.style.height = "50vh";
            iframe.style.marginTop = "20px";
            // pour ne pas avoir de problèmes avec les retours à la ligne
            description_component.innerHTML = "";
            description_component.innerText = article.description;
        }


        banner_component.appendChild(img_component);
        banner_component.appendChild(nom_source_component);
        banner_component.appendChild(date_pub_component);

        article_component.appendChild(titre_component);
        article_component.appendChild(banner_component);

        article_component.appendChild(container_actions);

        article_component.appendChild(description_component);


        if (article.estUneVideoYT()) {
            article_component.appendChild(iframe);
        }

        document.getElementById("article-reader").replaceWith(article_component);
    }
}
