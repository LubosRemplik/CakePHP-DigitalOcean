<?php
namespace DigitalOcean\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Network\Http\Client;

class SshKeysShell extends Shell
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

        $this->url = 'https://api.digitalocean.com/v1/ssh_keys';
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
                'Digital Ocean API for SSH Keys' .
                'https://developers.digitalocean.com/documentation/v1/ssh_keys/'
            )
            ->addSubcommand('all', [
                'help' => 'This method lists all the available public SSH keys ' .
                   'in your account that can be added to a droplet.',
            ]);

        return $parser;
    }

    /**
     * This method lists all the available public SSH keys in your account that can be added to a droplet.
     * https://developers.digitalocean.com/documentation/v1/ssh-keys/
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
            $this->out($response->code);
        }
        return $response;
    }
}
