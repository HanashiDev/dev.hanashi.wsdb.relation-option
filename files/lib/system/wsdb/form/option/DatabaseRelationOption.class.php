<?php

namespace wcf\system\wsdb\form\option;

use wcf\data\wsdb\record\RecordList;
use wcf\system\database\table\column\AbstractDatabaseTableColumn;
use wcf\system\database\table\column\TextDatabaseTableColumn;
use wcf\system\form\builder\field\AbstractFormField;
use wcf\system\form\builder\field\MultipleSelectionFormField;
use wcf\system\form\builder\field\SelectFormField;
use wcf\system\form\option\AbstractFormOption;
use wcf\system\form\option\formatter\IFormOptionFormatter;
use wcf\system\WCF;
use wcf\system\wsdb\form\option\formatter\DatabaseRelationFormatter;

final class DatabaseRelationOption extends AbstractFormOption
{
    private bool $multipleSelection = false;

    private int $databaseID = 0;

    /**
     * @var array<int, array<int, string>>
     */
    private array $records;

    #[\Override]
    public function getConfigurationFormFields(): array
    {
        return ['required', 'relationDatabaseID', 'relationCrossLink', 'relationMultipleSelection'];
    }

    #[\Override]
    public function getId(): string
    {
        return 'databaseRelation';
    }

    #[\Override]
    public function getFormField(string $id, array $configuration = []): AbstractFormField
    {
        $this->databaseID = $configuration['relationDatabaseID'] ?? 0;
        $options = $this->getRecords($this->databaseID);

        if (isset($configuration['relationMultipleSelection']) && $configuration['relationMultipleSelection']) {
            $this->multipleSelection = true;

            return MultipleSelectionFormField::create($id)
                ->options($options)
                ->filterable();
        } else {
            return SelectFormField::create($id)
                ->options($options);
        }
    }

    #[\Override]
    public function getFormatter(): IFormOptionFormatter
    {
        return new DatabaseRelationFormatter();
    }

    #[\Override]
    public function isFilterable(): bool
    {
        return false;
    }

    #[\Override]
    public function getDatabaseTableColumn(string $name): AbstractDatabaseTableColumn
    {
        return TextDatabaseTableColumn::create($name);
    }

    #[\Override]
    public function serializeValue(mixed $value): string
    {
        if (\is_array($value)) {
            return \implode(',', $value);
        }

        return (string)$value;
    }

    #[\Override]
    public function unserializeValue(string $value): mixed
    {
        $records = $this->getRecords($this->databaseID);

        if ($this->multipleSelection) {
            $values = \explode(',', $value);

            return \array_filter($values, static fn ($realValue) => isset($records[$realValue]));
        }

        return isset($records[$value]) ? $value : '';
    }

    /**
     * @return array<int, string>
     */
    private function getRecords(int $databaseID): array
    {
        if (!isset($this->records)) {
            $recordList = new RecordList();
            $recordList->getConditionBuilder()->add('databaseID = ?', [$databaseID]);
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

            $records = [];
            foreach ($recordList as $record) {
                $records[$record->databaseID][$record->recordID] = $record->getTitle();
            }
            $this->records = $records;
        }

        return $this->records[$databaseID] ?? [];
    }
}
