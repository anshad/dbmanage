dbmanage
========

Database Backup Management Package for Laravel 4


Installation
------------

The DBManage Service Provider can be installed via Composer by requiring the idevoc/dbmanage package in your laravel project's composer.json.

Note : The dbmanage depends on doctrine/dbal



    {
       "require": {
          "laravel/framework": "4.2.*",
          "doctrine/dbal": "2.5.*@dev",
          "idevoc/dbmanage": "dev-master"
       }
    }
      
   
Next, Update your packages with:

    composer update 
    
Or Install with:

    composer install 
    
    
Usage
-------
    
    
Add the service provider to app/config/app.php, within the providers array.

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

Note: before calling function, you need to set database configurations.
 
Add your backup path in app/config/app.php or just pass it directly.

    return DbManage::backupDatabase(); // backup full db to app/database
    return DbManage::backupDatabase(app_path().'/'); // backup to app path (you can define any path)
    return DbManage::backupDatabase(NULL, 'users,pages'); // backup only users and pages table to default path




