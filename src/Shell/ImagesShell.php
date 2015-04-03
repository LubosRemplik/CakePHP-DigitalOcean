<?php
namespace Lubos\DigitalOcean\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Http\Client;

class ImagesShell extends Shell
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

        $this->url = 'https://api.digitalocean.com/v1/images';
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
                'Digital Ocean API for Image ' .
                'https://developers.digitalocean.com/documentation/v1/images/'
            )
            ->addSubcommand('all', [
                'help' => 'This method returns all the available images that can be accessed by your client ID.',
                'parser' => [
                    'options' => [
                        'filter' => [
                            'help' => 'String, either “my_images” or “global”'
                        ],
                    ]
                ]
            ])
            ->addSubcommand('show', [
                'help' => 'Shows image of passed ID',
                'parser' => [
                    'arguments' => [
                        'image_id' => [
                            'help' => 'image ID',
                            'required' => true
                        ],
                    ]
                ]
            ])
            ->addSubcommand('destroy', [
                'help' => 'This method allows you to destroy an image.',
                'parser' => [
                    'arguments' => [
                        'image' => [
                            'help' => 'Numeric, this is the id of the image you would like to destroy',
                            'required' => true
                        ],
                    ]
                ]
            ]);

        return $parser;
    }

    /**
     * This method returns all the available images that can be accessed by your client ID.
     * https://developers.digitalocean.com/documentation/v1/images/
     *
     * @return \Cake\Network\Http\Response
     */
    public function all()
    {
        $url = $this->url;
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
            $this->out($response->code);
        }
        return $response;
    }

    /**
     * Shows image of passed ID.
     *
     * @param string $imageId Image ID.
     * @return \Cake\Network\Http\Response
     */
    public function show($imageId)
    {
        $url = $this->url . sprintf('/%s', $imageId);
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
     * This method allows you to destroy an image.
     * https://developers.digitalocean.com/documentation/v1/images/
     *
     * @param int $imageId Id of the image you want to destroy.
     * @return \Cake\Network\Http\Response
     */
    public function destroy($imageId)
    {
        $url = $this->url . sprintf('/%s/destroy', $imageId);
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
}
