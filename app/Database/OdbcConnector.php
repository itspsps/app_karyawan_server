<?php

namespace App\Database;

use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;
use PDO;

class OdbcConnector extends Connector implements ConnectorInterface
{
    public function connect(array $config)
    {
        $dsn = $config['dsn'] ?? null;

        if (!$dsn) {
            throw new \InvalidArgumentException("ODBC DSN tidak ditemukan di config.");
        }

        $options = $this->getOptions($config);

        return $this->createConnection($dsn, $config, $options);
    }
}
