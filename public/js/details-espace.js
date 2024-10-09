async function toggleAccessToWP(id_espace){
    const res = await API.toggleAccessToWP(id_espace);
    if(res.status === 200) window.location.reload();
    else alert("Erreur lors de la modification de l'accès à WordPress");
}

async function deleteEspace(id_espace){

    if(!confirm("Êtes-vous sûr de vouloir supprimer cet espace ?")) return;

    const res = await API.deleteEspace(id_espace);
    
    if(res.status === 200) window.location.href = "/espace";
    else alert("Erreur lors de la suppression de l'espace");
}