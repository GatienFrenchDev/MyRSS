class Espace {
    /**
     * 
     * @param {String} nom 
     * @param {Number} id_espace 
     * @param {Number} nb_non_lu 
     * @param {Boolean} est_proprietaire 
     */
    constructor(nom, id_espace, nb_non_lu, est_proprietaire) {
        this.nom = nom;
        this.id_espace = id_espace;
        this.nb_non_lu = nb_non_lu;
        this.est_proprietaire = est_proprietaire;
    }

    /**
     * 
     * @returns {HTMLDivElement}
     */
    getHTMLElement() {
        const DIVespace = document.createElement('div');
        DIVespace.id = `espace-${this.id_espace}`;
        DIVespace.classList.add('dossier');
        DIVespace.innerHTML = `
    <div>
    <span>
    <svg fill="#000000" version="1.1" class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
    width="18" height="18" viewBox="0 0 49.055 49.056">
       <path d="M13.988,37.665c2.689,0,4.879-2.188,4.879-4.88c0-2.69-2.189-4.879-4.879-4.879c-2.691,0-4.879,2.188-4.879,4.879
           C9.109,35.477,11.297,37.665,13.988,37.665z"/>
       <path d="M24.732,39.717c2.688,0,4.879-2.188,4.879-4.88c0-2.688-2.188-4.878-4.879-4.878c-2.691,0-4.879,2.188-4.879,4.878
           S22.041,39.717,24.732,39.717z"/>
       <path d="M34.969,37.665c2.689,0,4.879-2.188,4.879-4.88c0-2.69-2.188-4.879-4.879-4.879s-4.879,2.188-4.879,4.879
           C30.09,35.477,32.279,37.665,34.969,37.665z"/>
       <path d="M32.105,47.463c-0.017-0.075-0.039-0.152-0.072-0.23c-0.188-0.442-0.377-0.887-0.565-1.33
           c-0.334-0.785-0.668-1.57-1.004-2.355c-0.279-0.658-0.56-1.316-0.84-1.975l0,0c-0.109-0.26-0.22-0.519-0.33-0.776
           c-0.175-0.41-0.49-0.563-0.816-0.543v-0.007h-7.249v0.01c-0.256,0.027-0.494,0.18-0.633,0.509
           c-0.363,0.859-0.729,1.72-1.092,2.578c-0.209,0.492-0.418,0.985-0.625,1.478c-0.283,0.672-0.568,1.342-0.852,2.013
           c-0.488,1.153,1.203,2.157,1.695,0.991c0.148-0.35,0.297-0.698,0.445-1.049c0.213-0.504,0.426-1.006,0.639-1.511
           c0.141-0.329,0.281-0.661,0.422-0.993v0.769v1.135v2.487c0,0.007-0.002,0.012-0.002,0.018c0,0.126,0,0.25,0,0.376h7.25
           c0-0.126,0-0.251,0-0.376v-2.753v-1.647v-0.1c0.008,0.018,0.014,0.032,0.021,0.051c0.125,0.295,0.253,0.59,0.378,0.885
           c0.448,1.058,0.899,2.113,1.348,3.168c0.252,0.594,0.799,0.654,1.242,0.424c0,0.001,0,0.001,0,0.001
           c0.004-0.001,0.008-0.005,0.012-0.007c0.047-0.024,0.092-0.055,0.137-0.086c0.012-0.008,0.023-0.016,0.035-0.023
           c0.041-0.031,0.08-0.066,0.119-0.104c0.012-0.012,0.023-0.022,0.035-0.035c0.035-0.036,0.063-0.076,0.096-0.117
           c0.012-0.016,0.023-0.029,0.035-0.045c0.025-0.04,0.049-0.082,0.07-0.125c0.01-0.021,0.021-0.039,0.03-0.061
           c0.019-0.041,0.029-0.086,0.043-0.129c0.009-0.025,0.017-0.048,0.021-0.072c0.011-0.045,0.015-0.092,0.019-0.139
           c0.002-0.025,0.009-0.05,0.009-0.074c0-0.054-0.007-0.105-0.015-0.158C32.109,47.508,32.109,47.486,32.105,47.463z"/>
       <path d="M39.531,38.744c-0.174-0.411-0.489-0.563-0.813-0.545v-0.004h-7.25v0.008c-0.256,0.027-0.494,0.18-0.633,0.51
           c-0.134,0.312-0.265,0.624-0.396,0.936c0.119,0.162,0.221,0.338,0.303,0.531l1.379,3.24l1.361,3.195
           c0.346,0.811,0.271,1.69-0.182,2.438h1.115c0-0.061,0-0.119,0-0.182h1.354c0,0.062,0,0.121,0,0.182h2.949c0-0.061,0-0.119,0-0.182
           c0-0.748,0-1.497,0-2.246v-4.497c0.582,1.368,1.164,2.735,1.746,4.104c0.524,1.233,2.33,0.17,1.809-1.053
           C41.357,43.033,40.445,40.89,39.531,38.744z"/>
       <path d="M19.148,40.15c-0.201-0.47-0.398-0.938-0.598-1.406c-0.176-0.41-0.492-0.562-0.816-0.543v-0.006h-7.25v0.01
           c-0.256,0.027-0.492,0.179-0.633,0.509c-0.855,2.022-1.713,4.045-2.568,6.067c-0.488,1.152,1.205,2.156,1.697,0.992
           c0.5-1.186,1.002-2.371,1.504-3.555v4.393c0,0.006-0.002,0.01-0.002,0.016c0,0.811,0,1.617,0,2.428h2.949c0-0.061,0-0.119,0-0.181
           h1.354c0,0.062,0,0.12,0,0.181h2.328c-0.109-0.113-0.219-0.225-0.307-0.357c-0.494-0.748-0.578-1.65-0.229-2.479l1.156-2.729
           v-1.36c0.096,0.228,0.191,0.453,0.289,0.681L19.148,40.15z"/>
       <path d="M43.402,9.79c-0.353-0.497-0.72-0.982-1.108-1.449C38.049,3.248,31.66,0,24.527,0c-0.012,0-0.023,0-0.033,0
           c-0.008,0-0.014,0-0.021,0c-0.025,0-0.053,0.003-0.08,0.003c-7.078,0.041-13.41,3.279-17.629,8.339
           C6.375,8.809,6.006,9.294,5.655,9.791c-2.672,3.771-4.25,8.369-4.25,13.332c0,4.964,1.578,9.562,4.25,13.334
           c0.352,0.496,0.721,0.982,1.109,1.449c0.314,0.377,0.645,0.74,0.982,1.097L8.172,38c0.123-0.292,0.287-0.551,0.486-0.773V37.16
           c-0.117-0.133-0.242-0.26-0.355-0.396c0.07-0.047-0.736-0.434-1.119-1.446c-2.127-3.018-3.496-6.604-3.807-10.492h7.516
           c0.002,0.016,0.004,0.031,0.004,0.047c0.029,0.644,0.076,1.278,0.135,1.905c0.576-0.285,1.199-0.488,1.855-0.599
           c-0.035-0.434-0.07-0.866-0.092-1.308c0-0.016-0.002-0.031-0.004-0.047H23.58v0.047v3.365c0.375-0.065,0.758-0.105,1.152-0.105
           c0.254,0,0.496,0.039,0.744,0.066v-3.326v-0.047h10.678c-0.002,0.016-0.004,0.031-0.004,0.047
           c-0.021,0.439-0.058,0.873-0.093,1.306c0.656,0.106,1.279,0.31,1.855,0.595c0.061-0.625,0.105-1.26,0.135-1.899
           c0-0.016,0.002-0.031,0.002-0.047h7.629c-0.311,3.888-3.047,9.582-3.805,10.494s-1.188,1.399-1.121,1.445
           c-0.067,0.08-0.143,0.155-0.213,0.235v0.063c0.281,0.26,0.51,0.586,0.67,0.965l0.318,0.746c0.262-0.283,0.518-0.572,0.764-0.869
           c0.389-0.467,0.758-0.953,1.109-1.449c2.672-3.771,4.25-8.37,4.25-13.334C47.652,18.161,46.074,13.562,43.402,9.79z M23.58,1.965
           v11.681c-3.303-0.08-6.41-0.578-9.154-1.401C16.332,6.496,19.687,2.5,23.58,1.965z M17.769,3.008
           c-2.127,2.049-3.891,5.046-5.109,8.644c-1.631-0.609-3.098-1.343-4.363-2.172C10.777,6.532,14.041,4.264,17.769,3.008z
            M10.855,22.929H3.306c0.041-4.464,1.477-8.595,3.877-11.999c1.438,0.961,3.1,1.8,4.938,2.491
           C11.328,16.319,10.873,19.534,10.855,22.929z M12.754,22.929c0.016-3.188,0.432-6.196,1.145-8.902
           c2.92,0.894,6.201,1.433,9.682,1.517v7.386L12.754,22.929L12.754,22.929z M40.756,9.481c-1.291,0.846-2.791,1.59-4.461,2.206
           c-1.229-3.646-3.02-6.679-5.18-8.736C34.917,4.194,38.238,6.489,40.756,9.481z M25.476,1.98c3.852,0.595,7.168,4.584,9.053,10.294
           c-2.719,0.805-5.791,1.293-9.053,1.372V1.98z M25.476,22.929v-7.386c3.438-0.083,6.686-0.61,9.577-1.486
           c0.709,2.698,1.121,5.697,1.14,8.872H25.476z M38.09,22.929c-0.017-3.379-0.467-6.582-1.254-9.47
           c1.879-0.698,3.573-1.551,5.037-2.528c2.397,3.403,3.836,7.534,3.875,11.998H38.09z"/>
</svg>
    </span>
    <p>${this.nom}</p>
    <p class="side-info">${this.nb_non_lu == 0 ? "" : this.nb_non_lu}</p>
</div>
    `
        document.querySelector('div#arborescence').appendChild(DIVespace);
        DIVespace.addEventListener('click', async () => {
            document.querySelectorAll("div.categorie-active").forEach(categorie => categorie.classList.remove("categorie-active"));
            espace_actif = { "id_espace": this.id_espace, "nom": this.nom };
            Header.updateTitle(this.nom);
            arborescence = [{ "id": parseInt(this.id_espace), "nom": this.nom }];

            const categories = await API.getCategoriesFromEspace(this.id_espace);
            Arborescence.vider()
            Arborescence.addBackButton();
            Arborescence.addCategories(categories);

            ContainerArticle.numero_page = 0;
            const articles = await API.getArticlesFromEspace(this.id_espace, ContainerArticle.numero_page);
            ContainerArticle.vider();
            ContainerArticle.addArticles(articles);

        })

        DIVespace.addEventListener('contextmenu', (e) => {
            e.stopPropagation();
            e.preventDefault();
            ContextMenu.afficher(e.x, e.y)
            ContextMenu.vider();
            const item_collab = ContextMenu.addItem("Ajouter un collaborateur");

            /*
            Ajout du bouton `Supprimer l'espace` et `Renommer l'espace` si l'espace a été créé par l'user.
            Sinon on ajoute le bouton `Quitter l'espace`.
            */
            if(this.est_proprietaire){

                const item_supprimer = ContextMenu.addItem("Supprimer l'espace");
                const item_renommer = ContextMenu.addItem("Renommer l'espace");

                item_renommer.addEventListener("click", async () => {
                    let nom = "";
                    while (nom == "") {
                        nom = window.prompt("Entrez le nouveau nom de l'espace");
                        if (nom === null) {
                            return;
                        }
                        if (nom.length > 32) {
                            window.alert("Nom de l'espace trop long (max 32 caractères)");
                            nom = "";
                        }
                    }
                    await API.renameEspace(this.id_espace, nom);
                    document.querySelector(`#${DIVespace.id}>div>p`).innerText = nom;
                })

                item_supprimer.addEventListener("click", () => {
                    if (confirm(`Voulez vous vraiment supprimer l'espace ${this.nom} ?`)) {
                        API.supprimerEspace(this.id_espace);
                        DIVespace.remove();
                    };
                });
            }
            else{
                const item_quitter = ContextMenu.addItem("Quitter l'espace");
                item_quitter.addEventListener("click", () => {
                    if (confirm(`Voulez vous vraiment quitter l'espace ${this.nom} ?`)) {
                        API.quitterEspace(this.id_espace);
                        DIVespace.remove();
                    };
                });
            }

            item_collab.addEventListener("click", async () => {
                let email_a_inviter = "";
                while (email_a_inviter == "") {
                    email_a_inviter = window.prompt("Entrez l'email de la personne à inviter sur l'espace");
                    if (email_a_inviter === null) {
                        return;
                    }
                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!regex.test(email_a_inviter)) {
                        window.alert("Veuillez saisir un email valide");
                        email_a_inviter = "";
                    }
                }
                await API.inviterEmailaUnEspace(this.id_espace, email_a_inviter);
            })
        })

        return DIVespace;
    }
}