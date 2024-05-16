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
        img_component.src = article.url_image;

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

        // pour savoir si l'article est dans les favoris
        const appartientAuFavoris = await API.articleDansFavoris(article.id_article);
        if(appartientAuFavoris){
            btn_add_to_favorites.classList.add("starred");
            btn_add_to_favorites.children[1].innerText = "Retirer des favoris";
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
                btn_add_to_favorites.children[1].innerText = "Retirer des favoris";
            }
        })

        container_actions.appendChild(btn_add_to_favorites);


        // description de l'article
        const description_component = document.createElement("p");
        description_component.innerHTML = article.description;

        const iframe = document.createElement("iframe");

        // ajout de l'iframe de la video youtube
        if (article.estUneVideoYT()) {
            const id_video = article.url_article.split("https://www.youtube.com/watch?v=")[1];
            iframe.src = `https://www.youtube.com/embed/${id_video}`;
            iframe.style.height = "50vh";
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
