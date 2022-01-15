<?php

namespace BlankPhp\Base\Traits;

trait ContainerBindMake
{
    private $bindThisObj = [];

    public function useThis($instance)
    {
        $this->bindThisObj[] = $instance;
    }

    public function cleanThem()
    {
        foreach ($this->bindThisObj as $item) {
            app()->cleanInstance($item);
        }
    }

}
