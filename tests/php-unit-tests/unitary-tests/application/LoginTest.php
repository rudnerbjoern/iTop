<?php
namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class LoginTest extends ItopDataTestCase {
	protected $sConfigTmpBackupFile;
	protected $sConfigPath;
	protected $sLoginMode;

	protected function setUp(): void {
		parent::setUp();

		clearstatcache();

		// The test consists in requesting UI.php from outside iTop with a specific configuration
		// Hence the configuration file must be tweaked on disk (and restored)
		$this->sConfigPath = MetaModel::GetConfig()->GetLoadedFile();
		$this->sConfigTmpBackupFile = tempnam(sys_get_temp_dir(), "config_");
		file_put_contents($this->sConfigTmpBackupFile, file_get_contents($this->sConfigPath));

		$oConfig = new \Config($this->sConfigPath);
		$this->sLoginMode = "unimplemented_loginmode";
		$oConfig->AddAllowedLoginTypes($this->sLoginMode);

		@chmod($this->sConfigPath, 0770);
		$oConfig->WriteToFile();
		@chmod($this->sConfigPath, 0444);
	}

	protected function tearDown(): void {
		if (! is_null($this->sConfigTmpBackupFile) && is_file($this->sConfigTmpBackupFile)){
			//put config back
			@chmod($this->sConfigPath, 0770);
			file_put_contents($this->sConfigPath, file_get_contents($this->sConfigTmpBackupFile));
			@chmod($this->sConfigPath, 0444);
			@unlink($this->sConfigTmpBackupFile);
		}
		parent::tearDown();
	}

	protected function CallItopUrlByCurl($sUri, ?array $aPostFields=[]){
		$ch = curl_init();

		$sUrl = MetaModel::GetConfig()->Get('app_root_url') . "/$sUri";
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		if (0 !== sizeof($aPostFields)){
			curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
			curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$sOutput = curl_exec($ch);
		curl_close ($ch);

		return $sOutput;
	}
}
