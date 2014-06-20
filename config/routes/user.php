<?php
$user = $app['controllers_factory'];
//User Controller Routes
$user->get('/edit/{id}', 'Skel\Controller\User::edit')->value( 'id', null );
$user->get('/show/{id}', 'Skel\Controller\User::detail');
$user->get('/create', 'Skel\Controller\User::create');
$user->post('/create', 'Skel\Controller\User::create');
$user->get('/delete/{id}', 'Skel\Controller\User::delete');
$user->get('/', 'Skel\Controller\User::index')->value('page', 1);
$user->get('/page/{page}', 'Skel\Controller\User::index')->value( 'page', 1 );

//User Controller API Routes
$user->get('/{id}', 'Skel\Controller\User::get');
$user->post('/', 'Skel\Controller\User::create');
$user->put('/{id}', 'Skel\Controller\User::create'); 
$user->delete('/{id}', 'Skel\Controller\User::delete');

$user->after('Skel\Controller\User::after');

return $user;