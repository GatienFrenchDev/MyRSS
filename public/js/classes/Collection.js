class Collection{
    /**
     * 
     * @param {Number} id 
     * @param {String} nom 
     */
    constructor(id, nom){
        this.id = id;
        this.nom = nom;
    }

    /**
     * 
     * @returns {HTMLDivElement}
     */
    getHTML(){
        const DIVCollection = document.createElement('div');
        DIVCollection.id = `collection-${this.id}`;
        DIVCollection.classList.add('dossier');
        DIVCollection.innerHTML = `
        <div>
            <span>
                <svg fill="var(--highlight-text-color)" width="18" height="18" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1521.461 53c-103.466 0-199.466 42.667-266.666 113.067-37.334 37.333-66.134 83.2-84.267 134.4-14.933 38.4-22.4 81.066-22.4 125.866 0 19.307 2.027 38.4 5.333 57.387 2.774 17.92 7.254 35.307 12.374 52.587v.106c1.813 5.974 2.666 12.16 4.693 17.92 3.2 8.534 7.467 18.134 9.6 21.334 1.067 3.2 3.2 7.466 5.333 10.666 19.2 43.734 46.934 82.134 85.334 115.2 1.493 1.28 13.44 10.134 31.36 21.76-5.76 30.187-17.6 59.52-40.854 82.88-62.506 62.4-163.84 62.4-226.346 0-62.4-62.506-62.4-163.84 0-226.346 8.426-8.32 18.773-12.694 28.373-18.774-13.013-42.24-21.867-88.32-21.867-134.72 0-56 9.494-109.973 28.694-160H745.14L-.032 1011.4l829.76 829.76 745.067-745.173V426.333c0-88.213-71.787-160-160-160h-106.56c48.96-65.6 127.68-106.666 213.226-106.666 147.2 0 266.667 119.466 266.667 266.666h106.667C1894.795 220.467 1727.328 53 1521.46 53" fill-rule="evenodd"/>
                </svg>
            </span>
            <p>${this.nom}</p>
        </div>`

        DIVCollection.addEventListener("click", async () => {
            document.querySelectorAll("div.categorie-active").forEach(categorie => categorie.classList.remove("categorie-active"));
            DIVCollection.classList.add("categorie-active");
            ContainerArticle.numero_page = 0;
            const articles = await API.getArticlesFromCollection(this.id, ContainerArticle.numero_page);
            ContainerArticle.vider();
            ContainerArticle.addArticles(articles);
        });

        return DIVCollection;
    }
}