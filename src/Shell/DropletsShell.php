<?php
namespace Lubos\DigitalOcean\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Http\Client;
use Cake\Utility\String;
use Cake\Utility\Xml;

class DropletsShell extends Shell
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

        $this->url = 'https://api.digitalocean.com/v1/droplets';
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
                'Digital Ocean API for Droplet ' .
                'https://developers.digitalocean.com/documentation/v1/droplets/'
            )
            ->addSubcommand('all', [
                'help' => 'This method returns all active droplets that are currently running in your account.',
            ])
            ->addSubcommand('create', [
                'help' => implode(PHP_EOL, [
                    'This method allows you to create a new droplet.',
                    'Example:',
                    'bin/cake DigitalOcean.droplet create "Test" 66 10321756 5 --ssh_key_ids 739745',
                ]),
                'parser' => [
                    'arguments' => [
                        'name' => [
                            'help' => 'String, this is the name of the droplet',
                            'required' => true
                        ],
                        'size' => [
                            'help' => 'int $sizeId Size of the droplet.',
                            'required' => true
                        ],
                        'image' => [
                            'help' => 'int $imageId Image of the droplet.',
                            'required' => true
                        ],
                        'region' => [
                            'help' => 'int $regionId Region of the droplet.',
                            'required' => true
                        ]
                    ],
                    'options' => [
                        'ssh_key_ids' => [
                            'help' => 'Numeric CSV, comma separated list of ssh_key_ids that ' .
                                'you would like to be added to the server'
                        ],
                        'private_networking' => [
                            'help' => 'Boolean, enables a private network interface if the ' .
                                'region supports private networking'
                        ],
                        'backups_enabled' => [
                            'help' => 'Boolean, enables backups for your droplet.'
                        ],
                    ]
                ]
            ])
            ->addSubcommand('show', [
                'help' => 'Shows droplet of passed ID',
                'parser' => [
                    'arguments' => [
                        'droplet_id' => [
                            'help' => 'Droplet ID',
                            'required' => true
                        ],
                    ]
                ]
            ])
            ->addSubcommand('destroy', [
                'help' => 'This method destroys one of your droplets - this is irreversible.',
                'parser' => [
                    'arguments' => [
                        'droplet_id' => [
                            'help' => 'int $dropletId Id of the droplet you want to destroy.',
                            'required' => true
                        ],
                    ]
                ]
            ])
            ->addSubcommand('poweroff', [
                'help' => 'This method allows you to poweroff a running droplet. ' .
                    'The droplet will remain in your account.',
                'parser' => [
                    'arguments' => [
                        'droplet_id' => [
                            'help' => 'Integer, this is the id of your droplet that you want to power off',
                            'required' => true
                        ],
                    ],
                ]
            ])
            ->addSubcommand('snapshot', [
                'help' => 'This method allows you to take a snapshot of the droplet once it has been powered off.',
                'parser' => [
                    'arguments' => [
                        'droplet_id' => [
                            'help' => 'Numeric, this is the id of your droplet that you want to snapshot',
                            'required' => true
                        ],
                    ],
                    'options' => [
                        'name' => [
                            'help' => 'String, this is the name of the new snapshot you want to create.'
                        ],
                    ]
                ]
            ]);

        return $parser;
    }

    /**
     * This method returns all active droplets that are currently running in your account.
     * https://developers.digitalocean.com/documentation/v1/droplets/
     *
     * @return \Cake\Network\Http\Response
     */
    public function all()
    {
        $url = $this->url;
        $data = Configure::read('DigitalOcean');
        $client = new Client();
        $response = $client->get($url, $data);
        if ($response->isOk()) {
            $this->out(pr($response->json));
        } else {
            debug($response);
        }
        return $response;
    }

    /**
     * This method allows you to create a new droplet.
     * https://developers.digitalocean.com/documentation/v1/droplets/
     *
     * @param string $name Name of the droplet'.
     * @param int $sizeId Size of the droplet.
     * @param int $imageId Image of the droplet.
     * @param int $regionId Region of the droplet.
     * @return \Cake\Network\Http\Response
     */
    public function create($name, $sizeId, $imageId, $regionId)
    {
        $url = $this->url . '/new';
        $data = Configure::read('DigitalOcean');
        $data = array_merge(
            $data,
            array(
                'name' => $name,
                'size_id' => $sizeId,
                'image_id' => $imageId,
                'region_id' => $regionId,
            )
        );
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

    /**
     * Shows droplet of passed ID.
     *
     * @param string $dropletId Droplet ID.
     * @return \Cake\Network\Http\Response
     */
    public function show($dropletId)
    {
        $url = $this->url . sprintf('/%s', $dropletId);
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

    /**
     * This method destroys one of your droplets - this is irreversible.
     * https://developers.digitalocean.com/documentation/v1/droplets/
     *
     * @param int $dropletId Id of the droplet you want to destroy.
     * @return \Cake\Network\Http\Response
     */
    public function destroy($dropletId)
    {
        $url = $this->url . sprintf('/%s/destroy', $dropletId);
        $data = Configure::read('DigitalOcean');
        $client = new Client();
        $response = $client->get($url, $data);
        if ($response->isOk()) {
            $this->out(pr($response->json));
        } else {
            debug($response);
        }
        return $response;
    }

    /**
     * This method allows you to poweroff a running droplet. The droplet will remain in your account.
     * https://developers.digitalocean.com/documentation/v1/droplets/
     *
     * @param int $dropletId Id of the droplet.
     * @return \Cake\Network\Http\Response
     */
    public function poweroff($dropletId)
    {
        $url = $this->url . sprintf('/%s/power_off', $dropletId);
        $data = Configure::read('DigitalOcean');
        $client = new Client();
        $response = $client->get($url, $data);
        if ($response->isOk()) {
            $this->out(pr($response->json));
        } else {
            debug($response);
        }
        return $response;
    }

    /**
     * This method allows you to take a snapshot of the droplet once it has been powered off
     * https://developers.digitalocean.com/documentation/v1/droplets/
     *
     * @param int $dropletId Id of the droplet.
     * @return \Cake\Network\Http\Response
     */
    public function snapshot($dropletId)
    {
        $url = $this->url . sprintf('/%s/snapshot', $dropletId);
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
