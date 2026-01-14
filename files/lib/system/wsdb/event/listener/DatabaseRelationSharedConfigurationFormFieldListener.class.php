<?php

namespace wcf\system\wsdb\event\listener;

use wcf\data\wsdb\database\I18nDatabaseList;
use wcf\event\form\option\SharedConfigurationFormFieldCollecting;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\form\builder\field\SelectFormField;

final class DatabaseRelationSharedConfigurationFormFieldListener
{
    public function __invoke(SharedConfigurationFormFieldCollecting $event): void
    {
        $event->register(
            SelectFormField::create('relationDatabaseID')
                ->label('dev.hanashi.wsdb.relation.database')
                ->options($this->getDatabases())
                ->required()
        );
        $event->register(
            BooleanFormField::create('relationCrossLink')
                ->label('dev.hanashi.wsdb.relation.crossLink')
                ->description('dev.hanashi.wsdb.relation.crossLink.description')
        );
        $event->register(
            BooleanFormField::create('relationMultipleSelection')
                ->label('dev.hanashi.wsdb.relation.multipleSelection')
        );
    }

    /**
     * @return array<int, string>
     */
    private function getDatabases(): array
    {
        $databaseList = new I18nDatabaseList();
        $databaseList->sqlOrderBy = 'nameI18n';
        $databaseList->readObjects();

        $databases = [];
        foreach ($databaseList as $database) {
            $databases[$database->databaseID] = $database->getTitle();
        }

        return $databases;
    }
}
