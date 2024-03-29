TAG?=14-alpine
CONTAINER?=$(shell basename $(CURDIR))-buildchain
DOCKERRUN=docker container run \
	--name ${CONTAINER} \
	--rm \
	-t \
	--network craft-plugin-playground_default \ 
	-p 8081:8081 \
	-v `pwd`:/app \
	${CONTAINER}:${TAG}

.PHONY: build dev docker install npm

build: docker install
	${DOCKERRUN} \
		run build
dev: docker install
	${DOCKERRUN} \
		run dev
docker:
	docker build \
		. \
		-t ${CONTAINER}:${TAG} \
		--build-arg TAG=${TAG} \
		--no-cache
install: docker
	${DOCKERRUN} \
		install
update: docker
	rm -f buildchain/package-lock.json
	${DOCKERRUN} \
		install
update-clean: docker
	rm -f buildchain/package-lock.json
	rm -rf buildchain/node_modules/
	${DOCKERRUN} \
		install
npm: docker
	${DOCKERRUN} \
		$(filter-out $@,$(MAKECMDGOALS))
%:
	@:
# ref: https://stackoverflow.com/questions/6273608/how-to-pass-argument-to-makefile-from-command-line