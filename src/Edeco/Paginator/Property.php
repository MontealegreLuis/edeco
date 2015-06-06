<?php
class Edeco_Paginator_Property extends Zend_Paginator
{
    const PROPERTIES_TAG = 'properties';

    /**
     * Returns the items for a given page.
     *
     * @return Traversable
     */
    public function getItemsByPage($pageNumber)
    {
        $pageNumber = $this->normalizePageNumber($pageNumber);

        if ($this->_cacheEnabled()) {
            $data = self::$_cache->load($this->_getCacheId($pageNumber));
            if ($data !== false) {
                return $data;
            }
        }

        $offset = ($pageNumber - 1) * $this->getItemCountPerPage();

        $items = $this->_adapter->getItems($offset, $this->getItemCountPerPage());

        $filter = $this->getFilter();

        if ($filter !== null) {
            $items = $filter->filter($items);
        }

        if (!$items instanceof Traversable) {
            $items = new ArrayIterator($items);
        }

        if ($this->_cacheEnabled()) {
            // Add custom tag to synchronize cache in paginators of admin and
            // default modules
            self::$_cache->save(
                $items,
                $this->_getCacheId($pageNumber),
                array(
                    $this->_getCacheInternalId(),
                    self::PROPERTIES_TAG, //Add EDECO custom tag
                )
            );
        }

        return $items;
    }

}