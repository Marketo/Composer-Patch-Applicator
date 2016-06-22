<?php

/* All code covered by the BSD license located at http://silverstripe.org/bsd-license/ */

namespace Marketo\CliTools;

use Composer\Script\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\ProcessExecutor;

/**
 * Task for applying patch files from a folder to a source trunk
 *
 * Assumes that the patches were created relative to the root folder of the application, and
 * can be applied using patch -p0
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 */
class ApplyPatches implements PluginInterface, EventSubscriberInterface
{

    /**
     * @var string $patchDir
     */
    protected $patchDir = "mysite/patches";

    /**
     * @var Composer $composer
     */
    protected $composer;

    /**
     * @var IOInterface $io
     */
    protected $io;

    /**
     * @var ProcessExecutor $executor
     */
    protected $executor;

    /**
     * Initialize the plugin.
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->executor = new ProcessExecutor($this->io);

        $this->configure();
    }

    public static function getSubscribedEvents()
    {
        return array(
            \Composer\Script\ScriptEvents::PRE_INSTALL_CMD => "applyPatches",
            \Composer\Script\ScriptEvents::PRE_UPDATE_CMD => "applyPatches",
        );
    }

    public function applyPatches()
    {
        if (!directory_exists($this->patchDir))
        {
            return;
        }

        $patches = glob($this->patchDir . '/*.patch');

        if (count($patches))
        {
            foreach ($patches as $patchFile)
            {
                $file = excapeshellarg($patchFile);
                $io = $this->io;
                $io->write("<comment>Applying patches from $file.</comment>");
                $this->executor->execute("patch -r - -p0 --no-backup-if-mismatch -i " . $file, function ($type, $data) use ($io)
                {
                    if ($type == Process::ERR)
                    {
                        $io->write('<error>' . $data . '</error>');
                    }
                });
            }
        }
    }

    /**
     * Override the default patch dir from composer.json or env if needed.
     */
    public function configure()
    {
        // Set the patch dir from 'extra' if present.
        $extra = $this->composer->getPackage()->getExtra();
        if (isset($extra['marketo_patch_dir']))
        {
            $this->patchDir = $extra['marketo_patch_dir'];
        }

        // Set the patch dir from the environment if present.
        if (getenv('marketo_patch_dir') !== FALSE)
        {
            $this->patchDir = $extra['marketo_patch_dir'];
        }

        // Expand the path.
        $this->patchDir = getcwd() . DIRECTORY_SEPARATOR . $this->patchDir;
    }
}
