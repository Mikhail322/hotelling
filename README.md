# Tasks
* List of 5 hotels with the smallest number of weekend stays (weekend nights are Friday and Saturday nights) -> list hotel + number of stays for each of them;
* List hotels and dates where we had to reject customers (= where there were more customers wanting to stay then the capacity of the hotel);
* Show the day when we lost the most due to rejection (= did not have the highest possible income because of the lack of capacity on this day).

## How to use app(use all cmd from root dir)

install composer
```shell
composer install
```

Start building
```shell
make start
```

Start building (for watching logs)
```shell
make watch
```

Create conf files packages and etc.
```shell
make app-install
```

If you want to fresh db
```shell
make app-fresh-db
```

