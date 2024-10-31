<?php

namespace RtmMail\Migrations;

interface MigrationInterface
{
    
    public function migrate();

    
    public function rollback();

    
    public function is_migrated();

    
    public function get_priority();
}