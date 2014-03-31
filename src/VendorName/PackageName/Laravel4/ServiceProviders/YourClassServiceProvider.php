<?php namespace VendorName\PackageName\Laravel4\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use VendorName\PackageName\YourClass as YourClass;

/**
*  The Laravel4 Service provider to bing your class to the IoC container
*
*  This makes it possible for Laravel to find your classes in the App object
*  like App::make('YourClass');
*  
*/
class YourClassServiceProvider extends ServiceProvider{
	/**
	* Bind the class to IoC container
	*  @return YourClass;
	*/
	public function register(){
		$this->app->bind('yourclass', function(){
			return new YourClass;
		});
	}
}