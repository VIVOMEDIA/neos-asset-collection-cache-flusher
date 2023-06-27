<?php


namespace VIVOMEDIA\AssetCollectionCacheFlusher\Aspect;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Repository\AssetRepository;

/**
 * @Flow\Aspect()
 */
class UpdateAssetOnAddAssetToCollectionAspect
{
    /**
     * @Flow\Inject
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * Updates assets on asset collection change to trigger "assetUpdated" signal in AssetService.
     *
     * @Flow\AfterReturning("method(Neos\Media\Browser\Controller\AssetController->addAssetToCollectionAction())")
     */
    public function updateAssetOnAddAssetToCollection(\Neos\Flow\Aop\JoinPointInterface $joinPoint)
    {
        /** @var Asset $asset */
        $asset = $joinPoint->getMethodArgument('asset');

        /** @var AssetCollection $assetCollection */
        $assetCollection = $joinPoint->getMethodArgument('assetCollection');

        $collections = $asset->getAssetCollections();
        $collections->add($assetCollection);

        $asset->setAssetCollections($collections);
        $this->assetRepository->update($asset);
    }
}
