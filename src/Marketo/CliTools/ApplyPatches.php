<?php
/* All code covered by the BSD license located at http://silverstripe.org/bsd-license/ */

namespace Marketo\CliTools;

use Composer\Script\Event;

/**
 * Task for applying patch files from a folder to a source trunk
 *
 * Assumes that the patches were created relative to the root folder of the application, and
 * can be applied using patch -p0
 *
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 */
class ApplyPatches {
	const DEFAULT_DIR = 'mysite/patches';
	
	public static function run(Event $event) {
		$patchDir = isset($_ENV['marketo_patch_dir']) ? $_ENV['marketo_patch_dir'] : getcwd() . DIRECTORY_SEPARATOR . static::DEFAULT_DIR;
		$patches = glob($patchDir . '/*.patch');
		
		if (count($patches)) {
			foreach ($patches as $patchFile) {
				$file = escapeshellarg($patchFile);
				$event->getIO()->writeError('<info>Applying patches from ' . $file . ' </info>', false);
				$exec = "patch -r - -p0 --no-backup-if-mismatch -i " . $file;
				$output = `$exec`;
				$event->getIO()->writeError('<info>' . $output . '</info>', false);
			}
		}
	}
}
