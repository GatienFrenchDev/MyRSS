(async () => {
    const collections = await API.getAllCollections();
    const arborescence = document.getElementById("arborescence");
    collections.forEach(collection => {
        arborescence.appendChild(collection.getHTML());
    });
    new BoutonAjoutDossier();
})()