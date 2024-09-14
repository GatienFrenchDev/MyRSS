async function deleteRule(id_rule){
    await API.deleteRule(id_rule);
    location.reload();
}