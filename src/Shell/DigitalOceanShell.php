<?php
namespace Lubos\DigitalOcean\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Http\Client;

class DigitalOceanShell extends Shell
{

    /**
     * Initial settings on startup
     *
     * @return void
     */
    public function startup()
    {
        $data = Configure::read('DigitalOcean');
        if (!isset($data['token'])) {
            $this->error('Please set up DigitalOcean.token');
        }

        $this->client = new Client([
            'headers' => ['Authorization' => 'Bearer ' . $data['token']],
            'host' => 'api.digitalocean.com',
            'scheme' => 'https',
        ]);
    }

    /**
     * Execution method always used for tasks
     *
     * @return void
     */
    public function main()
    {
        $this->out($this->OptionParser->help());
    }
}
