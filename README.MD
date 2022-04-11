# [Evaluation en cours de formation STUDI] Projet d'application web pour un groupe hôtelier.

## 1. Contexte de l'évaluation
Hypnos est un groupe hôtelier fondé en 2004. Propriétaire de 7 établissements dans les quatre
coins de l’hexagone, chacun de ces hôtels s’avère être une destination idéale pour les couples
en quête d’un séjour romantique à deux.
Chaque suite au design luxueux inclut des services hauts de gamme (un spa privatif
notamment), de quoi plonger pleinement dans une atmosphère chic-romantique.
Hypnos souhaiterait ne pas dépendre uniquement de sites tiers comme Booking.com pour la
location de ses chambres. C’est pourquoi le groupe hôtelier aimerait être pourvu de son
propre système de réservation sur un nouveau site web.
Information importante : Le paiement n'est pas une fonctionnalité à envisager, car il se fait
obligatoirement sur place.


## 2. Environnement technique
L'environnement technique
- Pour le front : HTML5, CSS3, Bootstrap, Javascript (Jquery)
- Pour le back, PHP 8.1 sous PDO, Symfony 5, My SQL

## 3. Procédure de mise en place
- Vérifiez si avez le .env dans votre dossier 
`git clone https://github.com/FlavienGroslevin/ECF-GroupeHypnos.git`
___
`composer install`
___
- Modifer le fichier .env pour votre BDD et ensuite :
`php bin/console doctrine:database:create`
___
`php bin/console doctrine:migrations:migrate`
___
`yarn install`
___
`yarn dev`
___
`symfony server:start`
___
- Créer un compte sur la page réservation et modifier le role dans la BDD
Vous pouvez maintenant accéder au Dashboard d'admin et créer vos données
- Vous savez désormais naviguer sur le site en local ou le déployer en ligne.
