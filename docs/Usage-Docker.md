# Installation

[Return to Installation main page](Installation.md)

## Usage

### Docker runtime environment

First, you have to configure your env variables by creating the .env.local file.

    touch .env.local

Now you need to copy the docker runtime docker-compose.override.yml example.

    cp docker.compose.override.docker-runtime-sample.yml docker.compose.override.yml

Then change the env variables with the previous one.

Run the docker services.

    docker-composer up -d

Then access the application in your browser at the given URL (<https://localhost:8080> by default).
