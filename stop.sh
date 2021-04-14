docker-compose -f services/requester/docker-compose.yml down
docker-compose -f services/service-a/docker-compose.yml down
docker-compose -f services/service-b/docker-compose.yml down
docker-compose -f services/broker/docker-compose.yml down
docker-compose -f services/kafka/docker-compose.yml down

docker network rm helloprint-network
