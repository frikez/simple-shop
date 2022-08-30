<?php
declare(strict_types=1);

namespace App\Shop\Infrastructure\Rest;

use App\Shop\Application\Query\Product\GetProductQuery;
use App\Shop\Domain\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use OpenApi\Annotations as OA;
use App\Shop\Infrastructure\Http\HttpSpec;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\UuidV4;


final class GetProductAction
{
    use HandleTrait;

    public function __construct(
        private NormalizerInterface $normalizer,
        MessageBusInterface $queryBus,
    )
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @Route("/api/products/{productId}", methods={"GET"}, name="api_get_product")
     *
     * @param Request $request
     * @param string $productId
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
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request, string $productId): Response
    {

        $product = $this->getProduct($productId);
        return new JsonResponse(
            $this->normalizer->normalize($product, '', [AbstractObjectNormalizer::ENABLE_MAX_DEPTH=> true]),
        );

    }

    public function getProduct(string $productId) {
        try {
            $product = $this->handle(new GetProductQuery(new UuidV4($productId)));

        } catch(HandlerFailedException $e) {
            if($e->getPrevious() instanceof ResourceNotFoundException) {
                return new Response(null, Response::HTTP_NOT_FOUND);
            }
        }

        return $product;
    }
}
