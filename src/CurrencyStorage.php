<?php declare(strict_types=1);

namespace Lemonade\Currency;

use DateTime;
use Exception;
use RuntimeException;
use SplFileObject;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function filemtime;
use function is_dir;
use function mkdir;
use function sprintf;
use const DIRECTORY_SEPARATOR;

/**
 * CurrencyStorage class
 *
 * This class handles the storage and retrieval of currency exchange data.
 * It downloads exchange rate data from the Czech National Bank (CNB) and stores it in a local file system.
 *
 * @CurrencyStorage
 * @\Lemonade\Currency\CurrencyStorage
 */
final class CurrencyStorage
{
    /**
     * The endpoint for downloading currency exchange data.
     *
     * @var string
     */
    public const SOURCE_ENDPOINT = "https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/rok.txt?rok=%d";

    /**
     * The expected date format in the data file.
     *
     * @var string
     */
    protected const DATA_LINE_FORMAT = "j.n.Y";

    /**
     * The  date format in the data file.
     *
     * @var string
     */
    public const DATA_SAVE_FORMAT = "Y-m-d";

    /**
     * The directory where currency exchange files will be stored.
     *
     * @var string
     */
    private string $directory;

    /**
     * Constructor initializes the directory and fetches data.
     *
     * @param DateTime $date The date for which currency data is being stored.
     */
    public function __construct(protected readonly DateTime $date)
    {
        // Find the root directory of the project
        $projectRoot = dirname(__DIR__, 4); // Navigate out of `vendor/lemonade/component_currency/src`
        $this->directory = $projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'cnb';

        $this->_storeSource();
    }

    /**
     * Retrieves the full path to the storage file for the specified year.
     *
     * @return string The file path for the currency data file.
     */
    public function getFile(): string
    {
        return $this->directory . DIRECTORY_SEPARATOR . $this->date->format("Y") . ".lock";
    }

    /**
     * Retrieves the URL for the CNB currency data for the specified year.
     *
     * @return string The URL for downloading currency exchange data.
     */
    public function getUrl(): string
    {
        return sprintf(CurrencyStorage::SOURCE_ENDPOINT, $this->date->format("Y"));
    }

    /**
     * Retrieves the date associated with the storage instance.
     *
     * @return DateTime The date for which the data is stored.
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Parses and retrieves the currency exchange data from the storage file.
     *
     * @return array<string, array<string, float>> An array of currency exchange rates indexed by date and currency code.
     */
    public function getData(): array
    {
        $data = [];

        if (file_exists($this->getFile())) {
            $source = new SplFileObject($this->getFile(), "r");
            $first = $source->fgetcsv("|");
            $header = [];

            // Parse header row for currency multipliers and codes
            if (is_array($first)) {
                foreach ($first as $i => $value) {
                    if ($i === 0) continue;

                    list($multiplier, $code) = explode(" ", (string)$value);

                    $header[$i] = [
                        "column" => $i,
                        "multiplier" => (int)$multiplier,
                        "code" => $code,
                    ];
                }
            }

            // Process each data row
            while (($row = $source->fgetcsv("|")) !== false) {
                if (!is_array($row)) {
                    continue;
                }

                $inputDate = $row[0] ?? '1970-01-01';
                $dateLine = DateTime::createFromFormat(self::DATA_LINE_FORMAT, $inputDate);

                if ($dateLine === false) {
                    break;
                }

                $item = [];
                foreach ($row as $key => $value) {
                    if ($key === 0) continue;

                    $ivalue = (float)str_replace(",", ".", (string)$value);
                    $item[$header[$key]["code"]] = $ivalue / $header[$key]["multiplier"];
                }

                $data[$dateLine->format(self::DATA_SAVE_FORMAT)] = $item;
            }
        }

        return $data;
    }

    /**
     * Downloads and stores the currency exchange data from the CNB.
     *
     * @return void
     * @throws RuntimeException If the data could not be stored.
     */
    protected function _storeSource(): void
    {
        if ($this->_canCreate()) {
            $dir = $this->getFile();

            if (!is_dir($this->directory)) {
                mkdir($this->directory, 0775, true);
            }

            $success = file_put_contents($dir, file_get_contents($this->getUrl()));

            if ($success === false) {
                throw new RuntimeException("Failed to store currency data from URL: `{$this->getUrl()}` to file: `{$this->getFile()}`");
            }
        }
    }

    /**
     * Determines if a new data file should be created.
     *
     * @return bool True if the file can be created, false otherwise.
     */
    protected function _canCreate(): bool
    {
        return !$this->_validFile() || $this->_validCache();
    }

    /**
     * Checks if the storage file exists and is valid.
     *
     * @return bool True if the file exists and is valid, false otherwise.
     */
    protected function _validFile(): bool
    {
        return is_file($this->getFile()) && file_exists($this->getFile());
    }

    /**
     * Checks if the cached data is still valid.
     *
     * @return bool True if the cache is valid, false otherwise.
     */
    protected function _validCache(): bool
    {
        try {
            $filemtime = filemtime($this->getFile());

            return $filemtime !== false && $filemtime < (time() - 86400); // Cached for less than a day
        } catch (Exception) {
            return false;
        }
    }
}
