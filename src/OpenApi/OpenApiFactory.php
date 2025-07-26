<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\OpenApi;
use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\SecurityScheme;
use ApiPlatform\OpenApi\Model\Components;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $openApi->getComponents()->addSecurityScheme('bearerAuth', new Model\SecurityScheme(
            'http',
            'bearer',
            null,
            'JWT',
        ));

        $openApi->addSecurityRequirement(['bearerAuth' => []]);

        return $openApi;
    }
}
