class BoutonAfficherTags{


    static code_svg_down = `<svg height="14" width="14" style="margin-right: 0;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
    viewBox="0 0 330 330" xml:space="preserve">
<path id="XMLID_225_" d="M325.607,79.393c-5.857-5.857-15.355-5.858-21.213,0.001l-139.39,139.393L25.607,79.393
   c-5.857-5.857-15.355-5.858-21.213,0.001c-5.858,5.858-5.858,15.355,0,21.213l150.004,150c2.813,2.813,6.628,4.393,10.606,4.393
   s7.794-1.581,10.606-4.394l149.996-150C331.465,94.749,331.465,85.251,325.607,79.393z"/>
</svg>`;


    static getHTML(){

        const btn_show_tags = document.createElement("button");
        btn_show_tags.id = "btn-show-tags";

        btn_show_tags.innerHTML = this.code_svg_down;


        btn_show_tags.addEventListener("click", () => {
            const c_tag = document.getElementById("container-tags");
            if(c_tag.classList.contains("hidden")){
                c_tag.classList.remove("hidden");
                btn_show_tags.style.transform = 'rotate(180deg)'
            }
            else{
                c_tag.classList.add("hidden");
                btn_show_tags.style.transform = ''
            }
        })

        return btn_show_tags;
    }
}


