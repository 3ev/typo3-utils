<?php
namespace Tev\Typo3Utils\Services;

use Exception;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Log\LogManager;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Geocoder\Provider\GoogleMaps;

/**
 * Service class that provides geocoding functionality.
 */
class GeocodingService implements SingletonInterface
{
    /**
     * Underlying geocoding service.
     *
     * @var \Geocoder\Geocoder
     */
    protected $geocoder;

    /**
     * Logger instance.
     *
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;

    /**
     * Geocoding language (en-GB by default).
     *
     * @var string
     */
    private $lang;

    /**
     * Google Maps API key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * @param  \TYPO3\CMS\Core\Log\LogManager $logManager
     * @return void
     */
    public function injectLogManager(LogManager $logManager)
    {
        $this->logger = $logManager->getLogger(__CLASS__);
    }

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->lang = 'en-GB';
        $this->apiKey = null;
        $this->geocoder = null;
    }

    /**
     * Set the ceocoding language.
     *
     * @param  string                                    $lang
     * @return \Tev\Typo3Utils\Services\GeocodingService
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        $this->geocoder = null;

        return $this;
    }

    /**
     * Set the Google Maps API key.
     *
     * @param  string                                    $apiKey
     * @return \Tev\Typo3Utils\Services\GeocodingService
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        $this->geocoder = null;

        return $this;
    }

    /**
     * Geocode a raw string to a lat/lng value.
     *
     * @param  string $details Geocode string
     * @return array           Hash containing lat and lng. Both will be null if geocoding failed
     *
     * @throws \Exception If API key is not set
     */
    public function geocode($details)
    {
        $geo = $this->createGeocoder();

        try {
            $coords = $geo->geocode($details)->first()->getCoordinates();

            return [
                'lat' => $coords->getLatitude(),
                'lng' => $coords->getLongitude()
            ];
        } catch (Exception $e) {
            $this->logger->error('Error geocoding details', [
                'exception_message' => $e->getMessage(),
                'submitted_details' => $details
            ]);

            return [
                'lat' => null,
                'lng' => null
            ];
        }
    }

    /**
     * Create a geocoder object if one doesn't already exist.
     *
     * @return \Geocoder\Geocoder
     *
     * @throws \Exception If API key is not set
     */
    protected function createGeocoder()
    {
        if ($this->apiKey === null) {
            throw new Exception('API key must be set before geocoding.');
        }

        if ($this->geocoder === null) {
            $this->geocoder = new GoogleMaps(
                new CurlHttpAdapter,
                $this->lang,
                null,
                true,
                $this->apiKey
            );
        }

        return $this->geocoder;
    }
}
