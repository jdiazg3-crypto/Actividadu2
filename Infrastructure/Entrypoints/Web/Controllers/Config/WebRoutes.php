<?php

declare(strict_types=1);

final class WebRoutes
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function routes(): array
    {
        return array(
            'home' => array(
                'method' => 'GET',
                'action' => 'home',
            ),
            'users.create' => array(
                'method' => 'GET',
                'action' => 'create',
            ),
            'users.store' => array(
                'method' => 'POST',
                'action' => 'store',
            ),
            'users.index' => array(
                'method' => 'GET',
                'action' => 'index',
            ),
            'users.show' => array(
                'method' => 'GET',
                'action' => 'show',
            ),
            'users.edit' => array(
                'method' => 'GET',
                'action' => 'edit',
            ),
            'users.update' => array(
                'method' => 'POST',
                'action' => 'update',
            ),
            'users.delete' => array(
                'method' => 'POST',
                'action' => 'delete',
            ),
            'auth.login' => array(
                'method' => 'GET',
                'action' => 'login',
            ),
            'auth.authenticate' => array(
                'method' => 'POST',
                'action' => 'authenticate',
            ),
            'auth.logout' => array(
                'method' => 'GET',
                'action' => 'logout',
            ),
            'auth.forgot' => array(
                'method' => 'GET',
                'action' => 'forgot',
            ),
            'auth.forgot.send' => array(
                'method' => 'POST',
                'action' => 'forgot.send',
            ),
                        'equipos.index' => array(
                            'method' => 'GET',
                            'action' => 'equipos.index',
                        ),
                        'equipos.create' => array(
                            'method' => 'GET',
                            'action' => 'equipos.create',
                        ),
                        'equipos.store' => array(
                            'method' => 'POST',
                            'action' => 'equipos.store',
                        ),
                        'equipos.show' => array(
                            'method' => 'GET',
                            'action' => 'equipos.show',
                        ),
                        'equipos.edit' => array(
                            'method' => 'GET',
                            'action' => 'equipos.edit',
                        ),
                        'equipos.update' => array(
                            'method' => 'POST',
                            'action' => 'equipos.update',
                        ),
                        'equipos.confirmDelete' => array(
                            'method' => 'GET',
                            'action' => 'equipos.confirmDelete',
                        ),
                        'equipos.delete' => array(
                            'method' => 'POST',
                            'action' => 'equipos.delete',
                        ),
                        'equipos.search' => array(
                            'method' => 'GET',
                            'action' => 'equipos.search',
                        ),
        );
    }
}