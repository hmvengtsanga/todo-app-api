# Todo API

This project is an api allowing many users to create todos

## Features

- Creates users
- Log as user (Get jwt for below features)
- Refresh token (jwt)
- Get Todos (private and public): public todos is for others users
- Create todos (private or public)
- Update todos
- Change todo status 
- Delete todo

## Run Locally

Clone the project

```bash
  git clone git@github.com:hmvengtsanga/todo-app-api.git  via ssh
```

Go to the project directory

```bash
  cd todo-app-api
```

Run docker

```bash
  docker-compose up -d
```

Run task on docker container

```bash
  docker exec -it containerId sh
  php bin/console messenger:consume async
```

Wait 2 minutes while init.sh starts and opens the Browser

```bash
  API: http://localhost:8040/api
  PMA: http://localhost:8042/
  MailDev: http://localhost:8041/#/
```

## Running Tests

To run tests, run the following command on container

```bash
  php bin/phpunit --testdox
```

## Tech Stack

**Language/Framework:** PHP 8.1, Symfony 6, API Platform, Twig

**Server:** Apache

**Devops:** Docker, github-actions