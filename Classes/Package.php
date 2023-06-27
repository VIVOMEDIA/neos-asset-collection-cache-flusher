<?php

namespace VIVOMEDIA\AssetCollectionCacheFlusher;

use VIVOMEDIA\AssetCollectionCacheFlusher\Cache\AssetCollectionCacheFlusher;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Media\Domain\Service\AssetService;

class Package extends BasePackage
{
    /**
     * Invokes custom PHP code directly after the package manager has been initialized.
     *
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(
            AssetService::class, 'assetCreated',
            AssetCollectionCacheFlusher::class, 'registerAssetChange'
        );
        $dispatcher->connect(
            AssetService::class, 'assetRemoved',
            AssetCollectionCacheFlusher::class, 'registerAssetChange'
        );
        $dispatcher->connect(
            AssetService::class, 'assetUpdated',
            AssetCollectionCacheFlusher::class, 'registerAssetChange'
        );
        $dispatcher->connect(
            AssetService::class, 'assetResourceReplaced',
            AssetCollectionCacheFlusher::class, 'registerAssetChange'
        );
    }
}
