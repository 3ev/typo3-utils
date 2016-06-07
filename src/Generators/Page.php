<?php

namespace Tev\Typo3Utils\Generators;

/**
 * Generator for page records.
 */
class Page extends Base
{
    /**
     * {@inheritdoc}
     */
    protected function getTable()
    {
        return 'pages';
    }

    /**
     * Create a storage folder.
     *
     * @param  int  $pid
     * @param  array  $attrs
     * @return  int|null
     */
    public function createStorageFolder($pid, $attrs = [])
    {
        return $this->create($pid, array_merge($attrs, [
            'doktype' => 254
        ]));
    }
}
