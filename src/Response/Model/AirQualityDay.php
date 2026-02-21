<?php

namespace MeteoFlow\Response\Model;

/**
 * Air quality data point.
 */
class AirQualityDay
{
    /**
     * @var string|null Time (ISO 8601)
     */
    public $time;

    /**
     * @var float|null PM2.5
     */
    public $particulateMatter2;

    /**
     * @var float|null PM10
     */
    public $particulateMatter10;

    /**
     * @var float|null Carbon monoxide
     */
    public $carbonMonoxide;

    /**
     * @var float|null Sulphur dioxide
     */
    public $sulphurDioxide;

    /**
     * @var float|null Nitrogen dioxide
     */
    public $nitrogenDioxide;

    /**
     * @var float|null Ozone
     */
    public $ozone;

    /**
     * @var int|null Air quality index
     */
    public $aqi;

    /**
     * Create AirQualityDay from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $item = new self();

        $item->time = isset($data['time']) ? $data['time'] : null;
        $item->particulateMatter2 = isset($data['particulate_matter2']) ? (float) $data['particulate_matter2'] : null;
        $item->particulateMatter10 = isset($data['particulate_matter10']) ? (float) $data['particulate_matter10'] : null;
        $item->carbonMonoxide = isset($data['carbon_monoxide']) ? (float) $data['carbon_monoxide'] : null;
        $item->sulphurDioxide = isset($data['sulphur_dioxide']) ? (float) $data['sulphur_dioxide'] : null;
        $item->nitrogenDioxide = isset($data['nitrogen_dioxide']) ? (float) $data['nitrogen_dioxide'] : null;
        $item->ozone = isset($data['ozone']) ? (float) $data['ozone'] : null;
        $item->aqi = isset($data['aqi']) ? (int) $data['aqi'] : null;

        return $item;
    }
}
