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

1. Clone the repository
```bash
git clone
```

2. Install dependencies
```bash
composer install
```

4. Create database schema
```bash
php bin/console doctrine:schema:create
```

5. Load fixtures
```bash
php bin/console doctrine:fixtures:load
```

## Connexion :

```
- default admin : 
    username: admin
    password: password
```
```
- default user : 
    username: user
    password: password
```


## TESTS

1. purge test database
```bash
php bin/console --env=test doctrine:fixtures:load
```

2. run all tests and generate coverage raport [HERE](http://p8-todo-liste.erwan-h.fr:48200/test-coverage/index.html)
```bash
vendor/bin/phpunit --coverage-html public/test-coverage
```

3. run a specific test
```bash
vendor/bin/phpunit --filter=METHODE_NAME or CLASS_NAME
```