<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlsrv:server=sensus.database.windows.net;database=sensus',
    'username' => 'sadmin',
    'password' => 'KASDbkqwdas^&%1233',
    'charset' => 'utf8',
    'attributes' => array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)


    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
