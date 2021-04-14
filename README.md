# HelloPrint challenge

## Requirements

On your own machine you should have:

- docker
- docker-compose

Be sure that you have permissions to execute all .sh scripts in the project root folder. 

## Build the application

```
./build.sh
```

## Run the application

```
./start.sh
```

## Start consumer services

Open a new terminal and run:
```
docker exec service-a php /var/www/service-a/main.php
```

Open another new terminal and run:
```
docker exec service-b php /var/www/service-b/main.php
```

## Run the requester script

Open one more new terminal and run:
```
docker exec requester php /var/www/requester/requester.php
```

## Stop the application

```
./stop.sh
```
