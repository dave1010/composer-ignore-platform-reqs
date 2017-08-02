<?php

namespace Dave1010\NoPlatformReqs;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\ScriptEvents;
use Composer\Script\Event;
use Composer\Plugin\CommandEvent;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    private $io;
    private $composer;
    private $flag;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function command(CommandEvent $event)
    {
        $input = $event->getInput();
        $this->flag = $input->getOption('ignore-platform-reqs') !== true;
    }

    /**
     * Suggest a helpful flag
     *
     * @todo work on post and only be helpful if requirements can't be resolved
     * @see https://github.com/composer/composer/issues/6583
     */
    public function pre(\Composer\Installer\InstallerEvent $event)
    {
        if ($this->flag) {
            $this->io->write('Try --ignore-platform-reqs');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'pre-dependencies-solving' => [
                ['pre', -100],
            ],
            'command' => [
                ['command']
            ],
        ];
    }
}
