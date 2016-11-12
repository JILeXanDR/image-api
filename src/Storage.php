<?php

namespace app;

abstract class Storage
{
    abstract public function uploadFileByUrl(string $url);
}