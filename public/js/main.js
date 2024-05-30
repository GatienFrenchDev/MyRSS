let arborescence = [];
let espace_actif = null;

moment.locale('fr');

async function setup() {
    arborescence = [];
    espace_actif = null;
    
    Arborescence.vider();
    ContainerArticle.vider();

    const articles = await API.getAllArticles(ContainerArticle.numero_page);
    const espaces = await API.getEspaces();


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



document.getElementById("tous-les-posts").addEventListener("click", async () => {
    ContainerArticle.numero_page = 0;
    document.querySelectorAll("div.categorie-active").forEach(categorie => categorie.classList.remove("categorie-active"));
    document.getElementById("tous-les-posts").classList.add("categorie-active");
    Header.updateTitle("Tous les posts");
    setup();
})

document.getElementById("btn-afficher-non-lu").addEventListener("click", async () => {
    document.querySelectorAll("div.categorie-active").forEach(categorie => categorie.classList.remove("categorie-active"));
    document.getElementById("btn-afficher-non-lu").classList.add("categorie-active");
    arborescence = ["non-lu"];
    ContainerArticle.vider();
    Header.updateTitle("Non lu");
    ContainerArticle.numero_page = 0;
    const articles = await API.getArticlesNonLu(0);
    ContainerArticle.addArticles(articles);
})

document.getElementById("btn-afficher-favoris").addEventListener("click", async () => {
    document.querySelectorAll("div.categorie-active").forEach(categorie => categorie.classList.remove("categorie-active"));
    document.getElementById("btn-afficher-favoris").classList.add("categorie-active");
    arborescence = ["favoris"];
    ContainerArticle.vider();
    Header.updateTitle("Favoris");
    ContainerArticle.numero_page = 0;
    const articles = await API.getArticlesFavoris(0);
    ContainerArticle.addArticles(articles);
})

// Ferme le context menu lors d'un clic sur la page
document.body.addEventListener("click", () => {
    document.getElementById("context-menu").style.display = "none";
})


