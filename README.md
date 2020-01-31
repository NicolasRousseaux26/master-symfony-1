# Mon super projet

## Comment travailler sur le projet ?

Première étape, on récupère le dépôt :

```bash
cd C:\xampp\htdocs
git clone URL NOMDUPROJET
cd NOMDUPROJET
```

On installe les dépendances :

```bash
composer install
```

On configure la base de données dans ```.env.local```.

On crée la base de données :

```bash
php bin/console doctrine:database:create
```

On crée le schéma :

```bash
php bin/console doctrine:migrations:migrate
```

On lance les fixtures :

```bash
php bin/console doctrine:fixtures:load
```
