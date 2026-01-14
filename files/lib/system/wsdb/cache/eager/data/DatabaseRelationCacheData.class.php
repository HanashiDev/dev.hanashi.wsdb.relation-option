<?php

namespace wcf\system\wsdb\cache\eager\data;

final class DatabaseRelationCacheData
{
    /**
     * @param array<int, array<int, int[]>> $references
     */
    public function __construct(private readonly array $references)
    {
    }

    /**
     * @return array<int, int[]>
     */
    public function getReferencedRecords(int $recordID): array
    {
        return $this->references[$recordID] ?? [];
    }
}
