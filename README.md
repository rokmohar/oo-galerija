# Private gallery for image comparison

This project was build in two separate parts:
- backend with Symfony 4
- frontend with Aurelia

Project is using [Docker](https://www.docker.com/ "Docker") containers for environmental setup. Docker builds three separate service containers:
- MySQL container
- PHP container
- NGINX container

## Installation on Windows

You must install [Docker for Windows](https://store.docker.com/editions/community/docker-ce-desktop-windows "Docker for Windows") on your local machine.

When you have docker installed and running you must open a command line interface (cmd) and navigate to the project root. When setting up docker containers for the first time or rebuilding them after you have pulled new changes from the repository you must first execute the command `docker-compose up -d --build` to build the docker containers. When building the containers is finished you must run the command `docker-compose exec php sh` and then `composer install` to install the vendor libraries to the project.

**Note:** If the `composer install` command fails you might need to update your composer with the `composer self-update` command.

**Note:** When starting the containers without building them run the command `docker-compose up -d` without the `--build` option.

When the steps above are complete you can access the website on the the local address `http://symfony.local/`.

**Note:** You must add `symfony.local` to your hosts and point it to the local IP address `127.0.0.1`.

### Default user

When you login for the first time use the default user credentials `username: user1` and `password: pass1`. You can change these on the account settings page.
