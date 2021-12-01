up: ## Start all containers in foreground
	docker-compose up -d --build

restart: ## Restart all containers in foreground
	docker stop $$(docker ps -q)
	docker-compose up -d --build

clean: ## Stop and delete all containers 
	docker stop $$(docker ps -q)
	docker rm -v $$(docker ps -aq -f status=exited)

stop: ## Stop all containers
	docker stop $$(docker ps -q)

delete: ## delete all containers 
	docker rm -v $$(docker ps -aq -f status=exited)




