# Installation

[Return to Installation main page](Installation.md)

## Usage

### Hybrid runtime environment

First, you have to configure your env variables by creating the .env.local file.

    touch .env.local

Now you need to copy the local runtime docker-compose.override.yml example.

    cp docker.compose.override.local-runtime-sample.yml docker.compose.override.yml

Then change the env variables with the previous one.

Run the docker services.

    docker-composer up -d

There's no need to configure anything to run the application. If you have
[installed Symfony][symfony_cli] binary, run this command:

```bash
symfony serve
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or [configure a web server][web_server] like Nginx or
Apache to run the application.

[symfony_cli]: https://symfony.com/download
[web_server]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
