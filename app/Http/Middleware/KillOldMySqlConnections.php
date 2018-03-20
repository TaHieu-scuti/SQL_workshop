<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class KillOldMySqlConnections
{
    const SESSION_KEY_MYSQL_CONNECTION_IDS = 'MysqlConnectionIds';

    /** @var \Illuminate\Database\Connection */
    private $connection;

    /**
     * KillOldMySqlConnections constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        if (!($connection instanceof MySqlConnection)) {
            throw new \InvalidArgumentException('This middleware can only work with MySQL');
        }
        $this->connection = $connection;
    }

    /**
     * @param array $oldConnectionIds
     */
    private function killOldConnections(array $oldConnectionIds)
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
     * @param string                     $key
     * @param string                     $windowName
     * @param string                     $sessionKey
     * @param string                     $basePath
     * @param \Illuminate\Session\Store  $session
     */
    private function processSessionKey($key, $windowName, $sessionKey, $basePath, $session)
    {
        if (strpos($key, self::SESSION_KEY_MYSQL_CONNECTION_IDS . '$' . $windowName) === 0) {
            $explodedKey = explode('$', $key);
            if (count($explodedKey) !== 3) {
                return;
            }

            $explodedUri = explode('/', $explodedKey[2]);
            $basePathKey = $explodedUri[0];

            if ($key === $sessionKey || $basePath !== $basePathKey) {
                $oldConnectionIds = $session->pull($key);
                $this->killOldConnections($oldConnectionIds);
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
        $windowName = $request->get('windowName');
        $path = $request->path();

        $sessionKey = self::SESSION_KEY_MYSQL_CONNECTION_IDS
            . '$'
            . $windowName
            . '$'
            . $path;

        $explodedPath = explode('/', $path);
        $basePath = $explodedPath[0];

        if ($request->isXmlHttpRequest() && strpos($path, 'export') === false) {
            $session = $request->session();

            $connectionId = $this->connection->select('SELECT CONNECTION_ID() as id')[0]->id;

            /* kill old connections if the path is the same as for this
               request or if the first part of the path is different
               from the one of this request */
            foreach ($session->all() as $key => $value) {
                $this->processSessionKey($key, $windowName, $sessionKey, $basePath, $session);
            }
            $session->push($sessionKey, $connectionId);
            $session->save();
            Log::debug('Mysql connection id saved to session', [$connectionId]);
        }

        return $next($request);
    }
}
