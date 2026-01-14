<?php

namespace wcf\system\wsdb\event\listener;

use wcf\system\wsdb\cache\eager\DatabaseRelationCache;

final class DatabaseRelationRecordChangeListener
{
    public function __invoke(): void
    {
        (new DatabaseRelationCache())->rebuild();
    }
}
