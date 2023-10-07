
# TESTS

### test l'ensemble des tests en rédigant un rapport de couverture de code

```bash
vendor/bin/phpunit --coverage-html public/test-coverage
```

###  test une methode en particulier

```bash
vendor/bin/phpunit --filter=METHODE_NAME or CLASS_NAME
```

### liste des methodes disponnibles

#### tests unitaires
- testUser
- testTask
- testAuteur

#### tests fonctionnels

DefaultControllerTest :
- testHomepage

TaskControllerTest :
- testTasksList
- testCreateAction
- testEditAction
- testToggleTaskAction
- testDeleteTaskAction

UserControllerTest :
- testListAction
- testCreateAction
- testEditAction

SecurityControllerTest :
- testLoginAction
- testLogoutAction
- testLoginActionWithBadCredentials