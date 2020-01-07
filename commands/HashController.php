<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Helper command to echo the hash for a password given.
 */
class HashController extends Controller
{
    /**
     * This command echoes the hash for the password given.
     * @param string $pwd the plaintext password to be hashed.
     * @return int Exit code
     */
    public function actionIndex($pwd = '')
    {
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        echo $hash . "\n";
        echo 'All good? ';
        echo password_verify($pwd, $hash) ? 'TRUE' : 'FALSE';

        return ExitCode::OK;
    }
}
