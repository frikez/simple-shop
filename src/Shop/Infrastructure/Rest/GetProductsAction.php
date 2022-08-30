<?php
declare(strict_types=1);

namespace App\Shop\Infrastructure\Rest;

use App\Shop\Application\Query\Product\GetProductsQuery;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use App\Shop\Infrastructure\Http\HttpSpec;
use App\Shop\Application\Query\Product\ProductsView;

final class GetProductsAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $queryBus, NormalizerInterface $normalizer)
    {
        $this->messageBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/api/products", methods={"GET"}, name="api_get_products")
     *
     * @param Request $request
     * @return Response
     *
     * @throws ExceptionInterface
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description=HttpSpec::STR_HTTP_OK,
     *     @OA\Schema(ref=@Model(type=ProductsView::class))
     * )
     * @OA\Response(response=Response::HTTP_NOT_FOUND, description=HttpSpec::STR_HTTP_NOT_FOUND)
     * @OA\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @OA\Tag(name="Product")
     */
    public function __invoke(Request $request): Response
    {
        list($perPage, $offset, $orderBy, $orderDirection) = $this->getPaginationParameters($request);

        $products = $this->handle(new GetProductsQuery($perPage, $offset, $orderBy, $orderDirection));

        return new JsonResponse(
            $this->normalizer->normalize($products, '', [AbstractObjectNormalizer::ENABLE_MAX_DEPTH=> true]),
        );
    }

    #[ArrayShape([
        'perPage' => "int",
        'offset' => "int",
        'sortBy' => "string",
        'sortDirection' => "string"
    ])]
    private function getPaginationParameters(Request $request): array {
        $perPage = intval($request->query->get('perPage', 3));
        $page = intval($request->query->get('page', 1));
        $offset = $perPage * ($page - 1);
        $orderBy = $request->query->get('orderBy', 'name');
        $orderDirection = $request->query->get('orderDirection', 'ASC');

        return [
            $perPage,
            $offset,
            $orderBy,
            $orderDirection,
        ];
   }
}
