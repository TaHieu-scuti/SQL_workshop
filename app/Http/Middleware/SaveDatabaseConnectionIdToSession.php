<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

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

    private function killOldConnections($oldConnectionIds)
    {
        foreach ($oldConnectionIds as $oldConnectionId) {
            try {
                Log::debug('Killing old mysql connection', [$oldConnectionId]);
                $this->connection->statement('KILL ?', [$oldConnectionId]);
            } catch (QueryException $e) {
                if (strpos(
                        $e->getMessage(),
                        'SQLSTATE[HY000]: General error: 1094 Unknown thread id:'
                    ) !== 0
                ) {
                    throw $e;
                }
                Log::notice($e->getMessage());
            }
        }
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // TODO: don't run if this is an export request
        if ($request->isXmlHttpRequest()) {
            $session = $request->session();

            $connectionId = $this->connection->select('SELECT CONNECTION_ID() as id')[0]->id;

            $windowName = $request->get('windowName');

            $path = $request->path();

            $sessionKey = self::SESSION_KEY_MYSQL_CONNECTION_IDS
                . '$'
                . $windowName
                . '$'
                . $path;

            $explodedPath = explode('/', $path);
            $basePath = $explodedPath[0];

            /* kill old connections if the path is the same as for this
               request or if the first part of the path is different
               from the one of this request */
            foreach ($session->all() as $key => $value) {
                if (strpos($key, self::SESSION_KEY_MYSQL_CONNECTION_IDS . '$' . $windowName) === 0) {
                    $explodedKey = explode('$', $key);
                    if (count($explodedKey) !== 3) {
                        continue;
                    }

                    $explodedUri = explode('/', $explodedKey[2]);
                    $basePathKey = $explodedUri[0];

                    if ($key === $sessionKey || $basePath !== $basePathKey) {
                        $oldConnectionIds = $session->pull($key);
                        $this->killOldConnections($oldConnectionIds);
                    }
                }
            }
            $session->push($sessionKey, $connectionId);
            $session->save();
            Log::debug('Mysql connection id saved to session', [$connectionId]);
        }

        return $next($request);
    }
}
