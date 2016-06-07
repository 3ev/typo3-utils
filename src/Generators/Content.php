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
     * @param  int  $pid
     * @param  string  $plugin  Plugin name, of the form vendorextension_pluginname
     * @param  array  $attrs
     * @return  int|null
     */
    public function createPlugin($pid, $plugin, $attrs = [])
    {
        return $this->create($pid, array_merge($attrs, [
            'CType' => 'list',
            'list_type' => $plugin
        ]));
    }
}
