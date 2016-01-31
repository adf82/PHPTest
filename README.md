# Requirements

* PHP > 5.6.x
* MySQL > 5.5.x
* Apache > 2.2.x

# Installation

* Clone the repository
* Setup the VHost following the template in `doc/apache/VHost.conf`
* Set the proper write permission on `var` (maybe using ACL if supported by your OS)
* Set the proper permission (they must be writable by the webserver) on `web/images` and `web/cache`
* `cd PHPTest` and run `SYMFONY_ENV=dev php composer.phar install --prefer-dist --no-dev`
* Point your browser to `http://phptest.local`

# Implementation choices and notes

* `Composer` has been included into the repository for sake of semplicity (not a good pratice actually)
* For a better experience a pagination in `/product/list` should have been implemented, I decided to avoid it for time constraint. Pagination can be implemented using `Pagerfanta`
* A `ProductService` has been built to better support the search logic, avoiding the injection of the TaggingRepository into the ProductRepository (This has been considered as a bad practice)
* I didn't add any controll on 4xx and 5xx errors (default pages will be served)
 