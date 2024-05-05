class ArticleReader{

    /**
     * @param {Article} article 
     * @returns {void}
     */
    static afficher(article){
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
        })

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

        // description de l'article
        const description_component = document.createElement("p");
        description_component.innerHTML = article.description;

        const iframe = document.createElement("iframe");

        // ajout de l'iframe de la video youtube
        if(article.estUneVideoYT()){
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
        article_component.appendChild(description_component);
        
        
        if(article.estUneVideoYT()){
            article_component.appendChild(iframe);
        }

        document.getElementById("article-reader").replaceWith(article_component);
    }
}
