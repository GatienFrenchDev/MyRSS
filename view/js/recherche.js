let arborescence = [];
let espace_actif = null;

moment.locale('fr');

async function setup() {

    const articles = await API.getArticlesFromRecherche(window.location.search.substring(1));

    ContainerArticle.addArticles(articles);
}

setup()
