docker network create helloprint-network

docker-compose -f services/requester/docker-compose.yml up -d
docker-compose -f services/service-a/docker-compose.yml up -d
docker-compose -f services/service-b/docker-compose.yml up -d
docker-compose -f services/broker/docker-compose.yml up -d
docker-compose -f services/kafka/docker-compose.yml up -d
