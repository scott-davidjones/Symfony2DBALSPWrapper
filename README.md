* Symfony2 Docterine Meshup
uses the docterine db details, create new PDO connection (as docterine wont return the actual resource, just a wrapped object) run the query and loop through reults.
** example:
    $sql = "EXEC someSP ?,?,?";
    $sqlParams = array('param1','param2', 'param3');
    $conn   = $this->getDoctrine()->getConnection();
    $results = $conn->multipleResultSetsFetchAll($sql, $sqlParams);

* NB
ensure you have added the wrapper to your DBAL config.yml:
    doctrine:
        dbal:
            wrapper_class:  'Some\Bundle\Doctrine\DBAL\ExtendedConnection'
            driver:   %database_driver%
            host:     %database_host%
            port:     %database_port%
            dbname:   %database_name%
            user:     %database_user%
            password: %database_password%
            MultipleActiveResultSets: true
