<?PHP
    require_once "../../vendor/autoload.php";
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    
    $key = 'zdfg224tx@46';
    $key2 = 'zdfg224x@46';
    $payload = [
        'iss' => 'http://example.org',
        'aud' => 'http://example.com',
        'iat' => 1356999524,
        'nbf' => 1357000000
    ];
    
    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
    $jwt = JWT::encode($payload, $key, 'HS256');
    //$jwt="eyJ0eXAiOiJKV1QiLCJ7bGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJhdWQiOiJodHRwOi8vaGVsbG8uY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDB9.dbnb6k0Eu6t3L0Q8qpXKFzHAvjmMcnHCrh7LpMHfdSI";
    echo $jwt;
    echo"<br />";
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
    
    print_r($decoded);
    
    /*
     NOTE: This will now be an object instead of an associative array. To get
     an associative array, you will need to cast it as such:
    */
    
    $decoded_array = (array) $decoded;
    
    /**
     * You can add a leeway to account for when there is a clock skew times between
     * the signing and verifying servers. It is recommended that this leeway should
     * not be bigger than a few minutes.
     *
     * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
     */
    JWT::$leeway = 60; // $leeway in seconds
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    $decoded_array = (array) $decoded;
?>