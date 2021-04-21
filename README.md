# HappyBizTrip

Mise à jour de Symfony 5.1.* vers symfony 5.2.*
1. Exécuter : git checkout -b testing_new_symfony pour créer une branche pour l'upgrade
2. dans le fichier composer.json, modifier toutes les lignes contenant symfony/ la version 5.1.* par 5.2.*
3. Exécuter : composer update "symfony/*" --with-all-dependencies
4. Exécuter : yarn install --force pour rafraichier les dépendances des scripts javascript

Mise à jour des autres recettes
1. Exécuter : composer recipes pour voir les recettes à mettre à jour
2. Exécuter composer recipes "nom de la recette" pour voir la version actuelle, la nouvelle version et les fichiers impactés.

