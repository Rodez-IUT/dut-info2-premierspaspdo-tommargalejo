##1
Le contenu de la table action_log a bien été modifié mais le status
de l'utilisateur n'a pas été mis à jour, il y a donc incohérence dans la
base de données.

##2
Avec les modifications apportées, un état cohérent est préservé dans la BDD.
Si les 2 requêtes ne peuvent pas êter exécutée, la transation n'est pas
effectuée et aucune des 2 requêtes n'est prise en compte.
