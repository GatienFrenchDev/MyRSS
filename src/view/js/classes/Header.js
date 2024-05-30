class Header{
    /**
     * 
     * @param {String} title 
     */
    static updateTitle(title){
        document.querySelector('div.article-header>span').innerText = title;
    }
}