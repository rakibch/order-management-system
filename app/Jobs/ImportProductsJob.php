<?php
namespace App\Jobs;

use App\Services\ProductService as ProductImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportProductsJob implements ShouldQueue
{
    use Queueable;

    public $filePath;
    public $userId;

    public function __construct(string $filePath, int $userId)
    {
        $this->filePath = $filePath;
        $this->userId   = $userId;
    }

    public function handle(ProductImportService $importService)
    {
        $importService->import($this->filePath);
    }
}
