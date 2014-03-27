<?php

interface Cronjob_CronInterface
{

    public function __construct($args=null);

    public function run();

}