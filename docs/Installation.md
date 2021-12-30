# Installation

[Return to Summary main page](README.md)

## Requirements

* PHP 8.0 or higher;
* PDO PHP extension enabled (as you prefer);
* [Git][git];
* and the [usual Symfony application requirements][requirements].


## Installation

Move to the desired folder and clone the repository by launching one of the following commands:

```bash
git clone git@github.com:lruozzi9/symfony-project.git # for SSH
git clone https://github.com/lruozzi9/symfony-project.git # for HTTPS auth
gh repo clone lruozzi9/symfony-project # if you have the GitHub CLI
```

Move to the project dir:

```bash
cd symfony-project
```

Install composer dependencies:

```bash
composer install --no-dev # For production
composer install # For development
```

## Usage

You can install or develop on this project using both:

- [Local runtime environment](Usage-Local.md): in this setup the webserver and PHP is installed and run directly on your machine as your services (Postgres, MailHog, etc...). This setup might be the most speed efficient, but it's the worst in terms of maintainability and compatibility with other projects in your work environment.
- [Hybrid runtime environment](Usage-Hybrid.md): in this setup the webserver and PHP is installed and run directly on your machine, only services (Postgres, MailHog, etc...) run with Docker containers. This setup is especially useful on macOS where using a Docker runtime results in bad performances due to filesystem sync latency with bind mounted volumes.
- [Docker runtime environment](Usage-Docker.md): in this setup both the runtime and services are run on Docker containers.


[git]: https://git-scm.com/
[requirements]: https://symfony.com/doc/current/reference/requirements.html
