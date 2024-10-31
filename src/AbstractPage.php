<?php

namespace RtmMail;


abstract class AbstractPage
{
    
    protected $slug;

    public function __construct()
    {
        add_action('admin_init', [$this, 'handle_requests']);
    }

    
    abstract public function handle_requests();

    
    public function display()
    {
        if ($this->page_check()) {
            require __DIR__ . '/Views/' . $this->slug . '.php';
        }
    }

    
    public function page_check()
    {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';
        return (substr($page, 0, 8) === 'rtm-mail') && (explode('-', $page)[2] == $this->slug);
    }
}