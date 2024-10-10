async function shareArticle(id_article, id_utilisateur){
    const res = await API.shareArticle(id_article, id_utilisateur);
    if(!res.ok) return alert("Erreur lors du partage de l'article");
    alert("Article partagé avec succès");
    location.replace("/");
}