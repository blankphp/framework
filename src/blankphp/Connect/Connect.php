<?php


namespace BlankPhp\Connect;


interface Connect
{
    public function disconnect();

    public function connect();

    public function reconnect();
}