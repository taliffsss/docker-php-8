# crm2-docker

Containerized environment for crm2 API.
**_Note: This repository is only for running dockerized setup for PHP API, Redis, and any other backend-related services. Docker setup for Web is located inside `crm2-web` repository itself._**

### Docker Setup

#### Requirements:

- For mac users, download: [Docker For Mac](https://docs.docker.com/docker-for-mac/install/)
- For windows - TBD

#### Initial container build

1. Make sure crm2-api and crm2-docker repositories are on the same directory level
1. For mac users, add entry below in `/etc/hosts`.
   - `127.0.0.1 crm2-local.cartrack.com`
1. Rename `.env-sample` to `.env` and get updated credentials from the team.
1. Run `export $(cat .env | xargs)`
1. Run `docker-compose down -v` to stop any running containers
1. Run `docker-compose up --build -d` to run container
1. Go to `http://crm2-local.cartrack.com:8080/` to access Dev environment

#### Installing Dependencies

1. For someone who have php installed you can run `composer install` inside the `crm2-api` folder to install dependencies.
1. For those who doesn't have php installed you can run
   `docker-compose run composer` to install dependencies.

#### Restarting API container

Restart API container without restarting other services

1. Run `docker-compose restart <container_name>` to restart container
