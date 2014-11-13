<?php namespace Pingpong\Modules\Publishing;

class AssetPublisher extends Publisher {

	public function getDestinationPath()
	{
		return $this->repository->assetPath($this->module);
	}

	public function getSourcePath()
	{
		return $this->getModule()->getExtraPath('Assets');
	}

}