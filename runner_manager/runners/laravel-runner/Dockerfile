FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP
ARG NODE_VERSION=16
ARG POSTGRES_VERSION=14

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && echo "keyserver hkp://keyserver.ubuntu.com:80" >> ~/.gnupg/dirmngr.conf \
    && gpg --recv-key 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c \
    && gpg --export 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c > /usr/share/keyrings/ppa_ondrej_php.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.1-cli php8.1-dev \
       php8.1-pgsql php8.1-sqlite3 php8.1-gd \
       php8.1-curl \
       php8.1-imap php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath php8.1-soap \
       php8.1-intl php8.1-readline \
       php8.1-ldap \
       php8.1-msgpack php8.1-igbinary php8.1-redis php8.1-swoole \
       php8.1-memcached php8.1-pcov php8.1-xdebug \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /usr/share/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y yarn \
    && apt-get install -y mysql-client \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#LANGS
RUN npm install lodash
RUN npm install --global coffeescript
RUN apt -y update
RUN apt -y install sbcl
RUN apt -y install build-essential
RUN curl -fsSL https://crystal-lang.org/install.sh | bash
RUN apt -y install mono-complete
RUN apt -y install golang
RUN apt -y install groovy
RUN apt -y install haskell-platform
RUN apt -y install haxe
RUN apt -y install lua5.3
RUN apt -y install lua-json
RUN apt -y install opam
RUN apt -y install python3
RUN apt -y install r-base
RUN apt -y install r-cran-rjson
RUN apt -y install racket
RUN apt -y install rakudo
RUN apt -y install ruby-full
RUN apt -y install default-jdk
RUN apt -y install scala
RUN npm install -g ts-node typescript '@types/node'
# DART
RUN apt-get -y update
RUN apt-get install apt-transport-https
RUN apt-get install wget
RUN apt-get install -y gpg
RUN wget -qO- https://dl-ssl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o /usr/share/keyrings/dart.gpg
RUN echo 'deb [signed-by=/usr/share/keyrings/dart.gpg arch=amd64] https://storage.googleapis.com/download.dartlang.org/linux/debian stable main' | tee /etc/apt/sources.list.d/dart_stable.list
RUN apt-get -y update
RUN apt-get -y install dart
#ELIXIR
RUN apt-get -y install elixir
#AGDA
RUN apt -y install agda
RUN apt -y install agda-stdlib
#KOTLIN
RUN apt -y install kotlin
#PERL JSON
RUN cpan JSON -y
#FSHARP
RUN apt install -y dotnet-sdk-6.0
#CLOJURE
RUN curl -O https://download.clojure.org/install/linux-install-1.11.1.1149.sh
RUN chmod +x linux-install-1.11.1.1149.sh
RUN ./linux-install-1.11.1.1149.sh
RUN apt-get -y install rlwrap
COPY ./runners_sources/clojure/install.clj /var/www/html/install.clj
COPY ./runners_sources/clojure/deps.edn /var/www/html/deps.edn
RUN clj install.clj
#PYTHON LIBS
RUN apt install -y python3-numpy
RUN apt install -y python3-scipy


RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.1

#clojure runner throw the permission denied error without root shell user
RUN useradd -o -u 0 -g 0 -s /bin/bash sail
#RUN groupadd --force -g $WWWGROUP sail
#RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

COPY ./vendor/laravel/sail/runtimes/8.1/start-container /usr/local/bin/start-container
COPY ./vendor/laravel/sail/runtimes/8.1/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./vendor/laravel/sail/runtimes/8.1/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini

COPY . /var/www/html

RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
