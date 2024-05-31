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
 * @CurrencyStorage
 * @\Lemonade\Currency\CurrencyStorage
 */
final class CurrencyStorage
{

    /**
     * URL
     * @var string
     */
    public const SOURCE_ENDPOINT = "https://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/rok.txt?rok=%d";

    /**
     * @param DateTime $date
     * @param string $directory
     */
    public function __construct(protected readonly DateTime $date, protected readonly string $directory = "./storage/0/export/cnb")
    {

        $this->_storeSource();
    }


    /**
     * @return string
     */
    public function getFile(): string
    {

        return $this->directory . DIRECTORY_SEPARATOR . "cnb_" . $this->date->format(format: "Y") . ".lock";
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {

        return sprintf(CurrencyStorage::SOURCE_ENDPOINT, $this->date->format(format: "Y"));
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {

        $data = [];

        if (file_exists(filename: $this->getFile())) {

            $source = new SplFileObject(filename: $this->getFile(), mode: "r");
            $first = $source->fgetcsv(separator: "|");
            $header = [];

            if(is_array($first)) {
                foreach ($first as $i => $value) {
                    if ($i === 0) continue;

                    list($multiplier, $code) = explode(separator: " ", string: (string) $value);

                    $header[$i] = [
                        "column" => $i,
                        "multiplier" => (int) $multiplier,
                        "code" => $code
                    ];
                }
            }

            while ($row = ($source->fgetcsv(separator: "|") ?? [])) {

                $dateLine = DateTime::createFromFormat(format: "j.n.Y", datetime: ($row["0"] ?? ""));

                if ($dateLine === false) {

                    break;

                } else {

                    $item = [];

                    foreach ($row as $key => $value) {

                        if ($key === 0) {
                            continue;
                        }

                        $ivalue = (float)str_replace(search: ",", replace: ".", subject: (string) $value);

                        $item[$header[$key]["code"]] = $ivalue / $header[$key]["multiplier"];
                    }

                    $data[$dateLine->format(format: "Y-m-d")] = $item;
                }
            }
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function _storeSource(): void
    {

        if ($this->_canCreate()) {

            $dir = $this->getFile();

            if (!is_dir(filename: $this->directory)) {

                mkdir(directory: $this->directory, permissions: 0775, recursive: true);
            }

            $success = file_put_contents(filename: $dir, data: file_get_contents(filename: $this->getUrl()));

            if ($success === FALSE) {

                throw new RuntimeException("Lemonade\\Currency\\Storage " . "url: `{$this->getUrl()}` , soubor: `{$this->getFile()}`");
            }

        }
    }

    /**
     * @return bool
     */
    protected function _canCreate(): bool
    {

        if (!$this->_validFile()) {

            return true;

        } else {

            return $this->_validCache();
        }

    }

    /**
     * @return bool
     */
    protected function _validFile(): bool
    {

        return is_file(filename: $this->getFile()) && file_exists(filename: $this->getFile());
    }

    /**
     * @return bool
     */
    protected function _validCache(): bool
    {

        $valid = false;

        try {

            $filemtime = filemtime(filename: $this->getFile());

            if ($filemtime !== false) {

                $valid = $filemtime < (time() - 86400);
            }

        } catch (Exception) {

        }

        return $valid;

    }


}
/* End of file CurrencyStorage.php */
/* /lemonade/component_currency/src/CurrencyStorage.php */