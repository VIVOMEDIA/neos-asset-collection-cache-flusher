# Neos CMS Asset and AssetCollection cache flusher

[![Latest Stable Version](https://poser.pugx.org/vivomedia/neos-asset-collection-cache-flusher/v/stable)](https://packagist.org/packages/vivomedia/neos-asset-collection-cache-flusher)
[![Total Downloads](https://poser.pugx.org/vivomedia/neos-asset-collection-cache-flusher/downloads)](https://packagist.org/packages/vivomedia/neos-asset-collection-cache-flusher)
[![License](https://poser.pugx.org/vivomedia/neos-asset-collection-cache-flusher/license)](https://packagist.org/packages/vivomedia/neos-asset-collection-cache-flusher)

This package provides functionality to flush asset and asset collection caches on changes to themselves or there containing assets.

## Install

Install with composer

```
composer require vivomedia/neos-asset-collection-cache-flusher 
```


## Usage
### Asset
```neosfusion
prototype(SomePackage:Asset) < prototype(Neos.Neos:ContentComponent) {

    renderer = afx`
        ...
    `

    @cache {
        mode = 'cached'
        entryIdentifier {
            asset = ${q(node).property('asset').identifier} // or some other identifier
        }

        entryTags {
            asset = ${'Asset_' + q(node).property('asset').identifier}
        }
    }
}

```

### AssetCollection
```neosfusion
prototype(SomePackage:AssetCollection) < prototype(Neos.Neos:ContentComponent) {

    renderer = afx`
        ...
    `

    @cache {
        mode = 'cached'
        entryIdentifier {
            collection = ${q(node).property('assetCollection')} // or some other identifier
        }

        entryTags {
            collection = ${'AssetCollection_' + q(node).property('assetCollection')}
        }
    }
}
``` 

