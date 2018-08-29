# PHP JWT Technical Challenge

## Requirements
- PHP 7.2
- Composer
- Sqlite

## Commands
Installation:
```
make build
```
Start local webserver:
```
make start
```
Run tests:
```
make tests
```
Stop webserver:
```
make stop
```

### Credentials
User: `bob`
Password: `password`

## Instructions

Install the project with `make build`, then create a new [HTTP request file](https://www.jetbrains.com/help/phpstorm/http-client-in-product-code-editor.html) 
in PhpStorm with the contents below and run each request in order:
```
### Get JWT

POST http://localhost:8000/login_check
Content-Type: application/json

{"username":"bob","password":"password"}

#####################################################################
### Replace YOUR_JWT_TOKEN_GOES_HERE with the token returned above###
#####################################################################

### 1. Get a list of football teams in a single league

GET http://localhost:8000/api/football-league/1
Authorization: Bearer YOUR_JWT_TOKEN_GOES_HERE

### 2. Create a football team

POST http://localhost:8000/api/football-team
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN_GOES_HERE

{"name":"Reading","strip":"Puma", "footballLeague":2}

### 3. Modify all attributes of a football team

PUT http://localhost:8000/api/football-team/17
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN_GOES_HERE

{"name":"Tottenham Hotspur","strip":"Nike", "footballLeague":2}

### 4. Delete a football league

DELETE http://localhost:8000/api/football-league/2
Authorization: Bearer YOUR_JWT_TOKEN_GOES_HERE

###
```
