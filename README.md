# Installation

* Create schema
```
php bin/console doctrine:schema:update --force
```
* Create sample data
``` 
php bin/console doctrine:fixtures:load
```
* Run tests
``` 
phpunit
```