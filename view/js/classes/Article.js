/**
 * Représente un article d'actualité.
 * @class
 */
class Article {
    /**
     * Crée une instance d'un article.
     * @constructor
     * @param {Number} id_article - L'identifiant unique de l'article.
     * @param {String} titre - Le titre de l'article.
     * @param {String} description - La description ou le contenu de l'article.
     * @param {Number} date_pub - La date de publication de l'article en timestamp UNIX
     * @param {String} url_article - L'URL de l'article.
     * @param {String} nom_source - Le nom de la source de l'article.
     * @param {String} url_image - L'URL de l'image associée à l'article.
     * @param {boolean} est_lu - Indique si l'article a été lu ou non.
     */
    constructor(id_article, titre, description, date_pub, url_article, nom_source, url_image, est_lu) {
        this.id_article = id_article;
        this.titre = titre;
        this.description = description;
        this.date_pub = date_pub;
        this.url_article = url_article;
        this.nom_source = nom_source;
        this.url_image = url_image;
        this.est_lu = est_lu;
    }

    /**
     * 
     * @returns {HTMLElement}
     */
    getHTMLElement(){
        const article = document.createElement('div');

        const div_article_description = document.createElement('div');
        const element_titre = document.createElement('span');
        const element_description = document.createElement('span');


        const banner = document.createElement('div');
        const image = document.createElement('img');
        const source = document.createElement('span');
        const date = document.createElement('span');
        const pastille = document.createElement('span');
        const bordure = document.createElement('span');

        bordure.classList.add("bordure");

        if (!this.est_lu) {
            article.classList.add("article-non-lu")
        }
        article.classList.add("article");
        article.classList.add(`article-${this.id_article}`);

        div_article_description.classList.add("titre-description");

        element_titre.innerText = this.titre;
        element_titre.classList.add("titre");


        element_description.innerText = this.description;
        element_description.innerText = element_description.innerText;
        element_description.classList.add("description");

        banner.classList.add("banner");

        image.src = this.url_image;
        image.height = 16;
        image.width = 16;

        source.innerText = this.nom_source;
        source.classList.add("source");


        date.innerText = this.getMomentAgo();
        date.classList.add("date");

        pastille.classList.add("non-lu");

        div_article_description.appendChild(element_titre);
        div_article_description.appendChild(element_description);

        banner.appendChild(image);
        banner.appendChild(source);
        banner.appendChild(date);
        banner.appendChild(pastille);

        article.appendChild(bordure);
        article.appendChild(div_article_description);
        article.appendChild(banner);

        article.addEventListener('click', async () => {
            document.querySelectorAll("div.article-actif").forEach(article => {
                article.classList.remove("article-actif");
            })
            if(article.classList.contains("article-non-lu") && espace_actif){
                await API.setArticleLu(this.id_article, espace_actif["id_espace"]);
                article.classList.remove("article-non-lu");
            }

            ArticleReader.afficher(this);
            article.classList.add("article-actif");
        })
    
        article.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            if(article.classList.contains("article-non-lu") || !espace_actif){
                return;
            }
            API.setArticleNonLu(this.id_article, espace_actif["id_espace"]);
            article.classList.add("article-non-lu");
        })

        return article;
    }

    /**
     * Renvoie qque chose du style `il y 3 heures`
     * @returns {String}
     */
    getMomentAgo(){
        return moment.unix(this.date_pub).fromNow();
    }

    /**
     * @returns {Boolean}
     */
    estUneVideoYT(){
        return this.url_article.startsWith("https://www.youtube.com/watch?");
    }
}
