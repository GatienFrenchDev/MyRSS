let arborescence = [];
let espace_actif = null;

moment.locale('fr');

async function setup() {
    arborescence = [];
    espace_actif = null;

    const articles = await API.getAllArticles(ContainerArticle.numero_page);
    const espaces = await API.getEspaces();

    Arborescence.vider();
    ContainerArticle.vider();

    let nb_total_non_lu = 0;

    espaces.forEach(espace => {
        Arborescence.addEspace(espace);
        nb_total_non_lu += espace.nb_non_lu;
    });

    document.getElementById("nb-total-non-lu").innerText = nb_total_non_lu;

    ContainerArticle.addArticles(articles);
}

setup()

new BoutonAjoutDossier();



document.getElementById("tous-les-posts").addEventListener("click", async (e) => {
    document.querySelectorAll("div.categorie-active").forEach(categorie => categorie.classList.remove("categorie-active"));
    document.getElementById("tous-les-posts").classList.add("categorie-active");
    setup();
})

// Ferme le context menu lors d'un clic sur la page
document.body.addEventListener("click", () => {
    document.getElementById("context-menu").style.display = "none";
})


