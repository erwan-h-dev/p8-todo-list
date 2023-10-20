
# Guide de Contribution

## Récupération du Code Source

Commencez par suivre les étapes d'installation du projet. [INSTALLATION](#installation)

## Créer une Branche

```bash
git checkout -b nom_de_votre_branche
```

## Développement

1. **Code Conventions :** Suivez les conventions de codage définies dans la documentation technique du projet [DOCUMENTATION.md](DOCUMENTATION.md).

2. **Tests Automatisés :** Assurez-vous d'ajouter des tests unitaires et fonctionnels pertinents pour votre code. Exécutez les tests localement avant de soumettre une demande de fusion.

3. **Messages de Commit :** Utilisez des messages de commit clairs et descriptifs pour expliquer chaque modification. Suivez les conventions de message de commit définies dans la documentation technique.

## Soumission d'une Demande de Fusion (Pull Request)

1. **Mise à Jour du Projet :** Avant de créer une demande de fusion, assurez-vous de récupérer les dernières modifications du projet parent :
```bash
git pull upstream main
```

2. **Rebasing (si nécessaire) :** Si des modifications ont été apportées dans le projet parent, effectuez un rebase pour intégrer ces modifications dans votre branche :
```bash
git rebase upstream/main
```

3. **Pousser votre branche locale :** Une fois que votre branche est prête à être fusionnée, poussez-la vers le dépôt du projet :
```bash
git push depot_du_projet nom_de_votre_branche
```

4. **Soumission de la Demande de Fusion :** Depuis GitHub, créez une demande de fusion depuis votre branche vers la branche principale du projet parent. Assurez-vous de fournir une description détaillée de vos modifications.

## **Processus de Qualité**

1. **Tests Continus :** Les tests automatisés doivent être exécutés avec succès sur votre branche avant la fusion.
2. **Relecture de Code :** Votre code sera examiné par un membre de l'équipe. Soyez prêt à apporter des modifications en fonction des commentaires.

## **Règles de Contribution**

1. **Respectez le Code de Conduite :** Assurez-vous de respecter le code de conduite du projet et traitez les autres contributeurs avec respect.

2. **Travaillez sur des Branches Dédiées :** Il est interdit de travailler sur la branche principale. Utilisez des branches dédiées pour chaque fonctionnalité ou correction de bogue.

3. **Communiquez avec l'Équipe :** Si vous avez des questions ou des préoccupations, n'hésitez pas à communiquer avec l'équipe via les canaux définis.
---

# Processus de Qualité

## Convention de nommage

**Classe** :

- Commencez par une lettre majuscule.
- Utilisez la notation CamelCase, où chaque mot commence par une majuscule (ex. **`MaClasse`**).

**Méthode** :

- Commencez par une lettre minuscule.
- Utilisez la notation camelCase (ex. **`maMethode`**).

**Variable** :

- Commencez par une lettre minuscule.
- Utilisez la notation camelCase (ex. **`$maVariable`**).

**Constantes** :

- Utilisez des lettres majuscules avec des mots séparés par des underscores (ex. **`MA_CONSTANTE`**).

**Namespace** :

- Utilisez la notation CamelCase inversée (ex. **`App\Controller`**).

**Fichier** :

- Utilisez le même nom que la classe, en commençant par une lettre majuscule, suivi de l'extension **`.php`** (ex. **`MaClasse.php`**).

**Propriété de Classe** :

- Commencez par une lettre minuscule.
- Utilisez la notation camelCase (ex. **`$maPropriete`**).

**Méthode de Contrôleur** :

- Suivez la convention camelCase  (ex. **`index`**, **`userShow`**).

**Route** :

- Utilisez des noms en minuscules séparés par des tirets (ex. **`route_name`**).

**Noms de Variables dans les Vues Twig** :

- Utilisez la notation camelCase (ex. **`{{ maVariable }}`**).

**Noms de Chemins (URL)** :

- Utilisez des noms en minuscules séparés par des tirets (par exemple, **`/mon-chemin-url`**).

**Noms de Paramètres d'URL dans les Routes** :

- Utilisez la notation camelCase (ex. **`{monParametre}`**).

**Noms de Fichiers Twig** :

- Utilisez la notation snake_case pour les noms de fichiers (ex. **`ma_template.twig.html`**).

## **Tests Automatisés**

**Objectif :** S'assurer que les fonctionnalités existantes ne sont pas cassées par de nouvelles modifications et détecter rapidement les erreurs potentielles.

**Actions :**

- Implémentez des tests unitaires pour chaque composant du code.
- Créez des tests fonctionnels pour vérifier le comportement global de l'application.
- Utilisez des outils tels que PHPUnit et Behat pour l'exécution automatisée des tests.
- Atteignez un taux de couverture de code supérieur à 70 %.

## **Documentation**

**Objectif :** Fournir une documentation complète pour aider les développeurs juniors à comprendre le code, les processus et les flux de travail.

**Actions :**

- Documentez le code source en utilisant des commentaires PHPDoc pour expliquer le fonctionnement des classes, des méthodes et des paramètres.
- Créez une documentation générale expliquant l'architecture globale de l'application.
- Rédigez des guides pour les processus de développement, de test, de déploiement et de gestion des versions.

## **Gestion de Versions**

**Objectif :** Suivre les modifications apportées au code source, gérer les branches et faciliter la collaboration entre les membres de l'équipe.

**Actions :**

- Utilisez un système de gestion de versions, tel que Git, pour suivre toutes les modifications de code.
- Adoptez une stratégie de gestion de branches, comme Git Flow, pour gérer les fonctionnalités, les correctifs et les versions.
- Utilisez des messages de commit clairs et informatifs pour expliquer chaque modification apportée au code.