class ContextMenu{

    static vider(){
        document.getElementById('context-menu').innerHTML = "";
    }

    /**
     * 
     * @param {String} nom 
     * @returns {HTMLDivElement}
     */
    static addItem(nom){
        const item = document.createElement("div");
        item.innerText = nom;
        document.getElementById('context-menu').appendChild(item);
        return item;
    }

    /**
     * 
     * @param {Number} x 
     * @param {Number} y 
     */
    static afficher(x, y){
        const context_menu = document.getElementById('context-menu');
        context_menu.style.display = "grid";
        context_menu.style.left = x + "px";
        context_menu.style.top = y + "px";
    }
}