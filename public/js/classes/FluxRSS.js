class FluxRSS {
    constructor(id_flux, nom, domaine, nb_non_lu) {
        this.id_flux = id_flux;
        this.nom = nom;
        this.domaine = domaine;
        this.nb_non_lu = nb_non_lu;
    }

    getHTMLElement() {
        const DIVFlux = document.createElement('div');
        DIVFlux.id = `flux-${this.id_flux}`;
        DIVFlux.classList.add('dossier');
        DIVFlux.innerHTML = `
        <div>
            <span>
                <img src="http://www.google.com/s2/favicons?domain=${this.domaine}" height="16" width="16">
            </span>
            <p>${this.nom}</p>
            <p class="side-info">${this.nb_non_lu}</p>
            <svg xmlns:xlink="http://www.w3.org/1999/xlink" class="icon small" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 12C10 10.8954 10.8954 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14C10.8954 14 10 13.1046 10 12Z" fill="#707070"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17 12C17 10.8954 17.8954 10 19 10C20.1046 10 21 10.8954 21 12C21 13.1046 20.1046 14 19 14C17.8954 14 17 13.1046 17 12Z" fill="#707070"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 12C3 10.8954 3.89543 10 5 10C6.10457 10 7 10.8954 7 12C7 13.1046 6.10457 14 5 14C3.89543 14 3 13.1046 3 12Z" fill="#707070"></path>
            </svg>
        </div>`;
        DIVFlux.addEventListener('click', async () => {            
            document.querySelectorAll(".flux-actif").forEach(article => { article.classList.remove("flux-actif") });
            DIVFlux.classList.add("flux-actif");
            
            ContainerArticle.fluxOnFocus = this;

            const articles = await API.getArticlesFromFlux(this.id_flux, 0);

            ContainerArticle.numero_page = 0;
            ContainerArticle.vider();
            ContainerArticle.addArticles(articles);

            Header.updateTitle(this.nom);
        });

        DIVFlux.addEventListener("contextmenu", async (e) => {
            e.stopPropagation();
            e.preventDefault();
            const context_menu = document.getElementById('context-menu');
            context_menu.style.display = "grid";
            context_menu.style.left = e.x + "px" ;
            context_menu.style.top = e.y + "px" ;

            ContextMenu.vider();

            const item_recommander = ContextMenu.addItem("Recommander ce flux");
            const item_supprimer = ContextMenu.addItem("Supprimer le flux de la catégorie");

            if(Tools.getRoleFromCurrentEspace() === "reader"){
                item_supprimer.style.display = "none";
            }

            item_recommander.addEventListener("click", async () => {
                let email_a_inviter = "";
                while (email_a_inviter == "") {
                    email_a_inviter = window.prompt("Entrez l'email de la personne à qui vous souhaitez recommander le flux");
                    if (email_a_inviter === null) {
                        return;
                    }
                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if(!regex.test(email_a_inviter)){
                        window.alert("Veuillez saisir un email valide");
                        email_a_inviter = "";
                    }
                }
                API.recommanderFlux(this.id_flux, email_a_inviter)
            });
            item_supprimer.addEventListener("click", async () => {
                API.supprimerFlux(this.id_flux, arborescence.slice(-1)[0]["id"]);
                location.reload(); // oui il y a mieux mais c'est déjà bien comme ca
            });
        });

        return DIVFlux;
    }
}