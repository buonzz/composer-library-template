<?php namespace VendorName\PackageName\Laravel4\Facades;

use Illuminate\Support\Facades\Facade;

/**
*  Facade class for your Class
*
*  Use this to provide a facade for Laravel Application
*
*  @author Darwin Biler <buonzz@gmail.com>
*/
class YourClass extends Facade{
   /**
   *  method to be called to return the "real" class, since facade is just a front
   *  note that the yourclass is lowercase, since that is what we had registered in the ServiceProvider
   */
   protected static function getFacadeAccessor(){ return 'yourclass';};
}