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
        
        if(!est_selectionne){
            button.classList.add("non-select");
        }
        
        button.innerHTML = `<p>${this.nom}</p><span><svg fill="#000000" height="10" width="10" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
        viewBox="0 0 490 490" xml:space="preserve">
   <polygon points="456.851,0 245,212.564 33.149,0 0.708,32.337 212.669,245.004 0.708,457.678 33.149,490 245,277.443 456.851,490 
       489.292,457.678 277.331,245.004 489.292,32.337 "/>
   </svg></span>`;

        button.addEventListener("click", () => {
            if(this.est_selectionne){
                this.est_selectionne = false;
                API.blablabla
                //
            }
            else{
                this.est_selectionne = true;
            }
        })

        return button;
    }
}