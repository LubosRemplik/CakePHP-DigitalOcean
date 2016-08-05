<?php
namespace Lubos\DigitalOcean\Shell;

use Cake\Utility\String;
use Cake\Utility\Xml;
use Lubos\DigitalOcean\Shell\DigitalOceanShell;

class DropletsShell extends DigitalOceanShell
{

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
                'https://developers.digitalocean.com/documentation/v2/droplets/'
            )
            ->addSubcommand('all', [
                'help' => 'This method returns all active droplets that are currently running in your account.',
            ])
            ->addSubcommand('create', [
                'help' => implode(PHP_EOL, [
                    'This method allows you to create a new droplet.',
                    'Example:',
                    'bin/cake DigitalOcean.droplet create "Test" 66 10321756 5 --ssh_key 739745',
                ]),
                'parser' => [
                    'arguments' => [
                        'name' => [
                            'help' => 'String, this is the name of the droplet',
                            'required' => true
                        ],
                        'region' => [
                            'help' => 'int $region Region of the droplet.',
                            'required' => true
                        ],
                        'size' => [
                            'help' => 'int $size Size of the droplet.',
                            'required' => true
                        ],
                        'image' => [
                            'help' => 'int $image Image of the droplet.',
                            'required' => true
                        ],
                    ],
                    'options' => [
                        'ssh_key' => [
                            'help' => 'Numeric CSV, comma separated list of ssh_key that ' .
                                'you would like to be added to the server'
                        ],
                        'private_networking' => [
                            'help' => 'Boolean, enables a private network interface if the ' .
                                'region supports private networking'
                        ],
                        'backups' => [
                            'help' => 'Boolean, enables backups for your droplet.'
                        ],
                    ]
                ]
            ])
            ->addSubcommand('delete', [
                'help' => 'This method deletes one of your droplets - this is irreversible.',
                'parser' => [
                    'arguments' => [
                        'droplet_id' => [
                            'help' => 'int $dropletId Id of the droplet you want to delete.',
                            'required' => true
                        ],
                    ]
                ]
            ])
            ->addSubcommand('snapshots', [
                'help' => 'This method list snapshots of the droplet.',
                'parser' => [
                    'arguments' => [
                        'droplet_id' => [
                            'help' => 'Numeric, this is the id of your droplet that you want to snapshot',
                            'required' => true
                        ],
                    ]
                ]
            ])
            ->addSubcommand('snapshot', [
                'help' => 'This method allows you to take a snapshot of the droplet.',
                'parser' => [
                    'arguments' => [
                        'droplet_id' => [
                            'help' => 'Numeric, this is the id of your droplet that you want to snapshot',
                            'required' => true
                        ],
                        'name' => [
                            'help' => 'String, this is the name of the new snapshot you want to create.',
                            'required' => true
                        ],
                    ]
                ]
            ]);

        return $parser;
    }

    /**
     * This method returns all active droplets that are currently running in your account.
     *
     * @return \Cake\Network\Http\Response
     */
    public function all()
    {
        $response = $this->client->get('/v2/droplets');
        if ($response->isOk()) {
            $this->out(pr($response->json));

            return $response->json;
        } else {
            debug($response);
        }
    }

    /**
     * This method allows you to create a new droplet.
     *
     * @param string $name Name of the droplet'.
     * @param int $region Region of the droplet.
     * @param int $size Size of the droplet.
     * @param int $image Image of the droplet.
     * @return \Cake\Network\Http\Response
     */
    public function create($name, $region, $size, $image)
    {
        $data = [
            'name' => $name,
            'region' => $region,
            'size' => $size,
            'image' => $image,
        ];
        if (!empty($this->params)) {
            $data = array_merge(
                $data,
                $this->params
            );
        }
        $response = $this->client->post('/v2/droplets', $data);
        if ($response->isOk()) {
            $this->out(pr($response->json));

            return $response->json;
        } else {
            debug($response);
        }
    }

    /**
     * This method deletes one of your droplets - this is irreversible.
     *
     * @param int $dropletId Id of the droplet you want to delete.
     * @return \Cake\Network\Http\Response
     */
    public function delete($dropletId)
    {
        $response = $this->client->delete('/v2/droplets/' . $dropletId);
        if ($response->code == 204) {
            $this->out('Deleted');

            return true;
        } else {
            debug($response);
        }
    }

    /**
     * This method list snapshots of the droplet.
     *
     * @param int $dropletId Id of the droplet.
     * @return \Cake\Network\Http\Response
     */
    public function snapshots($dropletId)
    {
        $response = $this->client->get(sprintf('/v2/droplets/%s/snapshots', $dropletId));
        if ($response->isOk()) {
            $this->out(pr($response->json));

            return $response->json;
        } else {
            debug($response);
        }
    }

    /**
     * This method allows you to take a snapshot of the droplet
     *
     * @param int $dropletId Id of the droplet.
     * @param string $name Snapshot name.
     * @return \Cake\Network\Http\Response
     */
    public function snapshot($dropletId, $name)
    {
        $data = [
            'type' => 'snapshot',
            'name' => $name
        ];
        $response = $this->client->post(
            sprintf('/v2/droplets/%s/actions', $dropletId),
            $data
        );
        if ($response->isOk()) {
            $this->out(pr($response->json));

            return $response->json;
        } else {
            debug($response);
        }
    }
}
