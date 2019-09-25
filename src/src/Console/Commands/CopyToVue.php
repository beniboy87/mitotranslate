<?php

namespace Balazsbencs\Translate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class TranslateToVue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:vue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy Translation JSON files to VueJS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $success = File::copyDirectory(resource_path('lang'), resource_path('js/lang'));
        $this->info('Laravel translations successfully copied to VueJS folder!');
    }
}
