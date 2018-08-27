# PHP JWT Technical Challenge

Obtained the premier league team data from Wikipedia https://en.wikipedia.org/wiki/2018%E2%80%9319_Premier_League

Install

```
composer install
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load -n
bin/console doctrine:schema:update --force
bin/console doctrine:schema:drop --force
bin/console server:run

```

TODO:
- [ ] Implement migrations
- [ ] Consider test bootstrap
