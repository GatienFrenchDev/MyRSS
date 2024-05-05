<?php

/**
 * Fichier regroupant quelques fonctions "pratiques" pour l'intégralité du projet.
 */

define("API_KEY", "xxxxxxxxxxxxxxxxxxx");

function extractMainDomain(string $url): string | null
{
    $parsedUrl = parse_url($url);
    if (isset($parsedUrl['host'])) {
        $hostParts = explode('.', $parsedUrl['host']);
        // Si le domaine contient plus de deux parties (par exemple : www.example.com)
        if (count($hostParts) > 2) {
            $domain = $hostParts[count($hostParts) - 2] . '.' . $hostParts[count($hostParts) - 1];
        } else {
            $domain = $parsedUrl['host'];
        }
        return $domain;
    } else {
        return null;
    }
}

/**
 * Permet de récupérer l'identifiant d'une chaine YouTube en utilisant l'API
 * de YouTube.
 * Nécessite une clé d'API à définir dans la constante intitulée `API_KEY`
 * 
 * @author gatien.gillot@etu.univ-tours.fr
 * @param username - le username de la chaine Youtube. ( eg. `nobodyplaylists`)
 * @return channelID - l'identifiant de la chaine youtube correspondante (eg. `UCsBjURrPoezykLs9EqgamOA`),
 *                     `null` si aucune chaine YouTube trouvée.
 */
function getIDFromYoutubeChannel(string $username): string | null
{
    $res = file_get_contents(sprintf("https://youtube.googleapis.com/youtube/v3/channels?part=id&forHandle=%s&key=%s", urlencode($username), constant("API_KEY")));
    $json = json_decode($res, true);

    if (isset($json["items"])) {
        return $json["items"][0]["id"];
    }
    return null;
}

function getUsernameFromYouTubeUrl($url) {
    // Extraire le chemin de l'URL
    $path = parse_url($url, PHP_URL_PATH);

    // Appliquer une expression régulière pour extraire le nom d'utilisateur
    preg_match('/\/@([^\/]+)/', $path, $matches);

    // Vérifier si un match a été trouvé
    if(isset($matches[1])) {
        return $matches[1];
    } else {
        return false; // Aucun nom d'utilisateur trouvé
    }
}
