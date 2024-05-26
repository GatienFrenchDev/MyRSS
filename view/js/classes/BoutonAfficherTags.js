class BoutonAfficherTags{
    static getHTML(){

        const btn_show_tags = document.createElement("button");
        btn_show_tags.id = "btn-show-tags";

        btn_show_tags.innerHTML = `<svg height="18" width="18" style="margin-right:0px;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 486.82 486.82" xml:space="preserve"><path d="M486.82,21.213L465.607,0l-42.768,42.768H238.991L0,281.759L205.061,486.82l238.992-238.991V63.98L486.82,21.213zM414.053,235.403L205.061,444.394L42.427,281.759L251.418,72.768h141.421l-40.097,40.097c-14.56-6.167-32.029-3.326-43.898,8.543c-15.621,15.621-15.621,40.948,0,56.569c15.621,15.621,40.948,15.621,56.568,0c11.869-11.869,14.71-29.338,8.543-43.898l40.097-40.097V235.403z"></svg>`;


        btn_show_tags.addEventListener("click", () => {
            const c_tag = document.getElementById("container-tags");
            if(c_tag.classList.contains("hidden")){
                c_tag.classList.remove("hidden");
            }
            else{
                c_tag.classList.add("hidden");
            }
        })

        return btn_show_tags;
    }
}


