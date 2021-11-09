up:
	@docker-compose up -d

down:
	@docker-compose down -v

destroy:
	@docker-compose destroy

build:
	@docker-compose build

migrate:
	@docker-compose exec python python manage.py makemigrations
	@docker-compose exec python python manage.py migrate

crawl:
	@docker-compose exec python scrapy runspider crawlResearchers.py

crawl-local:
	scrapy runspider crawlResearchers.py

statics:
	@docker-compose exec python python manage.py collectstatic

statics-force:
	@docker-compose exec python python manage.py collectstatic --noinput
	@docker-compose exec python chmod 755 static/config/chromedriver

shell-py:
	@docker-compose exec python /bin/sh

log-py:
	@docker-compose logs python

services:
	@docker-compose ps