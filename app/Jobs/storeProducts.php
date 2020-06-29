<?php

namespace App\Jobs;

use App\Interfaces\OriginalStoreInterface;
use App\Interfaces\ProductInterface;

class StoreProducts extends Job
{




    /**
     * @var string
     */
    private $title;
    private $page_size;
    private $page;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $title, int $page_size, int $page)
    {
        $this->title = $title;
        $this->page_size = $page_size;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OriginalStoreInterface $originalData, ProductInterface $productInterface)
    {
        $data = $originalData->getByTitle($this->title, $this->page_size, $this->page);
        $products = $data['products'];
        foreach ($products as $product) {
            $productInterface->store($product);
        }


    }


}
