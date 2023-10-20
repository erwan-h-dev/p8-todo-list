# Documentation Technique - Authentification dans l'Application ToDo & Co

## Introduction

Cette documentation technique explique en détail comment l'authentification a été implémentée dans l'application ToDo & Co basée sur le framework Symfony. Elle vise à fournir aux développeurs une compréhension claire du fonctionnement de l'authentification, des fichiers et des composants pertinents à modifier, ainsi que de l'emplacement de stockage des utilisateurs.

## Structure de l'Authentification

L'authentification dans l'application ToDo & Co repose sur le composant Symfony Security. Voici une vue d'ensemble de la structure de l'authentification :

### Fichiers Importants

1. `config/packages/security.yaml` (Configuration)
    
    C’est le fichier de configuration central, il définit les paramètres de sécurité de l'application, comme les fournisseurs d'authentification `providers`, les pare-feu `firewalls`, et les contrôles d'accès `access_control`.
    
    - **Modification Requise :** Vous devrez ajouter ou configurer des fournisseurs d'authentification, définir les stratégies de sécurité, et spécifier les rôles requis pour l'accès à certaines parties de l'application.
2. `src/Entity/User.php` (entités)
`src/Repository/User.php` (repository)
    
    L’entité `User` représente le modèle d’un utilisateur et le repository contient les méthodes d’accès à ceux-ci. Les utilisateurs sont stockés dans une table de base de données.
    
    - **Modification Requise :** Si nécessaire, vous pouvez personnaliser les entités pour ajouter des champs aux utilisateurs ou définir de nouvelles relations avec d'autres entités. Vous pouvez également personnaliser les repository pour effectuer des opérations spécifiques sur les utilisateurs.
3. `src/Controller/SecurityController.php` (controller)
    
    Ce controller est essentiel pour la gestion de l'authentification. Il contient les actions nécessaires pour le processus de connexion et de déconnexions.
    
    - **Modification Requise :** Si besoin, il est possible de personnaliser des méthodes comme `login`/`logout` affin d’apporter des comportements spécifiques à ces actions

### Processus d'Authentification

1. **Demande d'Authentification** :
    - Lorsqu'un utilisateur tente d'accéder à une page protégée, il est redirigé vers la page de connexion.
    - Le composant Symfony Security intercepte la demande et déclenche le processus d'authentification grâce à la configuration présente dans `sécurity.yaml`.
2. **Fournisseur d'Authentification** :
    - Le fournisseur d'authentification spécifié dans `security.yaml` est utilisé pour valider les informations d'identification de l'utilisateur.
    - Par défaut, Symfony propose un fournisseur d'authentification pour les utilisateurs stockés en base de données, mais vous pouvez personnaliser ce fournisseur si nécessaire.
3. **Utilisateurs en Base de Données** :
    - Les utilisateurs et leurs informations d'identification sont stockés dans une table de base de données. Par défaut, cette table est nommée `user`.
4. **Contrôle d'Accès** :
    - Les attributs `IsGranded` présent dans les contrôleurs définissent les contrôles d'accès. Ils permettent notamment de restreindre l'accès à certaines pages aux utilisateurs ayant les rôles appropriés. Par exemple, seuls les utilisateurs avec le rôle administrateur (ROLE_ADMIN) peuvent accéder à la gestion des utilisateurs.
- Il existe deux  manière de restreindre ces accès, soit en passant directement par le rôle de l’utilisateur. Soit en utilisant des `Voters` qui sont des fichiers permettant un contrôle plus poussés grâce à une configuration plus précise de l’accès.
---