#!/bin/bash

VERSION="$(cat ../backend/version)"
VERSION="${VERSION:1}"

latest_image_tag="vagharsh/consul-tree:latest"
local_image_tag="vagharsh/consul-tree:$VERSION"
web_image_tag="vagharsh/consul-tree:$VERSION-web"

docker build -t "$local_image_tag" - < "Dockerfile-local"
docker build -t "$web_image_tag" .

docker login --username=vagharsh

docker push "$latest_image_tag"
docker push "$local_image_tag"
docker push "$web_image_tag"