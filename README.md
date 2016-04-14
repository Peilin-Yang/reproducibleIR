# reproducibleIR

Start the Docker Container (using docker-compose)

FIRST make sure that the mysql service is shut down on the local system

```sudo service mysql stop```

GOTO the docker directory and run

```docker-compose up -d```

Get Into the Running Docker Container

```docker exec -i -t web /bin/bash```
