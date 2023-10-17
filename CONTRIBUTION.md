
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