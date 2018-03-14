<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Log;

class SaveDatabaseConnectionIdToSession
{
    const SESSION_KEY_MYSQL_CONNECTION_IDS = 'MysqlConnectionIds';

    /** @var \Illuminate\Database\Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        if (!($connection instanceof MySqlConnection)) {
            throw new \InvalidArgumentException('This middleware can only work with MySQL');
        }
        $this->connection = $connection;
    }

    /**
     * Query for
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::debug('SaveDatabaseConnectionIdToSession');

        if ($request->isXmlHttpRequest()) {
            /*var_dump($request->path());
            dd($request->session()->all());*/
            // TODO use full uri for the connection id's and cancel the old one if it exists
            $connectionId = $this->connection->select('SELECT CONNECTION_ID() as id')[0]->id;
            $request->session()->push(self::SESSION_KEY_MYSQL_CONNECTION_IDS, $connectionId);
        }

        return $next($request);
    }
}
