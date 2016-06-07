<?php

namespace Tev\Typo3Utils\Log\Writer;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Log\Writer\FileWriter as BaseFileWriter;
use TYPO3\CMS\Core\Log\Exception\InvalidLogWriterConfigurationException;

/**
 * File writer that allows writing outside of the site directory.
 */
class FileWriter extends BaseFileWriter
{
    /**
     * Sets the path to the log file.
     *
     * Log file can be outside of given PATH_site directory
     *
     * @param  string  $logFile  Path to the log file, relative to PATH_site
     * @return  \TYPO3\CMS\Core\Log\Writer\WriterInterface
     */
    public function setLogFile($logFile)
    {
        if (strpos($logFile, '://') === false) {
            if (!GeneralUtility::isAbsPath($logFile)) {
                $logFile = PATH_site . $logFile;
            }
        }

        $this->logFile = $logFile;
        $this->openLogFile();

        return $this;
    }
}
