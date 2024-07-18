class Tools {
    
    /**
     * 
     * @param {String} url 
     * @returns {String}
     */
    static extractDomain(url) {
        if (!url) {
            return null;
        }
        // Utilisation d'une expression régulière pour extraire le domaine principal
        const match = url.match(/(?:https?:\/\/)?(?:[^@\n]+@)?(?:www\.)?([^:\/\n?]+)/g);
        if (match && match.length > 0) {
            const parts = match[0].split('.').slice(-2);
            return parts.join('.');
        } else {
            return null;
        }
    }

    static getRoleFromCurrentEspace(){
        return arborescence[0]['role']
    }
}