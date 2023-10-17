# p8-todoList

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/75f529c7f7ab44918aac4823bea6d9ac)](https://app.codacy.com/gh/erwan-h-dev/p8-todo-list/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## DESCRIPTION

Totolist est une application web permettant de gérer une liste de tâches à réaliser.
Celui-ci permet de créer, modifier, supprimer et marquer comme terminé un ensemble de tâches.

Le projet a été développer dans un contexte pédagogique pour OpenClassrooms.
[DEMO](http://p8-todo-liste.erwan-h.fr:48200/)

## REQUIREMENTS

* PHP 8.1.*
* MySQL 8.0.0
* Composer 2.6.*

## INSTALLATION

1. Clone le repository
```bash
git clone
```

2. Installe les dépendances
```bash
composer install
```

4. créer la base de données
```bash
php bin/console doctrine:schema:create
```

5. générer les fixtures
```bash
php bin/console doctrine:fixtures:load
```

## Connexion :

```
- admin par défaut : 
    username: admin
    password: password
```
```
- user par défaut : 
    username: user
    password: password
```

## TESTS

1. purger la base de donnée de test
```bash
php bin/console --env=test doctrine:fixtures:load
```

2. executer tous les tests et générer un rapport de couverture dans `public/test-coverage`
> Le dernier rapport de couverture est disponible [ICI](http://p8-todo-liste.erwan-h.fr:48200/test-coverage/index.html)
```bash
vendor/bin/phpunit --coverage-html public/test-coverage
```

3. Executer un test spécifique
```bash
vendor/bin/phpunit --filter=METHODE_NAME or CLASS_NAME
```