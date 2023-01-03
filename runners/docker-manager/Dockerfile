FROM php:8.1-apache
RUN apt update
RUN apt install -y gnupg
RUN apt install -y apt-transport-https ca-certificates curl software-properties-common
RUN curl -fsSL https://download.docker.com/linux/ubuntu/gpg | apt-key add -
RUN add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu focal stable"
RUN apt update
#RUN apt-cache policy docker-ce
RUN apt install -y docker-ce
COPY ./app /var/www/html
