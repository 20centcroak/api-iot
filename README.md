# api-iot
Demo of a simple REST api to manage connected devices. This API is based on [Slim framework](https://www.slimframework.com/).

## Quick start
See this list of [tools](https://github.com/20centcroak/api-iot/wiki/Tools) to start quickly
* clone the project
* run `composer install` in the directory containing composer.json
* be sure that the `extension=php_pdo_sqlite.dll` in the php.ini file of your php installation is uncommented
* be sure that the `extension=php_openssl.dll` is uncommented as well 
* run `php -S localhost:8080` in the "web" directory of the project
* open your brower and enter the following adress: `http://localhost:8080/`, a "Welcome!" message should be displayed if everything is ok
* use one of the curl command below to test the api

:information_source: the "api-config.json" allows a specific configuration of the api. So far, you can only change the format of device serial number.


## The REST API for connected devices
See the [wiki pages](https://github.com/20centcroak/api-iot/wiki) for information concerning RESTFUL API, hosting your api, ...

A connected device is generally a set of sensors which acquire and send data to a server which stores and link them.
A graphical user interface (GUI) manage user interaction and display these data in a convenient way (with the ability to sort, filter and propose different views).

Then the environment of a connected device can be split into 4 basic modules:
* the hardware component able to detect via sensors, connect to a server and send data
* a REST API (this project) able to manage input/output of data. This is a hub in which data transit. It dispatchs data according to requests
* a database able to store data on a distant server
* a GUI which displays data and manages user interaction

:information_source: see [arduino-iot](https://github.com/20centcroak/arduino-iot) project for a simple example of hardware connected device based on arduino.

This API is generic for any type of connected device. It considers that a connected device is a system which acquire parameters from sensors. It uses 3 tables linked in a database. 
These 3 tables manage:
* devices: serial number, name, ...
* users: name, e-mail, keyword, ...
* measures: type, value, unit, ...

The measures are linked to a unique device by table relationship.
The devices could be linked to a unique user by table relationship

Testing the API could be easily done via curl or ARC (see tools for more information). Here are a few examples of curl commands to add data in the database:

* Add a measure of temperature in the database acquired with device SN 1234

`curl -v -H "Content-Type: application/json" -X POST -d "{\"value\":28.5,\"type\":\"temperature\",\"unit\":\"K\"}" http://localhost:8080/measure/1234`

* get device information
http://localhost:8080/devices/1234

