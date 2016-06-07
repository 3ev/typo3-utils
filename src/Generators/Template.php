<?php

namespace Tev\Typo3Utils\Generators;

/**
 * Generator for template records.
 */
class Template extends Base
{
    /**
     * {@inheritdoc}
     */
    protected function getTable()
    {
        return 'sys_template';
    }

    /**
     * Create a new template record.
     *
     * You can pass $attrs['constants'] as a hash (constant => value), and it
     * will be correctly converted to string.
     *
     * @param  int  $pid
     * @param  array  $attrs
     * @return  int|null
     */
    public function create($pid, $attrs = [])
    {
        if (isset($attrs['constants'])) {
            $attrs['constants'] = $this->parseTsConstants($attrs['constants']);
        }

        return parent::create($pid, $attrs);
    }

    /**
     * Create an extension template.
     *
     * @param  int  $pid
     * @param  array  $attrs
     * @return  int|null
     */
    public function createExtensionTemplate($pid, $attrs = [])
    {
        return $this->create($pid, array_merge($attrs, [
            'title' => '+ext',
            'root' => 0
        ]));
    }

    /**
     * Parse TS constants into a string.
     *
     * @param  array|string  $tsConstants  Hash of key/values, or string
     * @return  string
     */
    private function parseTsConstants($tsConstants)
    {
        if (is_array($tsConstants)) {
            $parsed = '';
            foreach ($tsConstants as $constant => $value) {
                $parsed .= $constant . ' = ' . $value . PHP_EOL;
            }
            return $parsed;
        }

        return $tsConstants;
    }
}
