# Developing Choice
Given the nature of the app, I've choosen to use Sqlite3 as the database for a better deliverability.

Because the specs doesn't say anything about multiple image per product, I decided to go with a one to one relationship, embedding it directly into product.

The spec wasn't clear enough for me regarding the search feature in product list, hence I've speculated you want to search for a prefix on each tag (`"tag%"`) instead of a more generic `"%tag%"`; this will also speed up the search avoiding a full-text scanning (given an index on `tags.name`)

# Installation
The project is based on Symfony3, you can install it in the usual way...

```shell
$ git clone git@github.com:Iazel/PHPTest.git
$ cd PHPTest
$ composer install
$ bin/console doctrine:schema:update --force
```

And then run the server for testing the app
```shell
$ bin/console server:run
```

You can load some products and tags as an optional step:
```shell
$ bin/console doctrine:fixtures:load
```
