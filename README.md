# lbaw2076

## Tema 1 - Collaborative News

This project aims to provide an online collaborative news platform. People can post about their favorite topics and share some knowledge with the community. Sharing is caring.

To run the project image locally tun the following command:
```
docker run -it -p 8000:80 lbaw2076/lbaw2076
```

Link to the release with the final version of the source code in the group's git repository: https://git.fe.up.pt/lbaw/lbaw1920/lbaw2076

### Run PostgresSQL local server 
```
docker-compose up
```

### Run database
```
php artisan db:seed
```

### Install curl
> This feature is only working locally after doing this command

```
sudo apt-get install php7.2-curl
```

### Run laravel php local server
```
php artisan serve
```

**Note**: The Google authentication feature is only working locally.

## Membros do grupo:

* Ana Mafalda Costa Santos, up201706791@fe.up.pt
* Diogo Alexandre Silva, up201706892@fe.up.pt
* João Henrique Luz, up201703782@fe.up.pt
* Liliana Almeida, up201706908@fe.up.pt

***
GROUP2076, 18/02/2020
