# Developing Choices
  - Given the nature of the app, I've chosen to use Sqlite3 as the database for a better deliverability.

  - Because the specs doesn't say anything about multiple image per product, I decided to go with a one to one relationship, embedding it directly into product.

  - The spec wasn't clear enough for me regarding the search feature in product list, hence I've speculated you want to search for a prefix on each tag (`"tag%"`) instead of a more generic `"%tag%"`; this will also speed up the search avoiding a full-text scanning (given the index on `tags.name`). I've also decided to use an `OR` relation for each tag searched, however change it to an `AND` is *dead simple*.

  - The spec specified that I should use repository and so I've done (in TagController), however I prefer to use `Finder`s, a light abstraction built around the `QueryBuilder`.

  - `ViewModel`s are another component that I like because give a better separation between controller and representation, and are also easier to unit testing and composite.
  
  - The usefulness of `Finder`s and `ViewModel`s in this little project is scarse, but I decided to include this patterns as a demonstration.

# Installation
The project is based on Symfony3, you can install it in the usual way...

```shell
$ git clone git@github.com:Iazel/PHPTest.git
$ cd PHPTest
$ composer install
$ bin/console doctrine:schema:update --force
```

Then test that everything works correctly:
```shell
$ phpunit
```

Then run the server to try the app
```shell
$ bin/console server:run
```

You can also load some products and tags as an optional step:
```shell
$ bin/console doctrine:fixtures:load  --fixtures='src/AppBundle/DataFixtures/ORM/LoadProductData.php'
```
