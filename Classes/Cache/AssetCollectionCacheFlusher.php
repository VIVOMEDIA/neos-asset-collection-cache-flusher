<?php


namespace VIVOMEDIA\AssetCollectionCacheFlusher\Cache;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Fusion\Core\Cache\ContentCache;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\AssetVariantInterface;
use Psr\Log\LoggerInterface;

/**
 * @Flow\Scope("singleton")
 */
class AssetCollectionCacheFlusher
{
    protected array $tagsToFlush = [];

    /**
     * @Flow\Inject()
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject()
     * @var ContentCache
     */
    protected $contentCache;

    /**
     * @Flow\Inject
     * @var LoggerInterface
     */
    protected $systemLogger;

    public function registerAssetChange(AssetInterface $asset)
    {
        if (!$asset instanceof Asset || $asset instanceof AssetVariantInterface) {
            return;
        }

        $tagName = sprintf('Asset_%s', $this->persistenceManager->getIdentifierByObject($asset));
        $this->tagsToFlush[$tagName] = sprintf('Flush tags for Asset %s', $asset->getTitle());

        /** @var AssetCollection $assetCollection */
        foreach ($asset->getAssetCollections() as $assetCollection) {
            $collectionIdentifier = $this->persistenceManager->getIdentifierByObject($assetCollection);
            $tagName = sprintf('AssetCollection_%s', $collectionIdentifier);
            $this->tagsToFlush[$tagName] = sprintf('Flush tags for AssetCollection %s', $assetCollection->getTitle());
        }
    }

    /**
     * Flush caches according to the previously registered asset changes.
     *
     * @return void
     */
    public function shutdownObject()
    {
        if ($this->tagsToFlush !== []) {
            foreach ($this->tagsToFlush as $tag => $logMessage) {
                $affectedEntries = $this->contentCache->flushByTag($tag);
                if ($affectedEntries > 0) {
                    $this->systemLogger->debug(sprintf('Content cache: Removed %s entries %s', $affectedEntries, $logMessage));
                }
            }
        }
    }
}