# Requirements

* PHP > 5.6.x
* MySQL > 5.5.x
* Apache > 2.2.x

# Installation

* Clone the repository
* Setup the VHost following the template in `doc/apache/VHost.conf`
* set the proper write permission on `var` (maybe using ACL if supported by your OS)
* Set the proper permission (writable by the webserver) on `web/images`
* `cd PHPTest` and run `SYMFONY_ENV=dev php composer.phar install --prefer-dist --no-dev`
* Point your browser to `http://phptest.local`

# Implementation choices

* For a better experience a pagination in `/product/list` should have been implemented, I decided to avoid it for time constraint. Pagination can be implemented using `Pagerfanta`
 