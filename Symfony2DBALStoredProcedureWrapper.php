<?php
/*
 * Symfony2 Docterine Meshup
 *
 * uses the docterine db details, create new PDO connection
 * (as docterine wont return the actual resource, just a wrapped object)
 * run the query and loop through reults.
 *
 * example:
 * $sql = "EXEC someSP ?,?,?";
 * $sqlParams = array('param1','param2', 'param3');
 * $conn   = $this->getDoctrine()->getConnection();
 * $results = $conn->multipleResultSetsFetchAll($sql, $sqlParams);
 *
 * ensure you have added the wrapper to your DBAL config:
 * doctrine:
 *      dbal:
 *          wrapper_class:  'Some\Bundle\Doctrine\DBAL\ExtendedConnection'
 *          driver:   %database_driver%
 *          host:     %database_host%
 *          port:     %database_port%
 *          dbname:   %database_name%
 *          user:     %database_user%
 *          password: %database_password%
 *          MultipleActiveResultSets: true
 *
 * $sql should be
 */

namespace Some\Bundle\Doctrine\DBAL;

use Doctrine\DBAL\Connection AS Connection;

class ExtendedConnection extends Connection
{
    /*
     * executes SQL and returns All results (inc. multiple result sets)
     * dynamic sql using ? as param place holders
     *
     * @param $sql          string  Native SQL
     * @param $sqlParams    array   single dimension
     *
     * @return array multi dimension
     */
    public function multipleResultSetsFetchAll($sql, $sqlParams)
    {
        //Get Connection infor from Doctrine
        $params = $this->getParams();
        //create new PDO as we can only access the Docterine PDO as an object only
        //via $this->getWrappedConnection()
        $conn = new \PDO("sqlsrv:Server=".$params['host'].";Database=".$params['dbname'],$params['user'],$params['password']);
        //prepare statement
        $stmt = $conn->prepare($sql);
        //execute statement with params
        $stmt->execute($sqlParams);
        //grab each result set
        do{
            $results[] = $stmt->fetchAll();
        }while($stmt->nextRowset());
        //close $stmt and $conn
        $stmt->closeCursor();
        unset($stmt , $conn);
        //return
        return $results;
    }
}