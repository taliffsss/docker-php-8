# Docker

Containerized environment for PHP 8.
**_Note: This repository is only for running dockerized setup for PHP API, Redis, PostgreSQL and Adminer,  and It also has JIT enabled_**

### Docker Setup

#### This setup is also intended for load balancing as well

### Reference
- Adminer: [Adminer Docker hub](https://hub.docker.com/_/adminer)
- PostgreSQL: [PostgreSQL Docker hub](https://hub.docker.com/_/postgres)
- Redis: [Redis Docker hub](https://hub.docker.com/_/redis)

#### Requirements:

- For mac users, download: [Docker For Mac](https://docs.docker.com/docker-for-mac/install/)
- For windows - [Docker Windows](https://www.docker.com/products/docker-desktop)

#### Initial container build

1. Make sure that you have an `api` folder for PHP.
2. Make sure that `api` and `php-docker` are in the same folder.
4. Rename `.env-sample` to `.env` and get updated credentials from the team.
5. Run `export $(cat .env | xargs)` Mac
6. Run `docker-compose down -v` to stop any running containers
7. Run `docker-compose up --build -d` to run container `-d` is for detach mode
8. Go to `http://localhost:port/` to access Dev environment