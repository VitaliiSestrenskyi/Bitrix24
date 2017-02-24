<php
class AliasLoader
{
    private $aliases = [];

    private static $instance = null;
    private function __construct(){}
    protected function __clone(){}

    public static function getInstance()
    {
        if(is_null(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    private function getAliases()
    {
        return $this->aliases = [
            'CItuaAfa' => '\Itua\Afa\CItuaAfa',
            'CHelper' => '\Itua\Afa\CHelper',
            'CItuaEvents' => '\Itua\Afa\CItuaEvents',
            'CItuaAfaAgents' => '\Itua\Afa\CItuaAfaAgents',            
        ];
    }

    public function register()
    {
        foreach( $this->getAliases() as  $alias=> $full )
        {
            class_alias ( $full , $alias , true );
        }
    }
}
