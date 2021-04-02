<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        
        'secretaria_pessoa' => [
            'driver' => 'single',
            'path' => storage_path('logs/secretaria_pessoa.log'),
            'level' => 'debug',
        ],
        
        'secretaria_matricula' => [
            'driver' => 'single',
            'path' => storage_path('logs/secretaria_matricula.log'),
            'level' => 'debug',
        ],
        
        'pedagogico_conteudo_lecionado' => [
            'driver' => 'single',
            'path' => storage_path('logs/pedagogico_conteudo_lecionado.log'),
            'level' => 'debug',
        ],

        'pedagogico_frequencia' => [
            'driver' => 'single',
            'path' => storage_path('logs/pedagogico_frequencia.log'),
            'level' => 'debug',
        ],

        'pedagogico_nota' => [
            'driver' => 'single',
            'path' => storage_path('logs/pedagogico_nota.log'),
            'level' => 'debug',
        ],

        'pedagogico_resultado_final' => [
            'driver' => 'single',
            'path' => storage_path('logs/pedagogico_resultado_final.log'),
            'level' => 'debug',
        ],

        'financeiro_recebivel' => [
            'driver' => 'single',
            'path' => storage_path('logs/financeiro_recebivel.log'),
            'level' => 'debug',
        ],

        'financeiro_recebimento' => [
            'driver' => 'single',
            'path' => storage_path('logs/financeiro_recebimento.log'),
            'level' => 'debug',
        ],

        'financeiro_boleto' => [
            'driver' => 'single',
            'path' => storage_path('logs/financeiro_boleto.log'),
            'level' => 'debug',
        ],

        'user_acesso' => [
            'driver' => 'single',
            'path' => storage_path('logs/user_acesso.log'),
            'level' => 'debug',
        ],

        'perfil_permissao' => [
            'driver' => 'single',
            'path' => storage_path('logs/perfil_permissao.log'),
            'level' => 'debug',
        ],

        'portal_aluno' => [
            'driver' => 'single',
            'path' => storage_path('logs/portal_aluno.log'),
            'level' => 'debug',
        ],

        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],

];
