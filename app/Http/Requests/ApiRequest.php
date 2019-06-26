<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    protected $replace = [];
    protected $forbiddenMessages = [];
    protected $errorMessage = 'This action is unauthorized.';
    protected $errorCode = 403;
    protected $defaults = [];

    public function all($keys = null)
    {
        $request = parent::all($keys);

        $this->replaceKeys($request);

        $this->setDefaultRules($request);

        return $request;
    }

    private function replaceKeys(array $request): void
    {
        foreach ($this->replace as $oldKey => $key){
            if(isset($request[$oldKey])){
                $request[$key] = $request[$oldKey];
                $this[$key] = $request[$key];
                unset($request[$oldKey]);
            }
        }
    }

    private function setDefaultRules(array $request): void
    {
        foreach ($this->defaults as $key) {
            if (! isset($request[$key])) {
                $request[$key] = null;
                $this[$key] = $request[$key];
            }
        }
    }

    protected function beforeAuthorization(): void
    {
        //
    }

    public function authorize(): bool
    {
        $this->beforeAuthorization();

        foreach ((array)$this->authorizationRules() as $key => $value) {
            if(!$value) {
                if(isset($this->forbiddenMessages[$key])){
                    $this->errorMessage = ((array) $this->forbiddenMessages[$key])[0] ?? $this->errorMessage;
                    $this->errorCode = ((array) $this->forbiddenMessages[$key])[1] ?? $this->errorCode;
                }

                return false;
            }
        }

        return true;
    }

    protected function authorizationRules()
    {
        return [];
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException(...$this->forbiddenResponse());
    }

    public function forbiddenResponse(): array
    {
        return [$this->errorMessage, $this->errorCode];
    }

    public function validateResolved()
    {
        $this->init();

        $this->prepareForValidation();

        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $this->beforeValidation();

        if (! ($instance = $this->getValidatorInstance())->passes()) {
            $this->failedValidation($instance);
        }
    }

    protected function init(): void
    {
        //
    }

    protected function beforeValidation(): void
    {
        //
    }

    protected function standardFilteringRules(): array
    {
        return [
            'order_by.*'    => ['regex:/^(?:asc)|(?:desc)$/i'],
            'from'          => 'date',
            'to'            => 'date',
        ];
    }
}
