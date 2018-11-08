<?php

namespace Anker\ModulesBundle\Helper;

use Contao\System;
use Contao\FilesModel;
use Illuminate\Support\Str;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Upload extends System
{
	public function formatBytes($size, $precision = 2)
	{
		$base = log($size, 1024);
		$suffixes = ['', 'KB', 'MB', 'GB', 'TB'];

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}

	public function processPostUpload($arrFiles)
	{
		if (is_array($arrFiles)) {
			foreach ($arrFiles as $file) {
				$objFile = FilesModel::findByPath($file);

				if (in_array($objFile->extension, ['png', 'jpg', 'jpeg'])) {
					$strFile = TL_ROOT . '/' . $file;

					$oldSize = filesize($strFile);

					$optimizerChain = OptimizerChainFactory::create();
					$optimizerChain->optimize($strFile);

					$newSize = filesize($strFile);

					$strFilename = str_replace('.' . $objFile->extension, '', $objFile->name);
					$strFolder   = str_replace($objFile->name, '', $objFile->path);
					$strFilename = Str::slug($strFilename);
					$path        = $strFolder . $strFilename . '.' . $objFile->extension;

					rename($strFile, TL_ROOT . '/' . $path);

					$objFile->tstamp = time();
					$objFile->path = $path;
					$objFile->name = $strFilename . '.' . $objFile->extension;
					$objFile->hash = md5_file(TL_ROOT . '/' . $path);
					$objFile->save();

					System::log('Compression was successful. (New: '.$this->formatBytes($newSize).' | Old: '.$this->formatBytes($oldSize).' | Saved: '.$this->formatBytes($oldSize-$newSize).') (File: ' . $file . ')', __METHOD__, TL_FILES);
				}
			}
		}
	}
}
