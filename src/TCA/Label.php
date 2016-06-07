<?php

namespace Tev\Typo3Utils\TCA;

use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * TCA label_userFunc class to allow labels consisting of multiple fields.
 *
 * Usage:
 *
 *     'crtl' => [
 *         'label_userFunc' => 'Tev\\Typo3Utils\\TCA\\Label->run',
 *         'label_userFunc_options' => [
 *             // Required, single field name of array of field names
 *
 *             'fields' => [
 *                 'first_name',
 *                 'last_name'
 *             ],
 *
 *             // Optional, defaults to ' '
 *
 *             'glue' => ', '
 *          ]
 *     ]
 *
 * See https://docs.typo3.org/typo3cms/TCAReference/Reference/Ctrl/Index.html#label-userfunc
 * for more info on the label_userFunc option.
 */
class Label
{
    /**
     * Run the user function.
     *
     * @param  array  &$params  Contains 'table', 'row' and options
     * @param  null  $pObj  Is null, but documented
     * @return  void
     */
    public function run(&$params, $pObj)
    {
        if (isset($params['options']) && isset($params['options']['fields'])) {
            $fields = is_array($params['options']['fields']) ?
                $params['options']['fields'] : [$params['options']['fields']];
            $glue = isset($params['options']['glue']) ?
                $params['options']['glue'] : ' ';
            $record = BackendUtility::getRecord($params['table'], $params['row']['uid']);

            $l = [];

            foreach ($fields as $f) {
                if (array_key_exists($f, $record)) {
                    $l[] = $record[$f];
                }
            }

            $params['title'] = implode($glue, $l);
        }
    }
}
