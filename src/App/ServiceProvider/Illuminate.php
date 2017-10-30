<?php

namespace App\ServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;

class Illuminate
{
    protected $capsule = null;
    protected $dbsetup = [];

    public function __construct($dbSetup)
    {
        if(!is_null($this->capsule))
          return;

        if( empty( $dbSetup ) || !is_array($dbSetup) )
          throw new \Exception('db config is empty', 1);

        $this->dbsetup = $dbSetup;
        $this->setUpCapsule();
    }

    protected function setUpCapsule()
    {
      $this->capsule = new Capsule();
      $this->capsule->addConnection(
        $this->dbsetup
      );
      // Setup the Eloquent ORM…
      $this->capsule->bootEloquent();
    }
}
