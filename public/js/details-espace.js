async function toggleAccessToWP(espace_id){
    const res = await API.toggleAccessToWP(espace_id);
    if(res.status === 200) window.location.reload();
    else alert("Erreur lors de la modification de l'accès à WordPress");
}