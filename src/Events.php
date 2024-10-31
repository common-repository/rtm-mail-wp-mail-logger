<?php

namespace RtmMail;


use RtmMail\Helpers\EventHelper;


class Events extends AbstractPage
{
    protected $slug = 'events';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle_requests()
    {
            }

    
    public function display()
    {
                if ($this->page_check()) {
                        require __DIR__ . '/Views/' . $this->slug . '.php';
        }
    }
}
