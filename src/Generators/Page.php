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
     * Create a page.
     *
     * You may optionally set the Fluid Pages extension flexform by passing a 'form'
     * hash, which is a set of sheets and their fields.
     *
     * The default sheet name is 'page', so you'll typically be using that.
     *
     * [
     *     'form' => [
     *         'page' => [
     *             'settings.myfield' => 'An example'
     *         ]
     *     ]
     * ]
     *
     * @param  int  $pid  Parent PID
     * @param  array  $attrs  Page attributes
     * @return   int|null  Created page UID or null if failed
     */
    public function create($pid, $attrs = [])
    {
        if (isset($attrs['form'])) {
            $flexFormSheets = [];

            foreach ($attrs['form'] as $sheet => $setup) {
                $flexFormSheets[$sheet] = [];

                foreach ($setup as $key => $value) {
                    $flexFormSheets[$sheet][$key] = [
                        'vDEF' => $value
                    ];
                }
            }

            $attrs['tx_fed_page_flexform'] = [
                'data' => []
            ];

            foreach ($flexFormSheets as $sheet => $setup) {
                $attrs['tx_fed_page_flexform']['data'][$sheet] = [
                    'lDEF' => $setup
                ];
            }

            unset($attrs['form']);
        }

        return parent::create($pid, $attrs);
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
