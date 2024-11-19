<?php

class ProjectContainer extends \Emeset\Container
{
    public $sql = null;

    public function __construct($config){
        parent::__construct($config);
        $this->sql = new Db($config);
    }

    public function response()
    {
        return new \Emeset\Response();
    }

    public function Songs()
    {
        return new Songs($this->sql->get());
    }
}