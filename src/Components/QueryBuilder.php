<?php

namespace Application\Components;

use Application\Components\Exceptions\QueryBuilderException;
use PDO;


/**
 * Class QueryBuilder
 * @package test1\base
 * @property string $from
 * @property array $join
 * @property array $select
 */
class QueryBuilder
{
    private $select;
    private $joins;
    private $from;
    private $command = "";
    private $where;
    private $andWhere;
    private $binds;
    private $orWhere;

    public function build()
    {
        $this->command .= "SELECT " . implode(',', $this->select);
        $this->command .= " FROM " . $this->from;
        if (!empty($this->joins)) {
            $joins = "";
            foreach ($this->joins as $join) {
                $joins .= " $join[2] JOIN " . $join[0] . " USING ($join[1])";
            }
            $this->command .= $joins;
        }
        if (!empty($this->where)) {
            $this->command .= " WHERE $this->where";
        }

        if (!empty($this->orWhere)) {
            $this->command .= " OR $this->orWhere";
        }

        if (!empty($this->andWhere)) {
            $this->command .= " AND $this->andWhere";
        }
        return $this;
    }

    /**
     * @param \PDO $db
     * @return array
     */
    public function execute($db)
    {
        try {
            $query = $db->prepare($this->command);
            if (!empty($this->binds)) {
                foreach ($this->binds as $bind) {
                    $query->bindParam($bind[0], $bind[1], (int)$bind[2]);
                }
            }
            $query->execute();
            if (!empty($query)) {
                return $query->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\Exception $exception) {
            print "Error :" . $exception->getMessage();
        }
    }

    /**
     * @param array $fields
     * @return $this
     * @throws QueryBuilderException
     */
    public function select($fields)
    {
        if (!empty($fields) && is_array($fields)) {
            $this->select = $fields;
            return $this;
        }
        throw new QueryBuilderException('Incorrect field value "select"');
    }

    /**
     * @param string $table
     * @return $this
     * @throws QueryBuilderException
     */
    public function from($table)
    {
        if (!empty($table)) {
            $this->from = $table;
            return $this;
        }
        throw new QueryBuilderException('Incorrect field value "from"');

    }

    /**
     * @param string $table
     * @param string $type
     * @param string $field
     * @return $this
     * @throws QueryBuilderException
     */
    public function join($table, $field, $type = "LEFT")
    {
        if (!empty($table) && !empty($field)) {
            $this->joins[] = [$table, $field, $type];
            return $this;
        }
        throw new QueryBuilderException('Incorrect field value "join"');
    }


    /**
     * @param string $condition
     * @return $this
     * @throws QueryBuilderException
     */
    public function where($condition)
    {
        if (!empty($condition)) {
            $this->where = $condition;
            return $this;
        }
        throw new QueryBuilderException('Incorrect field value "where"');
    }

    public function andWhere($condition)
    {
        if (!empty($condition)) {
            $this->andWhere = $condition;
            return $this;
        }
        throw new QueryBuilderException('Incorrect field value "and where"');
    }

    public function orWhere($condition)
    {
        if (!empty($condition)) {
            $this->orWhere = $condition;
            return $this;
        }
        throw new QueryBuilderException('Incorrect field value "or where"');
    }

    public function bindParam($key, $value, $pdoParam)
    {
        if (!empty($key) && !empty($value) && !empty($pdoParam)) {
            $this->binds[] = [$key, $value, $pdoParam];
            return $this;
        }
        throw new QueryBuilderException('Incorrect field value "bind param"');
    }
}