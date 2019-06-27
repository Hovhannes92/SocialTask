<?php

namespace App\Http\Requests;

//use App\Models\BaseModel;

abstract class DataPersistRequest extends ApiRequest
{
    protected $fileNames = [];

//    protected $storePath = BaseModel::ATTACHMENTS_PATH;

    public abstract function persist();

    public function storeFilesIfExists(): array
    {
        $filePaths = [];
        foreach ($this->fileNames as $fileName => $storeName){
            if (is_int($fileName)) {
                $fileName = $storeName;
            }
            if ($this->hasFile($fileName)) {
                $path = $this->file($fileName)->store($this->storePath);
                $filePaths[$storeName] = removeBaseAttachmentPath($path);
            }
        }

        return $filePaths;
    }

    public function getResponseMessage(): array
    {
        return [$this->getMessage()];
    }

    protected function getMessage(): string
    {
        return 'Data successfully persisted';
    }

    protected function getMergingData(): array
    {
        return [];
    }

    protected function getProcessedData(?array $data = null): array
    {
        return array_merge(
            $data ?? $this->all(),
            $this->storeFilesIfExists(),
            $this->getMergingData()
        );
    }
}
