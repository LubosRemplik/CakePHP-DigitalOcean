<?php
namespace Lubos\DigitalOcean\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Http\Client;
use Cake\Utility\String;
use Cake\Utility\Xml;

class EventsShell extends Shell
{

    /**
     * Initial settings on startup
     *
     * @return void
     */
    public function startup()
    {
        $data = Configure::read('DigitalOcean');
        if (!isset($data['client_id']) || !isset($data['api_key'])) {
            $this->error('Please set up DigitalOcean.client_id and DigitalOcean.api_key');
        }

        $this->url = 'https://api.digitalocean.com/v1/events';
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

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description(
                'Digital Ocean API for Events ' .
                'https://developers.digitalocean.com/documentation/v1/events/'
            )
            ->addSubcommand('show', [
                'help' => 'Shows passed event of passed ID',
                'parser' => [
                    'arguments' => [
                        'event_id' => [
                            'help' => 'Event ID',
                            'required' => true
                        ],
                    ]
                ]
            ]);

        return $parser;
    }

    /**
     * Shows passed event of passed ID.
     *
     * @param string $eventId Event ID.
     * @return \Cake\Network\Http\Response
     */
    public function show($eventId)
    {
        $url = $this->url . sprintf('/%s', $eventId);
        $data = Configure::read('DigitalOcean');
        if (!empty($this->params)) {
            $data = array_merge(
                $data,
                $this->params
            );
        }
        $client = new Client();
        $response = $client->get($url, $data);
        if ($response->isOk()) {
            $this->out(pr($response->json));
        } else {
            debug($response);
        }
        return $response;
    }
}
