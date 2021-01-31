# Caker
Caker is a Symfony development stack with the following docker configuration:
- Caddy v2 as a web server and reverse-proxy
- A PostgreSQL database
- A PHP (8.0.*) container based on the [thecodingmachine PHP image](https://github.com/thecodingmachine/docker-images-php) with these additionnal plugins enabled:
    - pgsql
    - pdo_pgsql
    - intl
    - gd

For the Symfony environment, here is the configuration :
- PHP 8.0.* (with unlimited PHP memory)
- Symfony 5.2.*
- PHPUnit 9.5.*
- PHPStan (on level 5) with the following packages:
    - extension-installer
    - phpstan-doctrine
    - phpstan-phpunit
    - phpstan-symfony
- ECS (easy-coding-standard) which include PHPCS and PHP CS Fixer with the following rules:
    - CLEAN_CODE
    - PSR-12
    - DOCTRINE_ANNOTATIONS

## Getting started
Before the first run, you must create a `.env` file at the root of the project (you can copy paste the `.env.dist` file and rename it) and fill the requested values.
## Usage
To launch the stack, you only need to enter the following command in the folder in your terminal:
```bash
docker-compose -f ./docker-compose.yml up -d 
```

To stop it, you can simply run the appropriate command:
```bash
docker-compose -f ./docker-compose.yml stop 
```

## Aliases
Several commands have been preconfigured on the PHP container:
- `sf` replace `php bin/console` (the symfony console command)
- `phpunit` replace `php vendor/bin/simple-phpunit`

## Check coding standard
As stated above, a full suite of coding standards (CLEAN_CODE, PSR-12, DOCTRINE_ANNOTATIONS) has been configured in the PHP container. To use them you have the following commands:
- `phpstan analyse`
- `ecs check` to check the coding standards errors
- `ecs check --fix` to fix the errors above

## Recommended VSCode extensions
This is a list of extensions that works very well with this stack:
- [PHPStan](https://marketplace.visualstudio.com/items?itemName=swordev.phpstan)

## Others recommended VSCode extensions
- [indent-rainbow](https://marketplace.visualstudio.com/items?itemName=oderwat.indent-rainbow)
- [Bracket Pair Colorizer 2](https://marketplace.visualstudio.com/items?itemName=CoenraadS.bracket-pair-colorizer-2)
- [Better Comments](https://marketplace.visualstudio.com/items?itemName=aaron-bond.better-comments)
- [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
- [Code Spell Checker](https://marketplace.visualstudio.com/items?itemName=streetsidesoftware.code-spell-checker)
- [PHP DocBlocker](https://marketplace.visualstudio.com/items?itemName=neilbrayfield.php-docblocker)
- [PHP Namespace Resolver](https://marketplace.visualstudio.com/items?itemName=MehediDracula.php-namespace-resolver)
- [Path Intellisense](https://marketplace.visualstudio.com/items?itemName=christian-kohler.path-intellisense)

## Upcoming
- Add Github Actions support
