<?php

namespace wcf\system\wsdb\event\listener;

use wcf\page\WsdbRecordPage;
use wcf\system\event\listener\AbstractEventListener;
use wcf\system\WCF;
use wcf\system\wsdb\cache\eager\data\DatabaseCacheData;
use wcf\system\wsdb\cache\eager\DatabaseRelationCache;
use wcf\system\wsdb\cache\runtime\RecordRuntimeCache;

final class DatabaseRelationRecordListener extends AbstractEventListener
{
    protected function onAssignVariables(WsdbRecordPage $eventObj): void
    {
        $references = (new DatabaseRelationCache())->getCache()->getReferencedRecords($eventObj->getRecord()->recordID);
        if ($references === []) {
            return;
        }

        $recordIDs = \array_unique(\array_merge(...\array_values($references)));
        if ($recordIDs === []) {
            return;
        }

        RecordRuntimeCache::getInstance()->cacheObjectIDs($recordIDs);

        $referencedRecords = [];
        foreach ($references as $databaseID => $referencedRecordIDs) {
            $database = DatabaseCacheData::getSharedCache()->getDatabaseByID($databaseID);
            $records = [];
            foreach ($referencedRecordIDs as $recordID) {
                $record = RecordRuntimeCache::getInstance()->getObject($recordID);
                if ($record === null || !$record->canRead()) {
                    continue;
                }
                $records[] = $record;
            }

            if ($records !== []) {
                $referencedRecords[] = [
                    'database' => $database->getPhrase(WCF::getLanguage()->languageID, 'recordPlural'),
                    'enableCoverPhoto' => $database->enableCoverPhoto,
                    'records' => $records,
                ];
            }
        }

        if ($referencedRecords === []) {
            return;
        }

        WCF::getTPL()->assign([
            'referencedRecords' => $referencedRecords,
        ]);
    }
}
