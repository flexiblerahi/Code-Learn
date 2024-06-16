<?php
/** 
    * Step-by-Step Example with Car and Driver
    * Step 1: Define the Service Locator
    * The service locator will manage and provide instances of the Car and Driver services.
*/
class ServiceLocator
{
    private $services = array();
    private $instances = array();

    public function register($name, callable $resolver)
    {
        $this->services[$name] = $resolver;
    }

    public function get($name)
    {
        if(!isset($this->instances[$name])) {
            if(!isset($this->services[$name])) {
                throw new Exception("Service not found: " . $name);
            }
            $this->instances[$name] = $this->services[$name]($this);
        }

        return $this->instances[$name];
    }
}

/**
    * Step 2: Define the CarService and DriverService Classes
    * Here, we define two simple classes: CarService and DriverService.
*/
class Car
{
    private $model;

    public function __construct($model) 
    {
        $this->model = $model;
    }

    public function drive()
    {
        return "Driving a " . $this->model;
    }
}

class Driver
{
    private $name;
    private $car;

    public function __construct($name, Car $car) {
        $this->name = $name;
        $this->car = $car;
    }

    public function driveCar()
    {
        return $this->name . ' is '. $this->car->drive();
    }
}

/**
    * Step 3: Register Services with the Service Locator
    * Register instances of Car and Driver with the service locator.
*/

$service_locator = new ServiceLocator();

$service_locator->register('car', function ($locator) {
    return new Car('Tesla Model S');
});

$service_locator->register('driver', function ($locator) {
    $car = $locator->get('car');
    return new Driver('John Doe', $car);
});

print_r($service_locator);