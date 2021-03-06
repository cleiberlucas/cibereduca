<?php

use Illuminate\Support\Facades\Auth;

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'Rede Educa',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => 'Rede Educa',
    'logo_img' => 'vendor/adminlte/dist/img/logo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Rede Educa',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#661-authentication-views-classes
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#662-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#67-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#68-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#69-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => '/',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#610-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#611-menu
    |
    */

    'menu' => [
        [
            'text' => 'search',
            'search' => true,
            'topnav' => true,
        ],
        
        [
            'text'    => ' Secretaria',
            'icon' => 'fas fa-fw fa-school',
            'icon_color' => 'green',
            'can'   => 'Secretaria Ver',
            'submenu' => [
                [
                    'text' => 'Turmas/Matrículas',
                    'url'  => 'secretaria/turmas',
                    'icon_color' => 'green',
                    'can'  => 'Turma Ver',
                ],                
                [
                    'text' => 'Alunos',
                    'url'  => 'secretaria/1/pessoas',
                    'icon_color' => 'green',
                    'can'  => 'Pessoa Ver',
                ],
                [
                    'text' => 'Responsáveis',
                    'url'  => 'secretaria/2/pessoas',
                    'icon_color' => 'green',
                    'can'  => 'Pessoa Ver',
                ],
                [
                    'text' => 'Declarações',
                    'route'  => 'matriculas.documentos_escola.create',
                    'icon_color' => 'green',
                    'can'  => 'Matrícula Ver',
                ],
                [
                    'text'    => 'Relatórios',
                    'route'  => 'secretaria.relatorios.index',
                    'icon' => 'far fa-file-alt',
                    'icon_color' => 'green',  
                    'can'  => 'Pessoa Ver',              
                ],
                /* [
                    'text' => 'Histórico',
                    'url'  => '#',
                    'icon_color' => 'green',
                ], */
            ],
        ],
        [
            'text'    => 'Pedagógico',
            'icon'    => 'fas fa-chalkboard-teacher',
            'icon_color' => 'yellow',
            'can'  => 'Pedagógico Ver',
            'submenu' => [
                [
                    'text'    => 'Diários',
                    'icon'    => 'fas fa-book',
                    'url'  => 'pedagogico/turmas',
                    'icon_color' => 'yellow',
                    /* 'submenu' => [
                        [
                            'text' => 'Lançamentos',
                            'url'  => 'pedagogico/turmas',
                            'icon_color' => 'yellow',
                        ],
                        

                    ], */
                ],               
                [
                    'text'    => 'Avaliações',
                    'icon'    => 'fas fa-file-alt',
                    'icon_color' => 'yellow',
                    'submenu' => [
                        [
                            'text' => 'Cadastrar Avaliação',                                
                            'url'  => 'pedagogico/tiposturmas',
                            'icon_color' => 'yellow',
                            'can'  => 'Avaliação Ver',
                        ],
                        [
                            'text' => 'Lançar Notas',                                
                            'route' => 'turmas.index.notas',
                            'icon_color' => 'yellow',
                            'can'  => 'Nota Ver',
                        ],
                        [
                            'text' => 'Recuperação Final',                                
                            'route' => 'recuperacaofinal.index',
                            'icon_color' => 'yellow',
                            'can'  => 'Recuperação Final Ver',
                        ],
                    ],                   
                ],
                [
                    'text' => 'Resultado Final',                                
                    'route' => 'resultadofinal.index.turmas',
                    'icon' => 'fas fa-clipboard-check',
                    'icon_color' => 'yellow',
                    'can'  => 'Resultado Final Cadastrar',
                ],
                [
                    'text' => 'Relatórios',
                    'route'  => 'turmas.relatorios.diarios',
                    'icon' => 'far fa-file-alt',
                    'icon_color' => 'yellow',
                ],
                /* [
                    'text' => 'Boletins',
                    'url'  => '#',
                    'icon_color' => 'yellow',
                ], */
            ],
        ],
        ['text'    => 'Financeiro',
            'icon'    => 'fas fa-dollar-sign',                        
            'icon_color' => 'blue',  
            'can'  => 'Recebível Ver',          
            'submenu' => [
                [
                    'text' => 'Recebíveis',
                    'route'  => 'financeiro.index',
                    'icon' => 'fas fa-hand-holding-usd',
                    'icon_color' => 'blue',
                    'can'  => 'Recebível Ver',
                ],
                [
                    'text' => 'Boletos',
                    'route'  => 'boleto.relatorio',
                    'icon' => 'fas fa-barcode',
                    'icon_color' => 'blue',
                    'can'  => 'Boleto Ver',
                ],
                [
                    'text' => 'Remessas',
                    'route'  => 'remessa.index',
                    'icon' => 'fas fa-barcode',
                    'icon_color' => 'blue',
                    'can'  => 'Remessa Ver',
                ],
                [
                    'text' => 'Retornos',
                    'route'  => 'retorno.index',
                    'icon' => 'fas fa-barcode',
                    'icon_color' => 'blue',
                    'can'  => 'Retorno Ver',
                ],
                [
                    'text' => 'Relatórios',
                    'route'  => 'recebiveis.relatorios.index',
                    'icon' => 'far fa-file-alt',
                    'icon_color' => 'blue',
                    'can'   => 'Recebível Ver',
                ],
            ],
        ],
        [
            'text'    => 'Biblioteca',
            'icon'    => 'fas fa-book',                        
            'icon_color' => 'yellow',  
            'can'  => 'Acervo Ver',          
            'submenu' => [
                [
                    'text' => 'Acervo',
                    'route'  => 'financeiro.index',                    
                    'icon_color' => 'yellow',
                    'icon'    => 'fas fa-book',                        
                    'can'  => 'Acervo Ver',
                ],
                [
                    'text' => 'Autores',
                    'route'  => 'boleto.relatorio',                    
                    'icon_color' => 'yellow',
                    'can'  => 'Autor Ver',
                ],
                [
                    'text' => 'Editoras',
                    'route'  => 'boleto.relatorio',                    
                    'icon_color' => 'yellow',
                    'can'  => 'Editora Ver',
                ],
                [
                    'text' => 'Assuntos',
                    'route'  => 'boleto.relatorio',                    
                    'icon_color' => 'yellow',
                    'can'  => 'Assunto Ver',
                ],
            ],
        ],
        [
            'text'    => 'Captação',
            'icon'    => 'fas fa-plus',                        
            'icon_color' => 'orange',            
            'can'  => 'Captação Ver',
            'submenu' => [
                [
                    'text' => 'Captações',
                    'route'  => 'captacao.index',
                    'icon' => 'fas fa-plus-circle',
                    'icon_color' => 'orange',
                    'can'  => 'Captação Ver',
                ],
            ],
        ],
        /* Menu somente para o responsável */
        [
            'text'    => 'Opção Educacional',
            'route'  => 'opcaoeducacional.responsavel',
            'icon'    => 'fas fa-book-reader',                        
            'icon_color' => 'green',            
            'can'  => 'Opção Educacional Responsável',
        ],
        /* Menu da escola */
        [
            'text'    => 'Opção Educacional',            
            'icon'    => 'fas fa-book-reader',                        
            'icon_color' => 'green',            
            'can'  => 'Opção Educacional Ver',
            'submenu' => [
                [
                    'text' => 'Listagem',
                    'route'  => 'opcaoeducacional.index',                    
                    'icon_color' => 'green',
                    'can'  => '',
                ],
            ],
        ],
        [
            'text'    => 'Configurações',
            'icon'    => 'fas fa-fw fa-cogs',
            'icon_color' => 'red',   
            'can'  => 'Configurações Ver',
                   
            'submenu' => [
                [
                    'text' => 'Unidade Ensino',
                    'route'  => 'unidadesensino.index',                     
                    'icon_color' => 'red',
                    'can'  => 'Unidade Ensino Ver',
                ],
                [
                    'text' => 'Anos Letivos',
                    'url'  => 'admin/anosletivos',
                    'icon_color' => 'red',
                    'can'  => 'Ano Letivo Ver',
                ],
                [
                    'text' => 'Bimestres',
                    'url'  => 'admin/periodosletivos',
                    'icon_color' => 'red',
                    'can'  => 'Período Letivo Ver',
                ],               
                [
                    'text' => 'Padrões de Turmas',
                    'url'  => 'admin/tiposturmas',
                    'icon_color' => 'red',
                    'can'  => 'Padrão Turma Ver',
                ],
                [
                    'text' => 'ExtraCurriculares',
                    'route'  => 'extracurriculares.index',
                    'icon_color' => 'red',
                    'can'  => 'Tipo Atividade ExtraCurricular Ver',
                ],
                
                [
                    'text'    => 'Parâmetros Gerais',
                    'url'     => '#',
                    'icon'    => 'fas fa-fw fa-cog',
                    'icon_color' => 'red',
                    'submenu' => [
                        [
                            'text' => 'Atendimentos Especializados',
                            'url'  => 'admin/atendimentosespecializados',
                            'icon_color' => 'red',
                        ],
                        [
                            'text' => 'Disciplinas',
                            'url'  => 'secretaria/disciplinas',
                            'icon_color' => 'red',                            
                        ],  
                        [
                            'text' => 'Documentos Matrícula',
                            'url'  => 'admin/tiposdocumentos',
                            'icon_color' => 'red',
                        ],
                        [
                            'text' => 'Tipos Avaliações',
                            'url'  => 'pedagogico/tiposavaliacoes',
                            'icon_color' => 'red',
                        ],
                        [
                            'text' => 'Tipos Frequências',
                            'url'  => 'pedagogico/tiposfrequencias',
                            'icon_color' => 'red',
                        ],
                        [
                            'text' => 'Tipos Desconto Curso',
                            'url'  => 'admin/descontoscursos',
                            'icon_color' => 'red',
                        ],
                        [
                            'text' => 'Tipos Docs Identidade',
                            'url'  => 'admin/tiposdocidentidade',
                            'icon_color' => 'red',
                        ],
                        
                    ],
                ],
               
               /*  [
                    'text'    => 'level_one',
                    'url'     => '#',
                    'submenu' => [
                        [
                            'text' => 'level_two',
                            'url'  => '#',
                        ],
                        [
                            'text'    => 'level_two',
                            'url'     => '#',
                            'submenu' => [
                                [
                                    'text' => 'level_three',
                                    'url'  => '#',
                                ],
                                [
                                    'text' => 'level_three',
                                    'url'  => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'level_one',
                    'url'  => '#',
                ], */
            ],
        ],
       
        ['text'    => 'Usuários',
            'icon'    => 'fas fa-fw fa-users-cog',
            'can'  => 'Usuário Ver',
            'submenu' => [
                [
                    'text' => 'Usuários',
                    'url'  => 'admin/users',
                    'icon' => 'fas fa-fw fa-user',
                    'can'  => 'Usuário Ver',
                ],
                [
                    'text' => 'Perfis',
                    'url'  => 'admin/perfis',
                    'icon' => 'fas fa-fw fa-address-book',
                    'can'  => 'Perfil Ver',
                ],
                [
                    'text' => 'Permissões',
                    'url'  => 'admin/permissoes',
                    'icon' => 'fas fa-fw fa-lock',
                    'can'  => 'Permissões', //somente Cleiber
                ],                
            ],
        ],
       /*  [
            'text' => 'Alterar Senha',
            'route'  => 'users.editsenha',                   
            'icon' => 'fas fa-fw fa-lock',
             //'can'  => 'Usuarios', 
        ], */
        
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#612-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#613-plugins
    |
    */

    'plugins' => [
        [
            'name' => 'Datatables',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        [
            'name' => 'Select2',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        [
            'name' => 'Chartjs',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        [
            'name' => 'Sweetalert2',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        [
            'name' => 'Pace',
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],
];
