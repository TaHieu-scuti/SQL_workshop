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

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isXmlHttpRequest()) {
            Log::debug(getmypid() . ': Request vars: ', $request->all());
            $session = $request->session();
            Log::debug(getmypid() . ': SaveDatabaseConnectionIdToSession ' . $request->path());
            Log::debug(getmypid() . ': current session vars', $session->get(self::SESSION_KEY_MYSQL_CONNECTION_IDS, []));

            $session = $request->session();

            $connectionId = $this->connection->select('SELECT CONNECTION_ID() as id')[0]->id;
            Log::debug(getmypid() . ': Current connection id ' . $connectionId);
            $sessionKey = self::SESSION_KEY_MYSQL_CONNECTION_IDS . '_' . $request->path();
            if ($session->has($sessionKey)) {
                $oldConnectionIds = $session->pull($sessionKey);
                foreach ($oldConnectionIds as $oldConnectionId) {
                    try {
                        Log::debug(getmypid() . ': Killing: ' . $oldConnectionId);
                        $this->connection->statement('KILL ?', [$oldConnectionId]);
                    } catch (QueryException $e) {
                        Log::debug(getmypid() . ': ' . $e->getMessage());
                        if (strpos(
                            $e->getMessage(),
                            'SQLSTATE[HY000]: General error: 1094 Unknown thread id:'
                            ) !== 0) {
                            throw $e;
                        }
                    }
                }
            }
            $session->push($sessionKey, $connectionId);
            $session->save();
            Log::debug(getmypid() . ': connection id saved to session', [$connectionId]);

            //dd($session);
        }

        return $next($request);
    }
}
