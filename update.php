<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  1.0.6v1
 */
class J51_LaylaInstallerScript extends InstallerScript
{

	/**
	 * Version des Templates vor dem Update (aus manifest_cache).
	 *
	 * @var string
	 */
	protected $previousVersion = '';

	/**
	 * Merkt sich vor dem Update die bisher installierte Version.
	 */
	public function preflight($type, $installer)
	{
		if ($type === 'update')
		{
			try
			{
				$db    = Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);
				$query = $db->getQuery(true)
					->select($db->quoteName('manifest_cache'))
					->from($db->quoteName('#__extensions'))
					->where($db->quoteName('type') . ' = ' . $db->quote('template'))
					->where($db->quoteName('element') . ' = ' . $db->quote('j51_layla'))
					->where($db->quoteName('client_id') . ' = 0');
				$cache = json_decode((string) $db->setQuery($query)->loadResult(), true);
				$this->previousVersion = \is_array($cache) ? (string) ($cache['version'] ?? '') : '';
			}
			catch (\Exception $e)
			{
				$this->previousVersion = '';
			}
		}

		return parent::preflight($type, $installer);
	}

	/**
	 * Alte Overrides (J3/frühe J4-Versionen) nur beim Update von Versionen vor 2.0.0 entfernen.
	 * Bei neueren Ständen liegen in diesen Ordnern nur noch kundeneigene Overrides – sie bleiben erhalten.
	 */
	protected function isLegacyUpdate($type): bool
	{
		return $type === 'update' && $this->previousVersion !== '' && version_compare($this->previousVersion, '2.0.0', '<');
	}

	/**
	 * Ensure the core templates are correctly moved to the new mode.
	 *
	 * @return  void
	 *
	 * @since   4.1.0
	 */
	protected function fixTemplateMode(): void
	{
		$db = Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);

		$template = 'j51_layla';
		$clientId = 0;
		$query = $db->getQuery(true)
			->update($db->quoteName('#__template_styles'))
			->set($db->quoteName('inheritable') . ' = 1')
			->where($db->quoteName('template') . ' = ' . $db->quote($template))
			->where($db->quoteName('client_id') . ' = ' . $clientId);
    
		try
		{
			$db->setQuery($query)->execute();
		}
		catch (Exception $e)
		{
			echo Text::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $e->getCode(), $e->getMessage()) . '<br>';

			return;
		}
	}
	
	public function postflight($type, $installer)
 	{
		$foldersToDelete = array(
			'/templates/j51_layla/html/com_contact',
			'/templates/j51_layla/html/com_content/article',
			'/templates/j51_layla/html/com_media',
			'/templates/j51_layla/html/com_search',
			'/templates/j51_layla/html/com_users',
			'/templates/j51_layla/html/layout/joomla',
			'/templates/j51_layla/html/mod_breadcrumbs',
			'/templates/j51_layla/html/mod_custom',
			'/templates/j51_layla/html/mod_finder',
			'/templates/j51_layla/html/mod_login',
			'/templates/j51_layla/html/mod_search',
			'/templates/j51_layla/html/mod_tags_popular',
			'/templates/j51_layla/html/plg_content_pagenavigation',
			'/templates/j51_layla/html/plg_content_vote'
		);

		$filesToDelete = array(
			'/templates/j51_layla/html/modules.php'
		);

		if (!$this->isLegacyUpdate($type))
		{
			$foldersToDelete = [];
			$filesToDelete   = [];
		}

		foreach ($foldersToDelete as $folder)
		{
			if ($folderExists = Folder::exists(JPATH_ROOT . $folder))
			{
				Folder::delete(JPATH_ROOT . $folder);
			}
		}

		foreach ($filesToDelete as $file)
		{
			if ($fileExists = File::exists(JPATH_ROOT . $file))
			{
				File::delete(JPATH_ROOT . $file);
			}
		}

		// Ensure templates are moved to the correct mode
		$this->fixTemplateMode();
 	}
}