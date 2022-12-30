FROM php:8.1-apache
RUN apt -y update
RUN apt -y install build-essential
RUN curl -fsSL https://crystal-lang.org/install.sh | bash
RUN apt -y install rustc
RUN apt -y install nim
RUN apt-get install haskell-platform -y
RUN apt-get -y install gfortran
RUN apt -y install julia
# run install.jl to install the json package (otherwise, the first use of the container will take a long time)
COPY ./julia/install.jl /var/www/html/install.jl
RUN julia install.jl
COPY ./app /var/www/html
