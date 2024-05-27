class TagItem{

    constructor(id, nom, est_selectionne){
        this.id = id;
        this.nom = nom;
        this.est_selectionne = est_selectionne;
    }

    getHTML(){
        const button = document.createElement("button");
        button.classList.add("tag");
        button.id = `tag-${this.id}`;
        
        if(this.est_selectionne){
            button.classList.add("select");
        }
        
        button.innerHTML = `<p>${this.nom}</p>`;

        button.addEventListener("click", () => {
            if(this.est_selectionne){
                this.est_selectionne = false;
                button.classList.remove("select");
                API.addArticleToCollection(document.querySelector("#article-reader>h1").id,this.id)
            }
            else{
                this.est_selectionne = true;
                button.classList.add("select");
                API.addArticleToCollection(document.querySelector("#article-reader>h1").id,this.id)
            }
        })

        return button;
    }
}