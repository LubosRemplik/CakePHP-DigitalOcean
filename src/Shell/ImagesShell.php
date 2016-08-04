<?php
namespace Lubos\DigitalOcean\Shell;

use Cake\Console\Shell;
use Lubos\DigitalOcean\Shell\DigitalOceanShell;

class ImagesShell extends DigitalOceanShell
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
                'Digital Ocean API for Image ' .
                'https://developers.digitalocean.com/documentation/v2/images/'
            )
            ->addSubcommand('all', [
                'help' => 'This method returns available images.',
            ]);

        return $parser;
    }

    /**
     * This method returns available images.
     *
     * @return \Cake\Network\Http\Response
     */
    public function all()
    {
        $response = $this->client->get('/v2/images');
        if ($response->isOk()) {
            $this->out(pr($response->json));

            return $response->json;
        } else {
            $this->out($response);
        }
    }
}
