<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Database\MySqlConnection;

class KillOldMySqlConnections
{
    const SESSION_KEY_MYSQL_CONNECTION_IDS = 'MysqlConnectionIds';
    const TIME_OUT = 120000;

    /** @var \Illuminate\Database\Connection */
    private $connection;

    /** @var \Illuminate\Session\Store */
    private $session;

    /** @var string */
    private $windowName;

    /** @var string */
    private $sessionKey;

    /** @var string */
    private $basePath;

    /** @var string */
    private $path;

    /** @var int */
    private $now;

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
     * @param int $oldConnectionId
     * @param int $millis
     */
    private function killOldConnection($oldConnectionId, $millis)
    {
        if (($this->now - $millis) < self::TIME_OUT) {
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
     * @param string $key
     */
    private function processSessionKey($key)
    {
        if (strpos($key, self::SESSION_KEY_MYSQL_CONNECTION_IDS . '$' . $this->windowName) === 0) {
            $explodedKey = explode('$', $key);
            if (count($explodedKey) !== 4) {
                return;
            }

            $explodedUri = explode('/', $explodedKey[2]);
            $basePathKey = $explodedUri[0];

            if (strpos($key, $this->sessionKey) === 0 || $this->basePath !== $basePathKey) {
                $oldConnectionId = $this->session->pull($key);
                $this->killOldConnection($oldConnectionId, $explodedKey[3]);
            }
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->windowName = $request->get('windowName');
        $this->path = $request->path();

        if ($request->isXmlHttpRequest() && strpos($this->path, 'export') === false) {
            $this->sessionKey = self::SESSION_KEY_MYSQL_CONNECTION_IDS
                . '$'
                . $this->windowName
                . '$'
                . $this->path;

            $explodedPath = explode('/', $this->path);
            $this->basePath = $explodedPath[0];
            $this->session = $request->session();

            $connectionId = $this->connection->select('SELECT CONNECTION_ID() as id')[0]->id;

            $this->now = round(microtime(true) * 1000);

            /* kill old connections if the path is the same as for this
               request or if the first part of the path is different
               from the one of this request */
            foreach ($this->session->all() as $key => $value) {
                $this->processSessionKey($key);
            }

            $this->session->put($this->sessionKey . '$' . $this->now, $connectionId);
            $this->session->save();
            Log::debug('Mysql connection id saved to session', [$connectionId]);
        }

        return $next($request);
    }
}
