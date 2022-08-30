<?php
declare(strict_types=1);

namespace App\Shop\Infrastructure\Rest;

use App\Shop\Application\Command\Product\RemoveProductCommand;
use App\Shop\Application\Query\Product\GetProductsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use App\Shop\Infrastructure\Http\HttpSpec;
use App\Shop\Application\Query\Product\ProductsView;
use Symfony\Component\Uid\UuidV4;

final class RemoveProductAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /**
     * @Route("/api/products/{productId}", methods={"DELETE"}, name="api_delete_product")
     *
     * @param Request $request
     * @return Response
     *
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description=HttpSpec::STR_HTTP_OK,
     * )
     * @OA\Response(response=Response::HTTP_NOT_FOUND, description=HttpSpec::STR_HTTP_NOT_FOUND)
     * @OA\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @OA\Tag(name="Product")
     */
    public function __invoke(Request $request, string $productId): Response
    {

        $this->handle(new RemoveProductCommand(new UuidV4($productId)));

        return new Response(null, Response::HTTP_NO_CONTENT);

    }
}
