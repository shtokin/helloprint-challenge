docker network create helloprint-network

docker-compose -f services/requester/docker-compose.yml build
docker-compose -f services/service-a/docker-compose.yml build
docker-compose -f services/service-b/docker-compose.yml build
docker-compose -f services/broker/docker-compose.yml build
docker-compose -f services/kafka/docker-compose.yml build

