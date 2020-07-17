# arturgssjr/presto-client

Cliente PHP para PrestoDB via protocolo HTTP

[PrestoBD](https://prestodb.io/)

## O que é Presto?

O Presto é um mecanismo de consulta SQL distribuído de código aberto para executar consultas analíticas interativas em fontes de dados de todos os tamanhos, que variam de gigabytes a petabytes.

## Instalação

*required >= PHP 7.2.5*

```bash
$ composer require artur.junior/presto-client
```

## Usabilidade

### Padrão

```php
<?php

$client = new \ArturJr\PrestoClient\StatementClient(
    new \ArturJr\PrestoClient\ClientSession('http://localhost:8080/', 'catalog'),
    'SELECT * FROM catalog.schema.table'
);
// execute http request
$client->execute();
// next call uri
$client->advance();

/** @var \ArturJr\PrestoClient\QueryResult $result */
// current result
$result = $client->current();

// request cancel
$client->cancelLeafStage();
```

### Operações em Massa

```php
<?php

$client = new \ArturJr\PrestoClient\StatementClient(
    new \ArturJr\PrestoClient\ClientSession('http://localhost:8080/', 'catalog'),
    'SELECT * FROM catalog.schema.table'
);
$resultSession = new \ArturJr\PrestoClient\ResultsSession($client);
// yield results instead of returning them. Recommended.
$result = $resultSession->execute()->yieldResults();

// array
$result = $resultSession->execute()->getResults();
```

## Fetch Styles

### FixData Object

```php
<?php

$client = new \ArturJr\PrestoClient\StatementClient(
    new \ArturJr\PrestoClient\ClientSession('http://localhost:8080/', 'catalog'),
    'SELECT * FROM catalog.schema.table'
);
$resultSession = new \ArturJr\PrestoClient\ResultsSession($client);
$result = $resultSession->execute()->yieldResults();
/** @var \ArturJr\PrestoClient\QueryResult $row */
foreach ($result as $row) {
    foreach ($row->yieldData() as $yieldRow) {
        if ($yieldRow instanceof \ArturJr\PrestoClient\FixData) {
            var_dump($yieldRow->offsetGet('column_name'), $yieldRow['column_name']);
        }
    }
}
```

### Array Keys

```php
<?php

$client = new \ArturJr\PrestoClient\StatementClient(
    new \ArturJr\PrestoClient\ClientSession('http://localhost:8080/', 'catalog'),
    'SELECT * FROM catalog.schema.table'
);
$resultSession = new \ArturJr\PrestoClient\ResultsSession($client);
$result = $resultSession->execute()->yieldResults();
/** @var \ArturJr\PrestoClient\QueryResult $row */
foreach ($result as $row) {
    /** @var array $item */
    foreach ($row->yieldDataArray() as $item) {
        if (!is_null($item)) {
            var_dump($item);
        }
    }
}
```

### Mapeando Classes

```php
<?php

class Testing
{
    private $_key;

    private $_value;
}

$client = new \ArturJr\PrestoClient\StatementClient(
    new \ArturJr\PrestoClient\ClientSession('http://localhost:8080/', 'catalog'),
    'SELECT * FROM catalog.schema.table'
);
$resultSession = new \ArturJr\PrestoClient\ResultsSession($client);
$result = $resultSession->execute()->yieldResults();
/** @var \ArturJr\PrestoClient\QueryResult $row */
foreach ($result as $row) {
    foreach($row->yieldObject(Testing::class) as $object) {
        if ($object instanceof Testing) {
            var_dump($object);
        }
    }
}
```