<?php

use wcf\event\form\option\FormOptionCollecting;
use wcf\event\form\option\SharedConfigurationFormFieldCollecting;
use wcf\system\event\EventHandler;
use wcf\system\wsdb\event\listener\DatabaseRelationSharedConfigurationFormFieldListener;
use wcf\system\wsdb\form\option\DatabaseRelationOption;

return static function (): void {
    EventHandler::getInstance()->register(
        FormOptionCollecting::class,
        static function (FormOptionCollecting $event): void {
            $event->register(new DatabaseRelationOption());
        }
    );

    EventHandler::getInstance()->register(
        SharedConfigurationFormFieldCollecting::class,
        DatabaseRelationSharedConfigurationFormFieldListener::class
    );
};
