<?php

namespace RtmMail;

use RtmMail\Helpers\DashboardHelper;


class Dashboard extends AbstractPage
{
    protected $slug = 'dashboard';

    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle_requests()
    {
        if ($this->page_check()) {
                    }
    }

    
    public function display()
    {
                if ($this->page_check()) {
            global $dashboard;
                        $dashboard = DashboardHelper::get();
                        require __DIR__ . '/Views/' . $this->slug . '.php';
        }
    }
}