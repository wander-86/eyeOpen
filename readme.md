In this repo we think you will find everything necessary to work on the assignment for EyeOpen. Below a few basic instructions are provided to get you going.

# Vagrant
We work with vagrants to make sure your environment is the same as ours and prevent the 'it works on my machine' syndrome. The repo contains a Vagrantfile that should get it running with all necessary dependencies

# Paths
This repo will be loaded in your vagrant under **/assignment**

# PHPUnit
Each assignment contains tests or will require you to make tests. Once you did the composer install, you can run tests by running `vendor/bin/phpunit`. Settings are provided in the `phpunit.xml` and `phpunit-bootstrap.php` files.
