class ContainerArticle {

    static container = document.querySelector("div#container-articles");
    static numero_page = 0;

    static vider() {
        this.container.innerHTML = "";
    }

    static ajoutContainerNavigation() {

        const container_navigation = document.createElement("div");
        container_navigation.id = "container_pagination";
        const btn_precedent = document.createElement("button");
        btn_precedent.innerHTML = `<svg fill="#000000" height="12" width="12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
        viewBox="0 0 476.213 476.213" xml:space="preserve">
   <polygon points="476.213,223.107 57.427,223.107 151.82,128.713 130.607,107.5 0,238.106 130.607,368.714 151.82,347.5 
       57.427,253.107 476.213,253.107 "/>
   </svg>`;
        const bnt_suivant = document.createElement("button");
        bnt_suivant.innerHTML = `<svg viewBox="0 0 476.213 476.213" height="12" width="12" fill="#000000" xmlns="http://www.w3.org/2000/svg">
        <polygon points="476.213 223.107 57.427 223.107 151.82 128.713 130.607 107.5 0 238.106 130.607 368.714 151.82 347.5 57.427 253.107 476.213 253.107" style="transform-origin: 238.107px 238.107px;" transform="matrix(-1, 0, 0, -1, 0.000039387737, 0.000008870177)"/>
      </svg>`;
      const balise_num_page = document.createElement("span");
      balise_num_page.innerText = this.numero_page+1;


        if (this.numero_page > 0) {
            btn_precedent.addEventListener("click", async () => {
                this.vider();
                let articles = [];
                if(arborescence.length == 0){
                    articles = await API.getAllArticles(this.numero_page - 1);
                }
                else if(arborescence.length == 1){
                    articles = await API.getArticlesFromEspace(arborescence[0]["id"], this.numero_page - 1);
                }
                else if(arborescence.length > 1){
                    articles = await API.getArticlesFromCategorie(arborescence.slice(-1)[0]["id"], this.numero_page - 1);
                }
                this.numero_page--;
                this.addArticles(articles);
            });
        }
        else{
            btn_precedent.style.opacity = 0.3;
        }

        bnt_suivant.addEventListener("click", async () => {
            this.vider();
            let articles = [];
            if(arborescence.length == 0){
                articles = await API.getAllArticles(this.numero_page + 1);
            }
            else if(arborescence.length == 1){
                articles = await API.getArticlesFromEspace(arborescence[0]["id"], this.numero_page + 1);
            }
            else if(arborescence.length > 1){
                articles = await API.getArticlesFromCategorie(arborescence.slice(-1)[0]["id"], this.numero_page + 1);
            }
            this.numero_page++;
            this.addArticles(articles);
        });


        if(this.getNombreArticlesDansContainer() > 0){
            container_navigation.appendChild(btn_precedent);
            container_navigation.appendChild(balise_num_page);
        }

        if (this.getNombreArticlesDansContainer() > 50) {
            container_navigation.appendChild(bnt_suivant);
        }
        this.container.appendChild(container_navigation);
    }

    /**
     * 
     * @param {Article[]} articles 
     */
    static addArticles(articles) {
        articles.forEach(article => {
            this.addArticle(article);
        });
        this.ajoutContainerNavigation();
    }

    /**
     * 
     * @param {Article} article 
     */
    static addArticle(article) {
        const HTMLArticle = article.getHTMLElement();
        this.container.appendChild(HTMLArticle);
    }

    /**
     * @returns {Number}
     */
    static getNombreArticlesDansContainer() {
        return document.querySelectorAll("div#container-articles>div.article").length;
    }

}