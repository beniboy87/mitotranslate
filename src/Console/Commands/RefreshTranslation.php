<?php

namespace Balazsbencs\Translate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class RefreshTranslation extends Command
{
    private static $_output = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshing translation files from Mito Translate';

    /**
     * path for the production message files
     */
    protected $_messageFilePath;

    /**
     * The API's base URL with a trailing backslash
     *
     * @var string
     */
    private $_apiUrl = 'https://translate2.mito.hu/';

    /**
     * set `true` if you want to use the staging server
     * @var bool
     */
    public $useStagingApi = false;

    /**
     * The API key of your application
     *
     * @var string
     */
    public $apiKey;

    /**
     * The name of your message category
     *
     * @var string
     */
    public $messageCategory = 'app';

    /**
     * The name of the file where are the translations
     *
     * @var string
     */
    private $_messageFileName;

    /**
     * Route to the refresh action
     *
     * @var string
     */
    public $refreshActionRoute = 'mito-translate/refresh';

    /**
     * Available language codes for this app in MitoTranslate
     *
     * @var array
     */
    public $languageCodes = [];

    const FORMAT = 'icu';

    /**
     * Execute the console command.
     *
     * @param null $webserverUser
     * @return mixed
     * @throws \Exception
     */
    public function handle($webserverUser = null)
    {
        $this->_messageFilePath = resource_path() . '/lang';
        $this->setMessageFileName();

        if (empty(config('translate.apikey'))) {
            throw new \Exception('The `apikey` property must be set!');
        }

        if (empty(config('translate.language_codes'))) {
            throw new \Exception('The `language_codes` property must be set!');
        }

        if (empty(config('translate.message_category'))) {
            throw new \Exception('The `message_category` property cannot be empty!');
        }

        foreach (config('translate.language_codes') as $languageCode) {
            $contents = $this->download($languageCode);

            if ($contents === false) {
                throw new \Exception('Unable to access the MitoTranslate API!');
            }

            $languagePath = $this->_messageFilePath . $languageCode;

            if (!(File::exists($languagePath) && File::isDirectory($languagePath))) {
                File::makeDirectory($languagePath);
            }
            $filePath = $this->getMessageFilePath($languageCode);

            if (file_put_contents($filePath, $contents) === false) {
                throw new \Exception('Unable to update "' . $languageCode . '" translations!');
            } else {
                if ($webserverUser && !@chgrp($filePath, $webserverUser)) {
                    throw new \Exception("Failed to chown $filePath to $webserverUser");
                }
                if ($webserverUser && !@chmod($filePath, 02775)) {
                    throw new \Exception("Failed to make $filePath writable");
                }
                $this->info('"' . $languageCode . '" translations successfully updated!');
            }
        }
    }

    /**
     * @param $languageCode
     * @return bool|string
     */
    public function download($languageCode)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $this->getApiUrlToCall($languageCode));
        return $res->getStatusCode() === 200 ? $res->getBody()->getContents() : false;
    }

    /**
     * @param $languageCode
     * @return string
     */
    public function getMessageFilePath($languageCode)
    {
        return implode('/', [$this->_messageFilePath, $languageCode, $this->_messageFileName]);
    }

    /**
     *
     */
    private function setMessageFileName()
    {
        $this->_messageFileName = $this->messageCategory . '.json';
    }

    /**
     * @return string
     */
    public function getLanguageFilePath()
    {
        return $this->_messageFilePath;
    }

    /**
     * @param $languageCode
     * @return string
     */
    public function getApiUrlToCall($languageCode)
    {
        return $this->_apiUrl . 'api/download?' . http_build_query(['format' => self::FORMAT, 'languageCode' => $languageCode, 'key' => config('translate.apikey')]);
    }
}
