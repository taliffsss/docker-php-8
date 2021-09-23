
# Docker

Containerized environment for PHP 8.
**_Note: This repository is only for running dockerized setup for PHP, Redis, PostgreSQL and Adminer._**

### Docker Setup

#### Requirements:

- For mac users, download: [Docker For Mac](https://docs.docker.com/docker-for-mac/install/)
- For windows - [Docker Windows](https://www.docker.com/products/docker-desktop)

#### Initial container build

1. Make sure crm2-api and crm2-docker repositories are on the same directory level
1. For mac users, add entry below in `/etc/hosts`.
   - `127.0.0.1 php-8.docker.com`
1. Rename `.env-sample` to `.env` and get updated credentials from the team.
1. Run `export $(cat .env | xargs)` Mac
1. Run `docker-compose down -v` to stop any running containers
1. Run `docker-compose up --build -d` to run container `-d` is for detach mode
1. Go to `http://php-8.docker.com:port/` to access Dev environment