<?php

namespace wcf\system\wsdb\cache\eager;

use wcf\data\wsdb\option\OptionList;
use wcf\system\cache\eager\AbstractEagerCache;
use wcf\system\WCF;
use wcf\system\wsdb\cache\eager\data\DatabaseRelationCacheData;

/**
 * @extends AbstractEagerCache<DatabaseRelationCacheData>
 */
final class DatabaseRelationCache extends AbstractEagerCache
{
    #[\Override]
    protected function getCacheData(): DatabaseRelationCacheData
    {
        $optionList = new OptionList();
        $optionList->getConditionBuilder()->add('optionType = ?', ['databaseRelation']);
        $optionList->getConditionBuilder()->add('isDisabled = ?', [0]);
        $optionList->readObjects();

        $references = [];
        foreach ($optionList as $option) {
            $sql = "
                SELECT      recordID,
                            option" . $option->optionID . " value
                FROM        wcf1_wsdb_db" . $option->databaseID . "_record_data
                WHERE       option" . $option->optionID . " IS NOT NULL
                        AND option" . $option->optionID . " <> ?
            ";
            $statement = WCF::getDB()->prepare($sql);
            $statement->execute(['']);

            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $values = \explode(',', $row['value']);
                foreach ($values as $value) {
                    $references[(int)$value][$option->databaseID][] = $row['recordID'];
                }
            }
        }

        return new DatabaseRelationCacheData($references);
    }
}
