<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RssReaderController;

class parseRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:get {url} {method}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск парсера RSS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct( RssReaderController $rssReader )
    {
        parent::__construct();
        
        $this->rssReader = $rssReader;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->rssReader->getNews($this->argument('url'), $this->argument('method') );
    }
}
