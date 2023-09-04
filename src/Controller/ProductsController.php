<?php

namespace App\Controller;

use App\Cache\PromotionsCache;
use App\DTO\LowestPriceEnquiry;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{


    public function __construct(
        private ProductRepository $productRepository,
        private PromotionsCache $promotionsCache
    ) {
    }

    #[Route('/products/{id}/lowest-price', name: 'lowest_price', methods: ['POST'])]
    public function lowestPrice(
        Request $request,
        int $id,
        DTOSerializer $serializer,
        PromotionsFilterInterface $promotionsFilter,
    ): Response {


        // Deserialize = JSON -> DTO
        //   - Decode = JSON -> Array
        //   - Denormalize = Array -> DTO

        // Serialise = DTO -> JSON
        //   - Normalise = DTO -> Array
        //   - Encode = Array -> JSON

        if ($request->headers->has('force_fail')) {
            return new JsonResponse([
                'error' => 'Promotions Engine failure message',
            ], $request->headers->get('force_fail'));
        }

        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');

        $product = $this->productRepository->findOrFail($id);

        $lowestPriceEnquiry->setProduct($product);

        $productPromotions = $this->promotionsCache->findValidForProduct($product, new DateTime($lowestPriceEnquiry->getRequestDate()));

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$productPromotions);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new JsonResponse(data:$responseContent, status:Response::HTTP_OK, json: true);
    }

    #[Route('/products/{id}/promotions', name: 'promotions', methods: ['GET'])]
    public function promotions()
    {
        return $this->render('products/promotions.html.twig');
    }

}

/**
 * We are dealing with two entities:
 *
 *
 * 1. PRODUCT
 *      - id (int)
 *      - price (int)
 *
 * 2. PROMOTION
 *     - id (int)
 *     - name (string)
 *     - type (string)
 *     - adjustment (float)
 *     - criteria (string|json)
 *
 * 3. PRODUCT_PROMOTION
 *      - id (int)
 *      - product_id (int)
 *      - promotion_id (int)
 *      - valid_to (datetime)
 *
 *=============================================================================
 * id: 1
 * name: Black Friday half price sale!
 * type: date_range_multiplier
 * adjustment: 0.5
 * criteria: {"start_date": "2021-11-26", "end_date": "2021-11-29"}
 *=============================================================================
 *  id: 2
 *  name: Voucher OU812
 *  type: fixed_price_voucher
 *  adjustment: 100
 *  criteria: {"code": "OU812"}
 * =============================================================================
 */
