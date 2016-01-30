# Requirements

* PHP > 5.6.x
* MySQL > 5.5.x
* Apache 2.2.x

# Installation

* Clone the repository
* Setup the VHost following the template in `doc/apache/VHost.conf`
* Setup proper write permission on `var` (maybe using ACL if supported by your OS)
* `cd PHPTest` and run `SYMFONY_ENV=dev php composer.phar install --prefer-dist --no-dev`
* Point your browser to `http://phptest.local`