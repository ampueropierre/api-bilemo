<?php

namespace App\Representation;

class Products
{
    public $data;
    public $meta;

    public function __construct($data)
    {
        $this->data = $data->getItems();
        $this->addMeta('limit', $data->getItemNumberPerPage());
        $this->addMeta('page', $data->getCurrentPageNumber());
        $this->addMeta('total_items', $data->getTotalItemCount());
    }

    public function addMeta($name, $value)
    {
        if (isset($this->meta[$name])) {
            throw new \LogicException(sprintf('This meta is already exists. You are trying to override this meta,
             use the setMeta method instead for the %s meta.',$name));
        }

        $this->setMeta($name,$value);
    }

    public function setMeta($name, $value)
    {
        $this->meta[$name] = $value;
    }
}
