<?php
declare(strict_types=1);

namespace App\Shop\Infrastructure\Rest;

use App\Shop\Domain\Exception\InvalidInputDataException;
use App\Shop\Domain\Exception\ValidateCommandException;
use App\Shop\Domain\ValueObject\Currency;
use App\Shop\Domain\ValueObject\Money;
use App\Shop\Infrastructure\Http\HttpSpec;
use App\Shop\Application\Command\Product\CreateProductCommand;
use App\Shop\Infrastructure\Tools\ValidateCommandTrait;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ChoiceList\Loader\AbstractChoiceLoader;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateProductAction
{
    use ValidateCommandTrait;
    use HandleTrait;

    public function __construct(
        MessageBusInterface $commandBus,
        private RouterInterface $router,
        private ValidatorInterface $validator,
    )
    {
        $this->messageBus = $commandBus;
    }

    /**
     * @Route("/api/products", name="api_post_product", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @OA\RequestBody(@Model(type=CreateProductCommand::class))
     *
     * @OA\Response(response=Response::HTTP_CREATED, description=HttpSpec::STR_HTTP_CREATED)
     * @OA\Response(response=Response::HTTP_BAD_REQUEST, description=HttpSpec::STR_HTTP_BAD_REQUEST)
     * @OA\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @OA\Tag(name="Product")
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $command = new CreateProductCommand(
            $data['name'],
            $data['description'],
            new Money(
                $data['price']['amount'],
                new Currency($data['price']['currency']
                )
            ),
        );

        try {
            $this->validate($command);
        } catch (ValidateCommandException $e) {
            return new JsonResponse($e->getPropertiesErrors(),400);
        }

        $productId = $this->handle($command);

        $resourceUrl = $this->router->generate('api_get_product', ['productId' => $productId]);

        return new JsonResponse(null, Response::HTTP_CREATED, [HttpSpec::HEADER_LOCATION => $resourceUrl]);
    }
}
