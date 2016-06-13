<?php

namespace Tev\Typo3Utils\Generators;

/**
 * Generator for content records.
 */
class Content extends Base
{
    /**
     * {@inheritdoc}
     */
    protected function getTable()
    {
        return 'tt_content';
    }

    /**
     * Create a text element.
     *
     * @param  int  $pid
     * @param  array  $attrs
     * @return  int|null
     */
    public function createText($pid, $attrs = [])
    {
        return $this->create($pid, array_merge($attrs, [
            'CType' => 'text'
        ]));
    }

    /**
     * Create a plugin element.
     *
     * Simplifies the creation of the associated FlexForm by allowing you to pass
     * $attrs['form'], containing a of sheet names to flex form config. For example:
     *
     * $attrs['form'] => [
     *     'Sheet1' => [
     *         'settings.title' => 'An Example'
     *     ]
     * ]
     *
     * @param  int  $pid
     * @param  string  $plugin  Plugin name, of the form vendorextension_pluginname
     * @param  array  $attrs
     * @return  int|null
     */
    public function createPlugin($pid, $plugin, $attrs = [])
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

            $attrs['pi_flexform'] = [
                'data' => []
            ];

            foreach ($flexFormSheets as $sheet => $setup) {
                $attrs['pi_flexform']['data'][$sheet] = [
                    'lDEF' => $setup
                ];
            }

            unset($attrs['form']);
        }

        return $this->create($pid, array_merge($attrs, [
            'CType' => 'list',
            'list_type' => $plugin
        ]));
    }

    /**
     * Create a Fluid Content element.
     *
     * Simplifies the creation of the associated FlexForm by allowing you to pass
     * $attrs['form'], containing a hash of property name to value.
     *
     * @param  int  $pid
     * @param  string  $vendor  Studly case (TheVendor) vendor name of ext. to load FCE from
     * @param  string  $ext  Studly case (TheExtension) ext. name to load FCE from
     * @param  string  $file  The FCE file name, including the extension (e.g Content.html)
     * @param  array  $attrs  Optional additional attrs
     * @return  int|null
     */
    public function createFluidContent($pid, $vendor, $ext, $file, $attrs = [])
    {
        // Simplify the build of a flexform

        if (isset($attrs['form'])) {
            $flexFormDef = [];

            foreach ($attrs['form'] as $key => $value) {
                $flexFormDef[$key] = [
                    'vDEF' => $value
                ];
            }

            $attrs['pi_flexform'] = [
                'data' => [
                    'options' => [
                        'lDEF' => $flexFormDef
                    ]
                ]
            ];

            unset($attrs['form']);
        }

        return $this->create($pid, array_merge($attrs, [
            'CType' => 'fluidcontent_content',
            'tx_fed_fcefile' => $vendor.'.'.$ext.':'.$file
        ]));
    }
}
