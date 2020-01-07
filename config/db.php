<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlsrv:server=[insert server here];database=sensus',
//    'dsn' => 'mysql:host=[insert host here];dbname=sensus',
    'username' => '[insert uid here]',
    'password' => '[insert pwd here]',
    'charset' => 'utf8',
    'attributes' => array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)


    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
