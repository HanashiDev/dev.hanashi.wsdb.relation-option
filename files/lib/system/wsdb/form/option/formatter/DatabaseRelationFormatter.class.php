<?php

namespace wcf\system\wsdb\form\option\formatter;

use wcf\data\wsdb\record\Record;
use wcf\data\wsdb\record\RecordList;
use wcf\system\form\option\formatter\IFormOptionFormatter;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;

final class DatabaseRelationFormatter implements IFormOptionFormatter
{
    #[\Override]
    public function format(string $value, int $languageID, array $configuration): string
    {
        $values = \explode(',', $value);
        $records = $this->getRecords(ArrayUtil::toIntegerArray($values));
        $records = \array_filter($records, static fn (Record $record) => $record->canRead());
        if ($records === []) {
            return '';
        }

        $recordLinks = \array_map(
            static function (Record $record): string {
                return StringUtil::getAnchorTag(
                    $record->getLink(),
                    $record->getTitle(),
                    true,
                    (bool)$record->getDatabase()->enableUgc
                );
            },
            $records
        );

        return \implode(', ', $recordLinks);
    }

    /**
     * @param int[] $recordIDs
     * @return Record[]
     */
    private function getRecords(array $recordIDs): array
    {
        $recordList = new RecordList();
        $recordList->setObjectIDs($recordIDs);
        $recordList->sqlSelects = "(
            SELECT  title
            FROM    wcf1_wsdb_record_content
            WHERE   recordID = wsdb_record.recordID
                AND (
                        languageID IS NULL
                    OR languageID = " . WCF::getLanguage()->languageID . "
                    )
            LIMIT   1
        ) AS title";
        $recordList->sqlOrderBy = 'title';
        $recordList->readObjects();

        return $recordList->getObjects();
    }
}
