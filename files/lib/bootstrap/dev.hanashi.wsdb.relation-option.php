<?php

use wcf\event\form\option\FormOptionCollecting;
use wcf\event\form\option\SharedConfigurationFormFieldCollecting;
use wcf\event\wsdb\option\OptionCreated;
use wcf\event\wsdb\option\OptionDeleted;
use wcf\event\wsdb\option\OptionDisabled;
use wcf\event\wsdb\option\OptionEnabled;
use wcf\event\wsdb\option\OptionUpdated;
use wcf\event\wsdb\record\RecordCreated;
use wcf\event\wsdb\record\RecordDeleted;
use wcf\event\wsdb\record\RecordDisabled;
use wcf\event\wsdb\record\RecordEnabled;
use wcf\event\wsdb\record\RecordPublished;
use wcf\event\wsdb\record\RecordRestored;
use wcf\event\wsdb\record\RecordSoftDeleted;
use wcf\event\wsdb\record\RecordUpdated;
use wcf\system\event\EventHandler;
use wcf\system\wsdb\event\listener\DatabaseRelationRecordChangeListener;
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

    EventHandler::getInstance()->register(
        RecordCreated::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        RecordDeleted::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        RecordDisabled::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        RecordEnabled::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        RecordPublished::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        RecordRestored::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        RecordSoftDeleted::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        RecordUpdated::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        OptionCreated::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        OptionUpdated::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        OptionDeleted::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        OptionDisabled::class,
        DatabaseRelationRecordChangeListener::class
    );
    EventHandler::getInstance()->register(
        OptionEnabled::class,
        DatabaseRelationRecordChangeListener::class
    );
};
