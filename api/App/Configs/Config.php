<?php

namespace Opu\App\Configs;

class Config
{
    // db config
    public static $db_host = '127.0.0.1';
    public static $db_port = '3306';
    public static $db_name = 'adoption';
    public static $db_username = 'root';
    public static $db_password = '2580';

    // app config
    public static $app_name = 'Enventory';
    public static $app_url = 'http://localhost:8080';
    public static $app_theme_color = '#ff0044';
    public static $app_debug = true;

    // email config
    public static $smtp_host = 'mail.broadbrander.com';
    public static $smpt_port = 465;
    public static $smpt_username = 'test@broadbrander.com';
    public static $smpt_password = 'Vv*t],M=Ghg9';
    public static $smpt_from_email = 'test@broadbrander.com';
    public static $smpt_from_name = 'Enventory';
    public static $smpt_reply_to = 'test@broadbrander.com';

    // api access permission
    public static $allowed_domains = [
        'http://localhost:5173',
        'http://localhost:8888',
        'http://127.0.0.1:8888',
        'http://192.168.31.193:8888',
        'https://api.broadbrander.com',
        'https://pos.opu.rocks',
        'https://enventory.xyz',
        'https://localhost',
        'http://localhost',
    ];
}