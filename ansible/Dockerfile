FROM debian:stable-slim

RUN apt-get update && apt-get install python3-pip sshpass -y

RUN pip install ansible==5.1.0

WORKDIR /root

COPY . /root

COPY docker/requirements.yaml /root/requirements.yaml

COPY docker/entrypoint.sh /root/entrypoint.sh

RUN chmod +x /root/entrypoint.sh

ENTRYPOINT ["/root/entrypoint.sh"]
