dbmanage
========

Database Backup Management Package for Laravel 4


Installation
------------

The DBManage Service Provider can be installed via Composer by requiring the idevoc/dbmanage package in your laravel project's composer.json.



    {
       "require": {
          "laravel/framework": "4.1.*",
          "idevoc/dbmanage": "dev-master"
       }
    }
      
   
Next, Update your packages with:

    composer update 
    
Or Install with:

    composer install 
    
Next, add the service provider to app/config/app.php, within the providers array.

    'providers' => array(
         //--
        'Idevoc\Dbmanage\DbmanageServiceProvider',
    )
    
Next, create alias in app/config/app.php, within the aliases array.

    'aliases' => array(
         //--
        'DbManage'   => 'Idevoc\Dbmanage\DbManage',
    )   
    
Finally, call the function.

NB: before calling function, you need to set database configurations.
 
Add your backup path in app/config/app.php or just pass it directly.

    return DbManage::backupDatabase(Config::get('app.backup_path')); 



