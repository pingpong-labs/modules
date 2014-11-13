<?php namespace Pingpong\Modules\Publishing;

class MigrationPublisher extends AssetPublisher {

	public function getDestinationPath()
	{
		return $this->repository->config('migration');
	}

	public function getSourcePath()
	{
		return $this->getModule()->getExtraPath($this->repository->config('generator.migration'));
	}

} 