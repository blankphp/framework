<?php

namespace BlankPhp\Request\Secure;

class SlashString extends BaseSecure
{

    /**
     * @param $value
     * @return array|string
     */
    private function stripSlashesDeep($value)
    {
        //递归方式解决不安全字符
        return is_array($value) ? array_map([$this, 'stripSlashesDeep'], $value) : stripslashes($value);
    }

    public function runBefore($data)
    {
        return [$this->stripSlashesDeep($data)];
    }

    public function runAfter($data)
    {
        // TODO: Implement runAfter() method.
    }
}
