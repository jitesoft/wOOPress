<?php
return [
    \Jitesoft\wOOPress\Contracts\EventHandlerInterface::class     => \Jitesoft\wOOPress\Services\EventHandler::class,
    \Jitesoft\wOOPress\Contracts\ActionServiceInterface::class    => \Jitesoft\wOOPress\Services\ActionService::class,
    \Jitesoft\wOOPress\Contracts\FilterServiceInterface::class    => \Jitesoft\wOOPress\Services\FilterService::class,
    \Jitesoft\wOOPress\Contracts\OptionServiceInterface::class    => \Jitesoft\wOOPress\Services\OptionService::class,
    \Jitesoft\wOOPress\Contracts\TransientServiceInterface::class => \Jitesoft\wOOPress\Services\TransientService::class
];
